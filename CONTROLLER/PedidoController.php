<?php
require_once __DIR__ . '/../MODEL/conexion.php';

/**
 * CONTROLLER/PedidoController.php
 * ------------------------------
 * Controlador encargado de:
 *  - Crear pedidos a partir del carrito (carrito en sesión, alimentado desde checkout)
 *  - Validar método de pago (servidor) con expresiones regulares
 *  - Insertar pedido + líneas (order_items)
 *  - Descontar stock de forma atómica (sin permitir stock negativo)
 *  - Mostrar el historial de pedidos del usuario (MisPedidos)
 */
class PedidoController
{
    /**
     * Crear un nuevo pedido.
     * Flujo esperado:
     *  1) checkout.php pasa el carrito (localStorage) a $_SESSION['carrito']
     *  2) El usuario confirma el pedido -> POST /pedido/crear
     *  3) Aquí se valida sesión, carrito, pago y stock
     *  4) Se crea el pedido (orders) y sus líneas (order_items)
     *  5) Se redirige a /pedido/ok y se vacía el carrito de sesión
     */
    public static function crear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // El pedido solo lo puede crear un usuario autenticado
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        // Carrito en sesión (checkout lo debe pasar a sesión)
        $carrito = $_SESSION['carrito'] ?? [];
        if (!$carrito) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'El carrito está vacío.'];
            header("Location: /carrito");
            exit;
        }

        // user_id debe existir en sesión
        $userId = (int)($_SESSION['usuario']['id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sesión inválida.'];
            header("Location: /login");
            exit;
        }

        // =========================================================
        // VALIDACIÓN SERVIDOR: MÉTODO DE PAGO (blindaje real)
        // =========================================================

        // Método permitido
        $pagoTipo = isset($_POST['pago_tipo']) ? trim((string)$_POST['pago_tipo']) : '';
        $permitidos = ['tarjeta', 'paypal', 'transferencia'];
        if (!in_array($pagoTipo, $permitidos, true)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Método de pago inválido.'];
            header("Location: /checkout");
            exit;
        }

        // Helper: validar con regex
        $rx = function (string $value, string $pattern): bool {
            return (bool)preg_match($pattern, $value);
        };

        // Datos que guardaremos en el pedido (NO guardar CVV ni nº completo)
        $pagoDetalle = null;

        if ($pagoTipo === 'tarjeta') {
            $cardName   = trim((string)($_POST['card_name'] ?? ''));
            $cardNumber = trim((string)($_POST['card_number'] ?? ''));
            $cardExp    = trim((string)($_POST['card_exp'] ?? ''));
            $cardCvv    = trim((string)($_POST['card_cvv'] ?? ''));

            // Titular: solo letras (incluye acentos/ñ) y espacios, mínimo 2 palabras (nombre + apellido)
            if ($cardName === '' || !$rx($cardName, '/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+(?: [A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+)+$/u')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Titular inválido. Solo letras y espacios, e incluir nombre y apellido.'];
                header("Location: /checkout");
                exit;
            }

            // Número tarjeta: se permiten espacios, pero la longitud real debe ser 13-19 dígitos
            $cardDigits = preg_replace('/\s+/', '', $cardNumber);
            if ($cardDigits === null) $cardDigits = '';
            if (!$rx($cardDigits, '/^[0-9]{13,19}$/')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Número de tarjeta inválido. Debe tener entre 13 y 19 dígitos.'];
                header("Location: /checkout");
                exit;
            }

            // Caducidad: MM/AA y no vencida
            if (!$rx($cardExp, '/^(0[1-9]|1[0-2])\/([0-9]{2})$/')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Caducidad inválida. Formato MM/AA.'];
                header("Location: /checkout");
                exit;
            } else {
                $mm = (int)substr($cardExp, 0, 2);
                $yy = (int)substr($cardExp, 3, 2);

                $curYY = (int)date('y');
                $curMM = (int)date('n');

                if ($yy < $curYY || ($yy === $curYY && $mm < $curMM)) {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'La tarjeta está vencida.'];
                    header("Location: /checkout");
                    exit;
                }
            }

            // CVV: 3 o 4 dígitos (NO se guarda)
            if (!$rx($cardCvv, '/^[0-9]{3,4}$/')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'CVV inválido.'];
                header("Location: /checkout");
                exit;
            }

            // Guardamos solo info no sensible: últimos 4 + caducidad + titular
            $last4 = substr($cardDigits, -4);
            $pagoDetalle = "Tarjeta ****{$last4}" . ($cardExp ? " ({$cardExp})" : "") . ($cardName ? " - Titular: {$cardName}" : "");
        }

        if ($pagoTipo === 'paypal') {
            $paypalEmail = trim((string)($_POST['paypal_email'] ?? ''));

            // Email válido (servidor)
            if ($paypalEmail === '' || !filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Email de PayPal inválido.'];
                header("Location: /checkout");
                exit;
            }

            $pagoDetalle = "PayPal: {$paypalEmail}";
        }

        if ($pagoTipo === 'transferencia') {
            $transferRef = trim((string)($_POST['transfer_ref'] ?? ''));

            // Referencia obligatoria: 4-30, letras/números/guión
            if ($transferRef === '' || !$rx($transferRef, '/^[A-Za-z0-9-]{4,30}$/')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Código/referencia de transferencia inválida (obligatoria, 4–30).'];
                header("Location: /checkout");
                exit;
            }

            $pagoDetalle = "Transferencia: {$transferRef}";
        }

        // =========================================================
        // BBDD + TRANSACCIÓN (si algo falla -> rollback)
        // =========================================================
        $pdo = conexion::conexionBBDD();
        $pdo->beginTransaction();

        try {
            // Calcular total del carrito (servidor)
            $total = 0.0;
            foreach ($carrito as $i) {
                $precio   = (float)($i['precio'] ?? 0);
                $cantidad = (int)($i['cantidad'] ?? 0);

                // Descarta valores inválidos
                if ($precio < 0 || $cantidad <= 0) continue;

                $total += $precio * $cantidad;
            }

            if ($total <= 0) {
                throw new Exception("Total inválido.");
            }

            /**
             * Insert del pedido:
             * - metodo_pago / pago_tipo: método elegido
             * - estado: estado inicial (pendiente)
             * - pago_detalle: dato NO sensible (ej. últimos 4, email paypal, ref transferencia)
             *
             * IMPORTANTE:
             * Si tu tabla orders no tiene alguna columna, debes quitarla del INSERT.
             */
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, precio_total, metodo_pago, estado, pago_tipo, pago_detalle)
                VALUES (:uid, :total, :metodo, :estado, :pago_tipo, :pago_detalle)
            ");
            $stmt->execute([
                ':uid'         => $userId,
                ':total'       => $total,
                ':metodo'      => $pagoTipo,
                ':estado'      => 'pendiente',
                ':pago_tipo'   => $pagoTipo,
                ':pago_detalle' => $pagoDetalle
            ]);

            $orderId = (int)$pdo->lastInsertId();

            /**
             * Insert de líneas del pedido + descuento de stock seguro.
             * El descuento de stock se hace con UPDATE condicional:
             *  UPDATE ... SET stock = stock - X WHERE id=? AND stock >= X
             * Esto evita que el stock quede negativo incluso con compras simultáneas.
             */
            foreach ($carrito as $i) {
                $type = $i['type'] ?? ($i['tipo'] ?? null); // 'book' o 'other'
                $pid  = (int)($i['id'] ?? 0);
                $cant = (int)($i['cantidad'] ?? 1);
                $precioUnit = (float)($i['precio'] ?? 0);

                if ($pid <= 0 || $cant <= 0 || $precioUnit < 0) continue;

                if ($type === 'book') {
                    // Descuento de stock de libros (atómico / sin negativos)
                    $upd = $pdo->prepare("
                        UPDATE books
                        SET stock = stock - :c1
                        WHERE book_id = :id AND stock >= :c2
                    ");
                    $upd->execute([':c1' => $cant, ':c2' => $cant, ':id' => $pid]);

                    // Si no se actualiza ninguna fila -> no hay stock suficiente (o no existe)
                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el libro (ID {$pid}).");
                    }

                    $ptype = 1; // convención: 1 = book
                } else {
                    // Descuento de stock de otros productos (atómico / sin negativos)
                    $upd = $pdo->prepare("
                        UPDATE other_products
                        SET stock = stock - :c1
                        WHERE product_id = :id AND stock >= :c2
                    ");
                    $upd->execute([':c1' => $cant, ':c2' => $cant, ':id' => $pid]);

                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el producto (ID {$pid}).");
                    }

                    $ptype = 2; // convención: 2 = other
                }

                // Insertar línea del pedido
                $stmt = $pdo->prepare("
                    INSERT INTO order_items
                    (order_id, product_type, product_id, cantidad, precio_unitario)
                    VALUES (:order_id, :ptype, :pid, :cant, :precio)
                ");
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':ptype'    => $ptype,
                    ':pid'      => $pid,
                    ':cant'     => $cant,
                    ':precio'   => $precioUnit
                ]);
            }

            // Todo OK -> confirmamos transacción
            $pdo->commit();

            // Vaciar carrito en sesión (el carrito real del cliente lo vacías en PedidoOk.php con JS)
            unset($_SESSION['carrito']);

            header("Location: /pedido/ok");
            exit;
        } catch (Exception $e) {
            // Si algo falla -> deshacer todo (pedido + líneas + stock)
            $pdo->rollBack();

            $_SESSION['flash'] = ['type' => 'error', 'msg' => $e->getMessage()];
            header("Location: /carrito");
            exit;
        }
    }

    /**
     * Listado de pedidos del usuario autenticado.
     * Carga la vista VIEW/MisPedidos.php con $pedidos.
     */
    public static function misPedidos()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        $userId = (int)($_SESSION['usuario']['id'] ?? 0);
        if ($userId <= 0) {
            http_response_code(500);
            echo "Sesión inválida.";
            exit;
        }

        $pdo = conexion::conexionBBDD();

        // Pedidos del usuario (incluye estado + método/pago_detalle)
        $stmt = $pdo->prepare("
            SELECT order_id, precio_total, metodo_pago, estado, pago_detalle
            FROM orders
            WHERE user_id = :uid
            ORDER BY order_id DESC
        ");
        $stmt->execute([':uid' => $userId]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = __DIR__ . '/../VIEW/MisPedidos.php';
        if (!file_exists($view)) {
            http_response_code(500);
            echo "Falta la vista: VIEW/MisPedidos.php";
            exit;
        }

        require $view;
    }
}

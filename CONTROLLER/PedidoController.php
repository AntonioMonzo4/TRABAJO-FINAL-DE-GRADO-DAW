<?php
require_once __DIR__ . '/../MODEL/conexion.php';


/* CONTROLLER/PedidoController.php
   Controlador para manejar la creación y visualización de pedidos.
*/

class PedidoController
{

// Crear un nuevo pedido desde el carrito de compras  
    public static function crear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        // Verificar que el carrito no esté vacío
        $carrito = $_SESSION['carrito'] ?? [];

        //salta error si el carrito está vacío
        if (!$carrito) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'El carrito está vacío.'];
            header("Location: /carrito");
            exit;
        }

        // Obtener ID de usuario para el pedido 
        $userId = (int)($_SESSION['usuario']['id'] ?? 0);

        // Verificar ID de usuario válido 
        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sesión inválida.'];
            header("Location: /login");
            exit;
        }

        // Tipo de pago y detalles 
        $pagoTipo = (string)($_POST['pago_tipo'] ?? 'tarjeta');
        $permitidos = ['tarjeta', 'paypal', 'transferencia'];

        // Validar tipo de pago
        if (!in_array($pagoTipo, $permitidos, true)) $pagoTipo = 'tarjeta';

        $pagoDetalle = null;

        // Recopilar detalles según el tipo de pago 
        //Si es tarjeta, paypal o transferencia
        if ($pagoTipo === 'tarjeta') {

            // Datos de la tarjeta (simulados, no se almacenan completos por seguridad)

            $name = trim((string)($_POST['card_name'] ?? ''));
            $num  = preg_replace('/\D+/', '', (string)($_POST['card_number'] ?? ''));
            $exp  = trim((string)($_POST['card_exp'] ?? ''));

            $last4 = strlen($num) >= 4 ? substr($num, -4) : '----';
            // Generar detalle de pago
            $pagoDetalle = "Tarjeta ****{$last4}" . ($exp ? " ({$exp})" : "") . ($name ? " - Titular: {$name}" : "");
        }

        // PayPal o Transferencia 
        if ($pagoTipo === 'paypal') {
            $mail = trim((string)($_POST['paypal_email'] ?? ''));
            $pagoDetalle = $mail ? "PayPal: {$mail}" : "PayPal";
        }

        if ($pagoTipo === 'transferencia') {
            $ref = trim((string)($_POST['transfer_ref'] ?? ''));
            $pagoDetalle = $ref ? "Transferencia: {$ref}" : "Transferencia";
        }

        // Conectar a la base de datos y crear el pediddo
        $pdo = conexion::conexionBBDD();
        //beginTransaction inicia una transacción de base de datos y permite agrupar múltiples operaciones en una sola unidad de trabajo. Permite asegurar que todas las operaciones se completen correctamente antes de confirmar los cambios en la base de datos.
        $pdo->beginTransaction();

        // Usar try-catch para manejar errores y hacer rollback si es necesario
        try {
            // Total
            $total = 0.0;
            foreach ($carrito as $i) {
                $precio = (float)($i['precio'] ?? 0);
                $cantidad = (int)($i['cantidad'] ?? 0);
                if ($precio < 0 || $cantidad <= 0) continue;
                $total += $precio * $cantidad;
            }

            if ($total <= 0) {
                throw new Exception("Total inválido.");
            }

            // Crear pedido y obtener ID
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, precio_total, metodo_pago, estado, pago_tipo, pago_detalle)
                VALUES (:uid, :total, :metodo, :estado, :pago_tipo, :pago_detalle)
            ");
            // Ejecutar la inserción del pedido y asi obtener el ID del pedido recién creado
            $stmt->execute([
                ':uid' => $userId,
                ':total' => $total,
                ':metodo' => $pagoTipo,
                ':estado' => 'pendiente',
                ':pago_tipo' => $pagoTipo,
                ':pago_detalle' => $pagoDetalle
            ]);

            // ID del pedido recién creado
            $orderId = (int)$pdo->lastInsertId();

            // Líneas + descuento de stock seguro (NO permite negativos)
            foreach ($carrito as $i) {
                // Tipo de producto, ID, cantidad, precio unitario se asigna mediante el array carrito
                $type = $i['type'] ?? ($i['tipo'] ?? null); // 'book' o 'other'
                $pid  = (int)($i['id'] ?? 0);
                $cant = (int)($i['cantidad'] ?? 1);
                $precioUnit = (float)($i['precio'] ?? 0);

                // Validar datos si no son válidos, saltar
                if ($pid <= 0 || $cant <= 0 || $precioUnit < 0) continue;

                if ($type === 'book') {
                    // Actualizar stock de libros. El uso de :c es para evitar inyección SQL y asegurar que el stock no sea negativo
                    $upd = $pdo->prepare("
                        UPDATE books
                        SET stock = stock - :c1
                        WHERE book_id = :id AND stock >= :c2
                    ");
                    //:c1 y :c2 son los mismos valores para asegurar que el stock no sea negativo
                    //:c1 es la cantidad a restar y :c2 es la cantidad mínima requerida para realizar la resta

                    $upd->execute([':c1' => $cant, ':c2' => $cant, ':id' => $pid]);

                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el libro (ID {$pid}).");
                    }
                } else {
                    // Actualizar stock de otros productos
                    $upd = $pdo->prepare("
                        UPDATE other_products
                        SET stock = stock - :c1
                        WHERE product_id = :id AND stock >= :c2
                    ");
                    $upd->execute([':c1' => $cant, ':c2' => $cant, ':id' => $pid]);

                    // Verificar si se actualizó alguna fila, si no, stock insuficiente
                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el producto (ID {$pid}).");
                    }
                }

                // Insertar línea de pedido 
                $stmt = $pdo->prepare("
                    INSERT INTO order_items
                    (order_id, product_type, product_id, cantidad, precio_unitario)
                    VALUES (:order_id, :ptype, :pid, :cant, :precio)
                ");
                // Ejecutar la inserción de la línea del pedido
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':ptype'    => ($type === 'book') ? 1 : 2,
                    ':pid'      => $pid,
                    ':cant'     => $cant,
                    ':precio'   => $precioUnit
                ]);
            }

            // Confirmar transacción 
            $pdo->commit();
            // Pedido creado con éxito y carrito vacío
            unset($_SESSION['carrito']);

            header("Location: /pedido/ok");
            exit;
            // Manejo de errores: rollback y mensaje
        } catch (Exception $e) {
            //rollBack() revierte la transacción actual, deshaciendo todos los cambios realizados durante la transacción. Se utiliza para mantener la integridad de los datos en caso de errores o fallos.
            $pdo->rollBack();
            // Mensaje de error al usuario flash y redirigir al carrito
            $_SESSION['flash'] = ['type' => 'error', 'msg' => $e->getMessage()];
            header("Location: /carrito");
            exit;
        }
    }

    public static function misPedidos()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        require __DIR__ . '/../VIEW/MisPedidos.php';
    }
}

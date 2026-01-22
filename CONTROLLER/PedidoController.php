<?php

require_once __DIR__ . '/../MODEL/conexion.php';

class PedidoController
{
    public static function crear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        $carrito = $_SESSION['carrito'] ?? [];
        if (!$carrito) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'El carrito está vacío.'];
            header("Location: /carrito");
            exit;
        }

        $userId = (int)($_SESSION['usuario']['id'] ?? 0); // CLAVE CORRECTA
        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sesión inválida.'];
            header("Location: /login");
            exit;
        }

        $metodo = (string)($_POST['metodo_pago'] ?? 'tarjeta');

        $pdo = conexion::conexionBBDD();
        $pdo->beginTransaction();

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

            // Crear pedido
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, precio_total, metodo_pago)
                VALUES (:uid, :total, :metodo)
            ");
            $stmt->execute([
                ':uid' => $userId,
                ':total' => $total,
                ':metodo' => $metodo
            ]);

            $orderId = (int)$pdo->lastInsertId();

            // Líneas + descuento de stock seguro (NO permite negativos)
            foreach ($carrito as $i) {
                $type = $i['type'] ?? ($i['tipo'] ?? null); // 'book' o 'other'
                $pid  = (int)($i['id'] ?? 0);
                $cant = (int)($i['cantidad'] ?? 1);
                $precioUnit = (float)($i['precio'] ?? 0);

                if ($pid <= 0 || $cant <= 0 || $precioUnit < 0) continue;

                // 1) Descontar stock de forma segura
                if ($type === 'book') {
                    $upd = $pdo->prepare("
                        UPDATE books
                        SET stock = stock - :c
                        WHERE book_id = :id AND stock >= :c
                    ");
                    $upd->execute([':c' => $cant, ':id' => $pid]);

                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el libro (ID {$pid}).");
                    }
                } else {
                    $upd = $pdo->prepare("
                        UPDATE other_products
                        SET stock = stock - :c
                        WHERE product_id = :id AND stock >= :c
                    ");
                    $upd->execute([':c' => $cant, ':id' => $pid]);

                    if ($upd->rowCount() === 0) {
                        throw new Exception("Stock insuficiente para el producto (ID {$pid}).");
                    }
                }

                // 2) Insertar línea de pedido SOLO si el stock se descontó bien
                $stmt = $pdo->prepare("
                    INSERT INTO order_items
                    (order_id, product_type, product_id, cantidad, precio_unitario)
                    VALUES (:order_id, :ptype, :pid, :cant, :precio)
                ");
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':ptype'    => ($type === 'book') ? 1 : 2,
                    ':pid'      => $pid,
                    ':cant'     => $cant,
                    ':precio'   => $precioUnit
                ]);
            }

            $pdo->commit();

            // Vaciar carrito de sesión (el de localStorage lo vacías en PedidoOk.php)
            unset($_SESSION['carrito']);

            header("Location: /pedido/ok");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
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

        $userId = (int)($_SESSION['usuario']['id'] ?? 0);
        if ($userId <= 0) {
            http_response_code(500);
            echo "Sesión inválida.";
            exit;
        }

        $pdo = conexion::conexionBBDD();

        // OJO: tu tabla no tiene created_at (ya lo comprobaste)
        $stmt = $pdo->prepare("
            SELECT order_id, precio_total, metodo_pago
            FROM orders
            WHERE user_id = :uid
            ORDER BY order_id DESC
        ");
        $stmt->execute([':uid' => $userId]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar vista
        $view = __DIR__ . '/../VIEW/MisPedidos.php';
        if (!file_exists($view)) {
            http_response_code(500);
            echo "Falta la vista: VIEW/MisPedidos.php";
            exit;
        }
        require $view;
    }
}

 
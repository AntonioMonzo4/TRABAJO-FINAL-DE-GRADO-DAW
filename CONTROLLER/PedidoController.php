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
            header("Location: /carrito");
            exit;
        }

        $pdo = conexion::conexionBBDD();
        $pdo->beginTransaction();

        try {
            $total = 0;
            foreach ($carrito as $i) {
                $precio = (float)($i['precio'] ?? 0);
                $cantidad = (int)($i['cantidad'] ?? 0);
                $total += $precio * $cantidad;
            }

            // OJO: en sesión el id es 'id' (no 'user_id')
            $userId = (int)($_SESSION['usuario']['id'] ?? 0);
            if ($userId <= 0) {
                throw new Exception("Usuario en sesión inválido");
            }

            $metodo = $_POST['metodo_pago'] ?? 'tarjeta';

            // Crear pedido
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, precio_total, metodo_pago)
                VALUES (:user_id, :total, :metodo)
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':total'   => $total,
                ':metodo'  => $metodo
            ]);

            $orderId = (int)$pdo->lastInsertId();

            // Líneas de pedido + stock
            foreach ($carrito as $i) {
                $type = $i['type'] ?? ($i['tipo'] ?? null); // por si viene con 'tipo'
                $pid  = (int)($i['id'] ?? 0);
                $cant = (int)($i['cantidad'] ?? 1);
                $precioUnit = (float)($i['precio'] ?? 0);

                if ($pid <= 0 || $precioUnit <= 0) continue;

                $stmt = $pdo->prepare("
                    INSERT INTO order_items
                    (order_id, product_type, product_id, cantidad, precio_unitario)
                    VALUES (:order, :type, :pid, :cant, :precio)
                ");
                $stmt->execute([
                    ':order'  => $orderId,
                    ':type'   => ($type === 'book') ? 1 : 2,
                    ':pid'    => $pid,
                    ':cant'   => $cant,
                    ':precio' => $precioUnit
                ]);

                if ($type === 'book') {
                    $pdo->prepare("
                        UPDATE books SET stock = stock - :c WHERE book_id = :id
                    ")->execute([':c' => $cant, ':id' => $pid]);
                } else {
                    $pdo->prepare("
                        UPDATE other_products SET stock = stock - :c WHERE product_id = :id
                    ")->execute([':c' => $cant, ':id' => $pid]);
                }
            }

            $pdo->commit();
            unset($_SESSION['carrito']);

            header("Location: /pedido/ok");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
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

        // Pasamos datos a la vista via variables
        $pdo = conexion::conexionBBDD();
        $userId = (int)($_SESSION['usuario']['id'] ?? 0);

        $stmt = $pdo->prepare("
            SELECT order_id, precio_total, metodo_pago, created_at
            FROM orders
            WHERE user_id = :uid
            ORDER BY order_id DESC
        ");
        $stmt->execute([':uid' => $userId]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargar vista
        // (queda disponible $pedidos)
        $docRoot = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
        $candidatos = [
            $docRoot . '/VIEW/MisPedidos.php',
            $docRoot . '/view/MisPedidos.php',
            __DIR__ . '/../VIEW/MisPedidos.php',
            __DIR__ . '/../view/MisPedidos.php',
        ];
        foreach ($candidatos as $f) {
            if ($f && file_exists($f)) { require $f; return; }
        }

        http_response_code(404);
        echo "<h1>Error 404</h1><p>Vista no encontrada: MisPedidos.php</p>";
        exit;
    }
}

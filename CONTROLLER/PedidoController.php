<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class PedidoController
{
    public static function crear()
    {
        session_start();

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
                $total += $i['precio'] * $i['cantidad'];
            }

            // Crear pedido
            $stmt = $pdo->prepare("
              INSERT INTO orders (user_id, precio_total, metodo_pago)
              VALUES (:user_id, :total, :metodo)
            ");
            $stmt->execute([
                ':user_id' => $_SESSION['usuario']['user_id'],
                ':total'   => $total,
                ':metodo'  => $_POST['metodo_pago']
            ]);

            $orderId = $pdo->lastInsertId();

            // Líneas de pedido + stock
            foreach ($carrito as $i) {
                // item
                $stmt = $pdo->prepare("
                  INSERT INTO order_items
                  (order_id, product_type, product_id, cantidad, precio_unitario)
                  VALUES (:order, :type, :pid, :cant, :precio)
                ");
                $stmt->execute([
                    ':order'  => $orderId,
                    ':type'   => ($i['type'] === 'book') ? 1 : 2,
                    ':pid'    => $i['id'],
                    ':cant'   => $i['cantidad'],
                    ':precio' => $i['precio']
                ]);

                // stock
                if ($i['type'] === 'book') {
                    $pdo->prepare("
                      UPDATE books SET stock = stock - :c WHERE book_id = :id
                    ")->execute([':c' => $i['cantidad'], ':id' => $i['id']]);
                } else {
                    $pdo->prepare("
                      UPDATE other_products SET stock = stock - :c WHERE product_id = :id
                    ")->execute([':c' => $i['cantidad'], ':id' => $i['id']]);
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
}

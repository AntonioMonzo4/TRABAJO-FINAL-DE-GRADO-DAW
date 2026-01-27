<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class StockController
{
    public static function panel()
    {
        require __DIR__ . '/../VIEW/admin/AdminPanel.php';
    }

    public static function stock()
    {
        $pdo = conexion::conexionBBDD();

        $libros = $pdo->query("
            SELECT book_id, titulo, autor, precio, stock
            FROM books
            ORDER BY book_id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $otros = $pdo->query("
            SELECT product_id, nombre, precio, stock
            FROM other_products
            ORDER BY product_id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../VIEW/admin/AdminStock.php';
    }

    public static function stockGuardar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $tipo  = (string)($_POST['tipo'] ?? '');
        $id    = (int)($_POST['id'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);
        $stock  = (int)($_POST['stock'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'ID inválido'];
            header("Location: /admin/stock");
            exit;
        }

        if ($precio < 0) $precio = 0;
        if ($stock < 0) $stock = 0;

        if ($tipo === 'book') {
            $stmt = $pdo->prepare("UPDATE books SET precio = :p, stock = :s WHERE book_id = :id");
        } else {
            $stmt = $pdo->prepare("UPDATE other_products SET precio = :p, stock = :s WHERE product_id = :id");
        }

        $stmt->execute([':p' => $precio, ':s' => $stock, ':id' => $id]);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Producto actualizado'];
        header("Location: /admin/stock");
        exit;
    }

    public static function pedidos()
    {
        $pdo = conexion::conexionBBDD();

        $pedidos = $pdo->query("
            SELECT order_id, user_id, precio_total, metodo_pago, estado, pago_detalle, fecha_pedido
            FROM orders
            ORDER BY order_id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../VIEW/admin/AdminPedidos.php';
    }

    public static function pedidoEstado()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $orderId = (int)($_POST['order_id'] ?? 0);
        $estado  = (string)($_POST['estado'] ?? 'pendiente');

        $permitidos = ['pendiente', 'en_camino', 'finalizado'];
        if (!in_array($estado, $permitidos, true)) $estado = 'pendiente';

        if ($orderId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Pedido inválido'];
            header("Location: /admin/pedidos");
            exit;
        }

        $stmt = $pdo->prepare("UPDATE orders SET estado = :e WHERE order_id = :id");
        $stmt->execute([':e' => $estado, ':id' => $orderId]);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Estado actualizado'];
        header("Location: /admin/pedidos");
        exit;
    }
}

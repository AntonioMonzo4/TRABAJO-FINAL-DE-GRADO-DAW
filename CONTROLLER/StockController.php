<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class StockController
{
    public static function actualizar()
    {
        session_start();
        if ($_SESSION['usuario']['rol'] !== 'admin') exit;

        $pdo = conexion::conexionBBDD();

        $pdo->prepare("
          UPDATE books SET stock = :stock, precio = :precio
          WHERE book_id = :id
        ")->execute([
            ':stock' => (int)$_POST['stock'],
            ':precio' => (float)$_POST['precio'],
            ':id' => (int)$_POST['id']
        ]);

        header("Location: /admin/stock");
        exit;
    }
}

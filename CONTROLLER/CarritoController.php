<?php

class CarritoController
{
    public static function add()
    {
        session_start();

        $type = $_POST['product_type'];
        $id = (int)$_POST['product_id'];
        $titulo = $_POST['titulo'] ?? '';
        $precio = (float)$_POST['precio'];
        $cantidad = max(1, (int)$_POST['cantidad']);
        $imagen = $_POST['imagen'] ?? null;

        $key = $type . '_' . $id;

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$key])) {
            $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$key] = [
                'type' => $type,
                'id' => $id,
                'titulo' => $titulo,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => $imagen
            ];
        }

        header("Location: /carrito");
        exit;
    }

    public static function update()
    {
        session_start();

        foreach ($_POST['cantidades'] as $key => $cantidad) {
            if (isset($_SESSION['carrito'][$key])) {
                $_SESSION['carrito'][$key]['cantidad'] = max(1, (int)$cantidad);
            }
        }

        header("Location: /carrito");
        exit;
    }

    public static function remove()
    {
        session_start();

        $key = $_POST['key'];
        unset($_SESSION['carrito'][$key]);

        header("Location: /carrito");
        exit;
    }
}

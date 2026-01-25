<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class UsuariosAdminController
{
    public static function index()
    {
        $pdo = conexion::conexionBBDD();

        // IMPORTANTE: si tu tabla NO se llama users, cámbialo aquí
        $usuarios = $pdo->query("
            SELECT id, nombre, email, rol
            FROM users
            ORDER BY id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../VIEW/admin/Usuarios.php';
    }
}

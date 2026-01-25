<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class UsuariosAdminController
{
    public static function guardar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $id  = (int)($_POST['id'] ?? 0);
        $rol = (string)($_POST['rol'] ?? 'cliente');
        if (!in_array($rol, ['admin', 'cliente'], true)) $rol = 'cliente';

        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'ID inválido'];
            header("Location: /admin/usuarios");
            exit;
        }

        $stmt = $pdo->prepare("UPDATE users SET rol = :r WHERE id = :id");
        $stmt->execute([':r' => $rol, ':id' => $id]);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Rol actualizado'];
        header("Location: /admin/usuarios");
        exit;
    }

    public static function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $id = (int)($_POST['id'] ?? 0);
        $miId = (int)($_SESSION['usuario']['id'] ?? 0);

        if ($id <= 0 || $id === $miId) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'No puedes eliminar tu propio usuario'];
            header("Location: /admin/usuarios");
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Usuario eliminado'];
        header("Location: /admin/usuarios");
        exit;
    }
}

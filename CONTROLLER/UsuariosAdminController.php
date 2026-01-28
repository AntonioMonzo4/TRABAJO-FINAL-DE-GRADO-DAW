<?php
/* CONTROLLER/UsuariosAdminController.php
   Clase para gestionar usuarios desde el panel de administración.
*/
require_once __DIR__ . '/../MODEL/conexion.php';

// Controlador para gestionar usuarios desde el panel de administración
class UsuariosAdminController
{
    // Gestionar usuarios - mostrar lista y guardar cambios
    public static function guardar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $userId = (int)($_POST['user_id'] ?? 0);
        $rol = (string)($_POST['rol'] ?? 'cliente');

        if (!in_array($rol, ['admin', 'cliente'], true)) $rol = 'cliente';

        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Usuario inválido'];
            header("Location: /admin/usuarios");
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE users SET rol = :r WHERE user_id = :uid");
            $stmt->execute([':r' => $rol, ':uid' => $userId]);

            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Rol actualizado'];
            header("Location: /admin/usuarios");
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Error guardando rol: ' . $e->getMessage()];
            header("Location: /admin/usuarios");
            exit;
        }
    }

    // Eliminar usuario
    public static function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $pdo = conexion::conexionBBDD();

        $userId = (int)($_POST['user_id'] ?? 0);
        $miId = (int)($_SESSION['usuario']['id'] ?? $_SESSION['usuario']['user_id'] ?? 0);

        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Usuario inválido'];
            header("Location: /admin/usuarios");
            exit;
        }

        if ($userId === $miId) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'No puedes eliminar tu propio usuario'];
            header("Location: /admin/usuarios");
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :uid");
            $stmt->execute([':uid' => $userId]);

            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Usuario eliminado'];
            header("Location: /admin/usuarios");
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Error eliminando usuario: ' . $e->getMessage()];
            header("Location: /admin/usuarios");
            exit;
        }
    }
}

<?php
class AdminGuard
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Tu sesión real usa $_SESSION['usuario']
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        $rol = $_SESSION['usuario']['rol'] ?? 'cliente';
        if ($rol !== 'admin') {
            http_response_code(403);
            echo "Acceso denegado";
            exit;
        }
    }
}

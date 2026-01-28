<?php

/* CONTROLLER/AdminGuard.php
   Clase para proteger rutas de administrador mediante verificación de sesión y rol.
*/

class AdminGuard
{
    public static function check()
    {
        // Iniciar sesión si no está iniciada 
        //session_status() devuelve el estado de la sesión actual y puede ser:
        //PHP_SESSION_DISABLED si las sesiones están deshabilitadas.
        //PHP_SESSION_NONE si las sesiones están habilitadas, pero no se ha iniciado ninguna.
        //PHP_SESSION_ACTIVE si las sesiones están habilitadas y una sesión está activa.
        //session_start() inicia una nueva sesión o reanuda la sesión actual.

        if (session_status() === PHP_SESSION_NONE) session_start();

        // Verificar si el usuario está autenticado y tiene rol de administrador 
        //Si no, redirigir o mostrar error

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        $rol = $_SESSION['usuario']['rol'] ?? 'cliente';
        if ($rol !== 'admin') {
            
            // Acceso denegado 
            //http_response_code() establece el código de respuesta HTTP para la página.
            //403 Forbidden indica que el servidor entiende la solicitud pero se niega a autorizarla.

            http_response_code(403);
            echo "Acceso denegado";
            exit;
        }
    }
}

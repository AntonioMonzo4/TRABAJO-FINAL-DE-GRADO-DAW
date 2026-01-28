<?php

/* CONTROLLER/AuthGuard.php
   Clase para proteger rutas mediante verificación de sesión.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    $_SESSION['flash'] = ['type'=>'error','msg'=>'Debes iniciar sesión para acceder'];
    header("Location: /login");
    exit;
}

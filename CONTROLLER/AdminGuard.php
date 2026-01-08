<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debe existir un usuario en sesión y su rol debe ser 'admin'
if (
    !isset($_SESSION['usuario']) ||
    !is_array($_SESSION['usuario']) ||
    ($_SESSION['usuario']['rol'] ?? '') !== 'admin'
) {
    header("Location: /login");
    exit;
}
?>
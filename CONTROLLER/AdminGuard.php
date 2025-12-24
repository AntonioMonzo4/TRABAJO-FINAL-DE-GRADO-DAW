<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION['user']) ||
    ($_SESSION['user']['rol'] ?? '') !== 'admin'
) {
    header("Location: /login");
    exit;
}

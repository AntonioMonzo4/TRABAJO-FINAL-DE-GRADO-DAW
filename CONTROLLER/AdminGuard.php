<?php
session_start();

if (
    !isset($_SESSION['usuario']) ||
    $_SESSION['usuario']['rol'] !== 'admin'
) {
    header("Location: /home");
    exit;
}

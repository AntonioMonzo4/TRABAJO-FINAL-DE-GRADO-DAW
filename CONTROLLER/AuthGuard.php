<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION['flash'] = ['type'=>'error','msg'=>'Debes iniciar sesión para acceder'];
    header("Location: /login");
    exit;
}

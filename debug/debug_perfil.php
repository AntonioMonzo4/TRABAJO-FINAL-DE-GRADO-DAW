<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forzamos la ruta del update de perfil
$_GET['ruta'] = 'perfil/actualizar';
$_SERVER['REQUEST_URI'] = '/perfil/actualizar';

require __DIR__ . '/index.php';

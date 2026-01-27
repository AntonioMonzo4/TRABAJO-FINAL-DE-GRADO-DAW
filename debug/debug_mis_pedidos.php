<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forzamos la ruta que quieres depurar
$_GET['ruta'] = 'mis-pedidos';

// Si tu router usa REQUEST_URI en vez de $_GET['ruta'], también ayuda esto:
$_SERVER['REQUEST_URI'] = '/mis-pedidos';

require __DIR__ . '/index.php';

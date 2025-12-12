<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de usuario logueado (si existe)
$usuario = $_SESSION['usuario'] ?? null;

// Opcional: contador de carrito (ajusta según tu lógica)
$carritoCount = $_SESSION['carrito_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- METADATOS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Los Círculos de Atenea - Librería web">
    <meta name="author" content="Antonio Monzó">
    <meta name="keywords" content="libros, lectura, novela, ensayo, poesía, Atenea, librería, conocimiento">
    <meta name="robots" content="index,follow">

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/VIEW/img/logo_principal.png" type="image/svg+xml">

    <!-- CSS PRINCIPAL (ruta según tu estructura) -->
    <link rel="stylesheet" href="/VIEW/css/style.css">

    <!-- ICONOS FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Los Círculos de Atenea</title>
</head>

<body>
<header class="header">
    <!-- HEADER SUPERIOR -->
    <div class="highHeader">
        <!-- LOGO -->
        <div class="logoHeader">
            <a href="/home">
                <img src="/VIEW/img/logo_principal.png" alt="Los Círculos de Atenea">
            </a>
        </div>

        <!-- NAVEGACIÓN PRINCIPAL -->
        <nav>
            <ul class="navbar-enlaces">
                <li><a href="/home">Inicio</a></li>
                <li><a href="/about">Sobre nosotros</a></li>
                <li><a href="/books">Libros</a></li>
                <li><a href="/tienda">Tienda</a></li>
                <!-- Ejemplo de dropdown si lo necesitas
                <li class="dropdown">
                    <a href="#">Categorías <i class="fa-solid fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="/categoria/novela">Novela</a></li>
                        <li><a href="/categoria/poesia">Poesía</a></li>
                    </ul>
                </li>
                -->
            </ul>
        </nav>

        <!-- ACCIONES DE USUARIO (login / registro / menú usuario) -->
        <div class="user-actions">
            <?php if ($usuario): ?>
                <!-- Usuario logueado: menú desplegable -->
                <div class="user-dropdown">
                    <button type="button" class="user-welcome">
                        <i class="fa-regular fa-user"></i>
                        <span>Hola, <?= htmlspecialchars($usuario['nombre']) ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <ul class="user-menu">
                        <li>
                            <a href="/perfil">
                                <i class="fa-regular fa-id-card"></i>
                                Mi perfil
                            </a>
                        </li>
                        <li>
                            <a href="/pedidos">
                                <i class="fa-solid fa-box"></i>
                                Mis pedidos
                            </a>
                        </li>
                        <li>
                            <a href="/logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Usuario no logueado: botones de login / registro -->
                <a href="/login" class="login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
                </a>
                <a href="/register" class="register-btn">
                    <i class="fa-regular fa-id-card"></i> Registrarse
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- HEADER INFERIOR -->
    <div class="lowHeader">
        <!-- BUSCADOR -->
        <div class="buscador">
            <form action="/buscar" method="get">
                <input type="text" name="q" placeholder="Busca libros, autores, géneros..." />
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
            </form>
        </div>

        <!-- CARRITO -->
        <div class="carrito">
            <a href="/carrito" title="Ver carrito">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <?php if ($carritoCount > 0): ?>
                <span class="carrito-count"><?= (int)$carritoCount ?></span>
            <?php endif; ?>
        </div>
    </div>
</header>

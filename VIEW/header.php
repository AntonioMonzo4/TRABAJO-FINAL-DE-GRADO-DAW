<!DOCTYPE html>
<html lang="es">


<!-- METADATOS -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Loas círculos de Atenea - Libreria web">
    <meta name="author" content="Antonio Monzó">
    <meta name="keywords" content="libro, libros, autor, libreria, lectura, ebook, marcapáginas, bolsas, portalibros, saga, cuentos, Atenea, conocimiento, comics, biografías, poesia"> 
    <meta name="robots" content="index,follow">
    <link rel="shortcut icon" href="/VIEW/img/logo_principal.png" type="image/svg+xml">

    <!-- Añadido CDN de FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/VIEW/css/style.css">
    <title>Los círculos de Atenea</title>

</head>

<body>
    <!-- INICIO DE SESIÓN EN LA WEB -->
    <?php 

    //Inicio de sesión si no esta iniciado aún
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //Cerrar sesión si se recibe la petición
    if(isset($_GET["logout"]) && $_GET["logout"]== "0"){
        session_destroy();// Destruir la sesión
        header("Location: index.php");// Redirigir a la página de inicio
        exit();// Asegurarse de que no se ejecute más código después de la redirección
    }

    //TODO: Añadir verificación de sesión y mostrar opciones de usuario si está logueado

    ?>
    <!-- HEADER DE LA PÁGINA WEB--> 
    <header class="header">
        <div class="highHeader">
            <div class="logoHeader">    
                <a href="index.php"><img src="/VIEW/img/logo_principal.png" alt="Los círculos de Atenea"></a>
            </div>
            <!-- NAVBAR -->
            <nav class="navbar">
                <ul class="navbar-enlaces">
                    <!-- index.php?pagina=x  cargar la página correspondiente -->
                    <li><a href="index.php?pagina=inicio">Inicio</a></li>

                    <!-- Menú desplegable para la tienda -->
                     <li class="dropdown">
                        <a href="index.php?pagina=categoriaTienda">Tienda <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?pagina=categoriaTienda&tipo=libros">Libros</a></li>
                            <li><a href="index.php?pagina=categoriaTienda&tipo=otros">Otros Productos</a></li>
                            <li><a href="index.php?pagina=categoriaTienda">Todos los Productos</a></li>
                        </ul>
                    </li>
                    <li><a href="index.php?pagina=sobreNosotros">Sobre Nosotros</a></li>
                    <li><a href="index.php?pagina=contacto">Contacto</a></li>
                </ul>

            </nav>

            <div class="user-actions">
<!--Opciones del usuario -->
                <?php if (isset($_SESSION['user_id'])): //Si la sesión está iniciada ?>


                    <div class="user-dropdown"><!--MENU DE USUARIO -->
                        <a href="#" class="user-welcome">
                            <i class="fas fa-user"></i>
                            Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="user-menu"><!--Menú desplegable de usuario y si el usuario es admin muestra opciones adicionales -->
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <li><a href="index.php?pagina=administrar_productos"><i class="fas fa-cog"></i> Panel Admin</a></li>
                            <?php endif; ?>
                            <li><a href="index.php?pagina=mis_pedidos"><i class="fas fa-box"></i> Mis Pedidos</a></li>
                            <li><a href="index.php?pagina=modificar_datos"><i class="fas fa-user-edit"></i> Mi Cuenta</a></li>
                            <li><a href="index.php?logout=0"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div><!--FIN MENU DE USUARIO -->
                <?php else: ?>
                    <!-- Opciones para usuarios no autenticados -->
                    <a href="index.php?pagina=form_logado" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </a>
                    <a href="index.php?pagina=form_registro" class="register-btn">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </a>
                <?php endif; ?>
            </div>
        </div>

        </div>

        <!-- TODO: Buscar íconos adecuados para los botones -->
        <div class="lowHeader">
            <div class="buscador">
                <form action="" method="GET">
                    <input type="hidden" name="pagina" value="busqueda">
                    <input type="text" placeholder="Escribe tu libro o producto..." name="buscador" required>
                    <button type="submit"><i></i></button> 
                </form>
            </div>

            <div class="carrito">
                <a href="" title="Ver Carrito de la Compra">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="carrito-count">0</span>
                </a>
            </div>
        </div>





    </header>
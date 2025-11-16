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
    <link rel="shortcut icon" href="./VIEW/img/logo_principal.png" type="image/svg+xml">
    <link rel="stylesheet" href="./VIEW/css/stylesheet.css">
    <title>Los círculos de Atenea</title>
</head>
<body>
    <!-- CABECERA -->
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
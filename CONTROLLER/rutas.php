<?php
require_once __DIR__ . '/authController.php';


$ruta = $_GET['ruta'] ?? 'home';
$metodo = $_SERVER['REQUEST_METHOD'];

// Función para cargar vistas de forma segura y evitar repetición de código 
function cargarVista($archivo)
{
    $rutaCompleta = __DIR__ . '/../VIEW/' . $archivo;
    if (file_exists($rutaCompleta)) {
        require_once $rutaCompleta;
    } else {
        require_once __DIR__ . '/../VIEW/Error404.php';
    }
}

// RUTEADOR PRINCIPAL
switch ($ruta) {

    /* ===============================
       RUTA HOME
       =============================== */
    case "home":
        if ($metodo === 'GET') {
            cargarVista('home.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       SOBRE NOSOTROS
       =============================== */
    case "about":
        if ($metodo === 'GET') {
            cargarVista('SobreNosotros.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       LOGIN
       =============================== */
    case "login":
        if ($metodo === 'GET') {
            cargarVista('FormLogin.php');
        } elseif ($metodo === 'POST') {
            AuthController::login();
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       REGISTRO
       =============================== */
    case "register":
        if ($metodo === 'GET') {
            cargarVista('FormRegistro.php');
        } elseif ($metodo === 'POST') {
            AuthController::register();
        } else {
            echo "Método no permitido.";
        }
        break;


    /* ===============================
       LOGOUT
        =============================== */
    case "logout":
        AuthController::logout();
        break;

    /* ===============================
       AVISO LEGAL
       =============================== */
    case "aviso-legal":
        if ($metodo === 'GET') {
            cargarVista('AvisoLegal.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       POLÍTICA DE COOKIES
       =============================== */
    case "cookies":
        if ($metodo === 'GET') {
            cargarVista('Cookies.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       POLÍTICA DE PRIVACIDAD
       =============================== */
    case "privacidad":
        if ($metodo === 'GET') {
            cargarVista('PoliticaPrivacidad.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       CARRITO
       =============================== */
    case "carrito":
        if ($metodo === 'GET') {
            cargarVista('Carrito.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    /* ===============================
       PERFIL DEL USUARIO
       =============================== */
    case "perfil":
        if ($metodo === 'GET') {
            cargarVista('Perfil.php');
        } else {
            echo "Método no permitido.";
        }
        break;

    case "tienda":
        if ($metodo === 'GET') cargarVista('Tienda.php');
        break;

    case "books":
        if ($metodo === 'GET') cargarVista('Libros.php');
        break;

    case "otros":
        if ($metodo === 'GET') cargarVista('OtrosProductos.php');
        break;

  

    /* ===============================
       SI LA RUTA NO EXISTE → 404
       =============================== */
    default:
        if ($metodo === 'GET') {
            cargarVista('Error404.php');
        } else {
            echo "Método no permitido.";
        }
        break;
}

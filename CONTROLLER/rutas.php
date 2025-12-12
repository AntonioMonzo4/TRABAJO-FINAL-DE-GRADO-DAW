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


    case (preg_match('/^book\/(\d+)$/', $ruta, $matches) ? true : false):
        if ($metodo === 'GET') {
            $_GET['id'] = (int)$matches[1];
            cargarVista('LibroDetalle.php');
        }
        break;


    case "carrito/add":
        if ($metodo === 'POST') {
            require_once __DIR__ . '/../CONTROLLER/CarritoController.php';
            CarritoController::add();
        }
        break;

    case "carrito/update":
        if ($metodo === 'POST') {
            require_once __DIR__ . '/../CONTROLLER/CarritoController.php';
            CarritoController::update();
        }
        break;

    case "carrito/remove":
        if ($metodo === 'POST') {
            require_once __DIR__ . '/../CONTROLLER/CarritoController.php';
            CarritoController::remove();
        }
        break;

    case "checkout":
        if ($metodo === 'GET') cargarVista('Checkout.php');
        elseif ($metodo === 'POST') {
            require_once __DIR__ . '/../CONTROLLER/PedidoController.php';
            PedidoController::crear();
        }
        break;

    case "pedido/ok":
        if ($metodo === 'GET') cargarVista('PedidoOk.php');
        break;


    case "admin":
        if ($metodo === 'GET') cargarVista('admin/Dashboard.php');
        break;

    case "admin/pedidos":
        if ($metodo === 'GET') cargarVista('admin/Pedidos.php');
        break;

    case "admin/pedido":
        if ($metodo === 'GET') cargarVista('admin/PedidoDetalle.php');
        break;

    case "admin/usuarios":
        if ($metodo === 'GET') cargarVista('admin/Usuarios.php');
        break;


    case "admin/stock":
        if ($metodo === 'GET') cargarVista('admin/Stock.php');
        break;

    case "admin/stock/edit":
        if ($metodo === 'POST') {
            require_once __DIR__ . '/../CONTROLLER/StockController.php';
            StockController::actualizar();
        }
        break;

    case "mis-pedidos":
        if ($metodo === 'GET') cargarVista('MisPedidos.php');
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

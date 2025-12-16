<?php
// =====================================================
// Router estable y compatible (sin errores 500)
// =====================================================

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* -----------------------------------------------------
   Obtener ruta (URL limpia o ?pagina=)
----------------------------------------------------- */
$ruta = $_GET['pagina'] ?? '';

if ($ruta === '') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = trim($path, '/');

    if ($path === '' || $path === 'index.php') {
        $ruta = 'home';
    } else {
        $ruta = $path;
    }
}

/* -----------------------------------------------------
   Cargar vista de forma segura
----------------------------------------------------- */
function cargarVista($vista)
{
    $archivo = $_SERVER['DOCUMENT_ROOT'] . '/VIEW/' . $vista;

    if (!file_exists($archivo)) {
        http_response_code(404);
        echo "<h1>Error 404</h1>";
        echo "<p>Vista no encontrada: " . htmlspecialchars($archivo) . "</p>";
        exit;
    }

    require $archivo;
}

/* -----------------------------------------------------
   ROUTER
----------------------------------------------------- */
switch ($ruta) {

    /* ===== PÁGINAS PÚBLICAS ===== */

    case 'home':
        cargarVista('home.php');
        break;

    case 'sobre-nosotros':
    case 'sobreNosotros':
        cargarVista('SobreNosotros.php');
        break;

    case 'tienda':
        cargarVista('Tienda.php');
        break;

    case 'books':
        cargarVista('Libros.php');
        break;

    case 'otros':
        cargarVista('OtrosProductos.php');
        break;

    case 'carrito':
        cargarVista('Carrito.php');
        break;

    /* ===== LOGIN / REGISTRO ===== */

    case 'login':
        if ($method === 'GET') {
            cargarVista('FormLogin.php');
        } else {
            echo "POST /login pendiente (no rompe el proyecto)";
        }
        break;

    case 'register':
        if ($method === 'GET') {
            cargarVista('FormRegistro.php');
        } else {
            echo "POST /register pendiente (no rompe el proyecto)";
        }
        break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: /home");
        exit;

        /* ===== CHECKOUT / PEDIDOS ===== */

    case 'checkout':
        if ($method === 'GET') {
            cargarVista('Checkout.php');
        } else {
            echo "POST /checkout pendiente";
        }
        break;

    case 'mis-pedidos':
        cargarVista('MisPedidos.php');
        break;

    /* ===== LEGALES ===== */

    case 'aviso-legal':
        cargarVista('AvisoLegal.php');
        break;

    case 'privacidad':
        cargarVista('PoliticaPrivacidad.php');
        break;

    case 'cookies':
        cargarVista('Cookies.php');
        break;

    /* ===== DETALLE DE LIBRO ===== */
    default:

        // /book/12
        if (preg_match('#^book/([0-9]+)$#', $ruta, $m)) {
            $_GET['id'] = (int)$m[1];
            cargarVista('LibroDetalle.php');
            break;
        }

        /* ===== ADMIN (VISTAS) ===== */

        if ($ruta === 'admin') {
            cargarVista('admin/Dashboard.php');
            break;
        }

        if ($ruta === 'admin/pedidos') {
            cargarVista('admin/Pedidos.php');
            break;
        }

        if ($ruta === 'admin/usuarios') {
            cargarVista('admin/Usuarios.php');
            break;
        }

        if ($ruta === 'admin/stock') {
            cargarVista('admin/Stock.php');
            break;
        }

        /* ===== 404 ===== */
        http_response_code(404);
        echo "<h1>Error 404</h1>";
        echo "<p>Ruta no encontrada: " . htmlspecialchars($ruta) . "</p>";
        break;
}

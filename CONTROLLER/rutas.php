<?php
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';


$ruta = $_GET['pagina'] ?? '';
if ($ruta === '') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $ruta = trim((string)$path, '/');
    if ($ruta === '' || $ruta === 'index.php') $ruta = 'home';
}

/* Normaliza (por si llega con espacios o barras) */
$ruta = trim($ruta);
$ruta = ltrim($ruta, '/');


function cargarVista(string $vista): void
{
    $docRoot = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), '/');

    $candidatos = [
        $docRoot . '/VIEW/' . $vista,
        $docRoot . '/view/' . $vista,
        __DIR__ . '/../VIEW/' . $vista,
        __DIR__ . '/../view/' . $vista,

        // Variante en minúsculas (Linux)
        $docRoot . '/VIEW/' . strtolower($vista),
        $docRoot . '/view/' . strtolower($vista),
        __DIR__ . '/../VIEW/' . strtolower($vista),
        __DIR__ . '/../view/' . strtolower($vista),
    ];

    foreach ($candidatos as $archivo) {
        if ($archivo && file_exists($archivo)) {
            require $archivo;
            return;
        }
    }

    http_response_code(404);
    echo "<h1>Error 404</h1>";
    echo "<p>Vista no encontrada: " . htmlspecialchars($vista) . "</p>";
    exit;
}

/* ---------------------------
   Acciones de carrito sin controlador (para que NO falle carrito/add)
---------------------------- */
function carritoInit(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) $_SESSION['carrito'] = [];
}

function carritoAdd(): void
{
    carritoInit();

    // Espera POST: id, titulo, precio, cantidad (opcional)
    $id = (int)($_POST['id'] ?? 0);
    $titulo = (string)($_POST['titulo'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 1);
    if ($id <= 0 || $titulo === '' || $precio <= 0) {
        http_response_code(400);
        echo "Datos de carrito inválidos";
        exit;
    }

    if (!isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = [
            'id' => $id,
            'titulo' => $titulo,
            'precio' => $precio,
            'cantidad' => max(1, $cantidad),
        ];
    } else {
        $_SESSION['carrito'][$id]['cantidad'] += max(1, $cantidad);
    }

    header("Location: /carrito");
    exit;
}

function carritoRemove(): void
{
    carritoInit();
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) unset($_SESSION['carrito'][$id]);
    header("Location: /carrito");
    exit;
}

function carritoUpdate(): void
{
    carritoInit();
    $id = (int)($_POST['id'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 1);
    if ($id > 0 && isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] = max(1, $cantidad);
    }
    header("Location: /carrito");
    exit;
}

function carritoVaciar(): void
{
    carritoInit();
    $_SESSION['carrito'] = [];
    header("Location: /carrito");
    exit;
}

/* ---------------------------
   Router
---------------------------- */
switch ($ruta) {

    // Home
    case 'home':
        cargarVista('home.php');
        break;



    case 'register':
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            cargarVista('FormularioRegistro.php');
        } else {
            require_once __DIR__ . '/AuthController.php';
            AuthController::register();
        }
        break;

    // Sobre nosotros (tu header usa /about)
    case 'about':
    case 'sobre-nosotros':
    case 'sobreNosotros':
        cargarVista('SobreNosotros.php');
        break;

    // Tienda / Libros / Otros
    case 'tienda':
        cargarVista('Tienda.php');
        break;

    case 'books':
        cargarVista('Libros.php');
        break;

    case 'otros':
        cargarVista('OtrosProductos.php');
        break;

    case 'otros-productos':
    case 'otros_productos':
    case 'otrosproductos':
        cargarVista('OtrosProductos.php');
        break;


    // Carrito
    case 'carrito':
        cargarVista('Carrito.php');
        break;

    case 'carrito/add':
        if ($method === 'POST') carritoAdd();
        http_response_code(405);
        echo "Método no permitido";
        exit;

    case 'carrito/remove':
        if ($method === 'POST') carritoRemove();
        http_response_code(405);
        echo "Método no permitido";
        exit;

    case 'carrito/update':
        if ($method === 'POST') carritoUpdate();
        http_response_code(405);
        echo "Método no permitido";
        exit;

    case 'vaciar_carrito':
        if ($method === 'POST') carritoVaciar();
        http_response_code(405);
        echo "Método no permitido";
        exit;

        // Legales (tu footer enlaza con .php y con guiones/bajos)
    case 'privacidad':
    case 'privacidad.php':
        cargarVista('PoliticaPrivacidad.php');
        break;



    case 'aviso-legal':
    case 'aviso_legal.php':
        cargarVista('AvisoLegal.php');
        break;

    case 'cookies':
    case 'cookies.php':
        cargarVista('Cookies.php');
        break;

    // Si tienes términos/condiciones con otro nombre de ruta
    case 'terminos':
    case 'terminos-y-condiciones':
    case 'condiciones':
        // ajusta aquí el nombre real de tu vista si la tienes
        cargarVista('TerminosCondiciones.php');
        break;

    case 'perfil':
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }
        cargarVista('Perfil.php');
        break;

    case 'mis-pedidos':
    case 'mis_pedidos':
        require_once __DIR__ . '/PedidoController.php';
        PedidoController::misPedidos();
        break;


    case 'login':
        if ($method === 'GET') {
            cargarVista('FormLogin.php');
        } else {
            require_once __DIR__ . '/AuthController.php';
            AuthController::login();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/AuthController.php';
        AuthController::logout();
        break;


    case 'pedido/crear':
        require_once __DIR__ . '/PedidoController.php';
        PedidoController::crear();
        break;

    case 'pedido/ok':
    case 'pedido-ok':
        cargarVista('PedidoOk.php');
        break;
    case 'contacto':
        cargarVista('Contacto.php');
        break;



    case 'perfil/actualizar':
        require_once __DIR__ . '/AuthController.php';
        AuthController::actualizarPerfil();
        break;

    // Admin panel
    case 'admin':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/StockController.php';
        StockController::panel();
        break;

    // Admin stock
    case 'admin/stock':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/StockController.php';
        StockController::stock();
        break;

    case 'admin/stock/guardar':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/StockController.php';
        StockController::stockGuardar();
        break;

    // Admin pedidos
    case 'admin/pedidos':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/StockController.php';
        StockController::pedidos();
        break;

    case 'admin/pedidos/estado':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/StockController.php';
        StockController::pedidoEstado();
        break;

    case 'admin/usuarios':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        cargarVista('admin/Usuarios.php');
        break;

    case 'admin/usuarios/guardar':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/UsuariosAdminController.php';
        UsuariosAdminController::guardar();
        break;

    case 'admin/usuarios/eliminar':
        require_once __DIR__ . '/AdminGuard.php';
        AdminGuard::check();
        require_once __DIR__ . '/UsuariosAdminController.php';
        UsuariosAdminController::eliminar();
        break;


    case 'admin/stock/book': // POST editar libro
        require_once __DIR__ . '/AdminGuard.php';
        if (($method ?? 'GET') !== 'POST') {
            header("Location: /admin/stock");
            exit;
        }

        require_once __DIR__ . '/../MODEL/conexion.php';
        $pdo = conexion::conexionBBDD();

        $id = (int)($_POST['id'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE books SET precio = :precio, stock = :stock WHERE book_id = :id");
            $stmt->execute([':precio' => $precio, ':stock' => $stock, ':id' => $id]);
        }
        header("Location: /admin/stock");
        exit;

    case 'admin/stock/other': // POST editar otro producto
        require_once __DIR__ . '/AdminGuard.php';
        if (($method ?? 'GET') !== 'POST') {
            header("Location: /admin/stock");
            exit;
        }

        require_once __DIR__ . '/../MODEL/conexion.php';
        $pdo = conexion::conexionBBDD();

        $id = (int)($_POST['id'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);

        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE other_products SET precio = :precio, stock = :stock WHERE product_id = :id");
            $stmt->execute([':precio' => $precio, ':stock' => $stock, ':id' => $id]);
        }
        header("Location: /admin/stock");
        exit;


    case 'checkout':
        cargarVista('checkout.php'); // tu archivo es checkout.php
        break;


    default:
        // Detalle libro: /book/12
        if (preg_match('#^book/([0-9]+)$#', $ruta, $m)) {
            $_GET['id'] = (int)$m[1];
            cargarVista('LibroDetalle.php');
            break;
        }

        http_response_code(404);
        echo "<h1>Error 404</h1>";
        echo "<p>Ruta no encontrada: " . htmlspecialchars($ruta) . "</p>";
        break;
}

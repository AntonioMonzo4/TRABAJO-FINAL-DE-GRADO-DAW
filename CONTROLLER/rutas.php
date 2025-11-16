  <?php 
$ruta = $_SERVER['REQUEST_URI'];
$metodo = $_SERVER['REQUEST_METHOD'];
  
if (isset($_GET["pagina"])) {
    switch ($_GET["pagina"]) {
        /* Panel Admin */
        

        case "agregarProducto":
            if ($metodo === 'GET') {
                require_once 'VISTA/agregarProducto.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        /* Navegación básica */
        case "home":
            if ($metodo === 'GET') {
                require_once 'VISTA/Home.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "categoriaTienda":
            if ($metodo === 'GET') {
                require_once 'CONTROL/controladorCategoria.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "about":
            if ($metodo === 'GET') {
                require_once 'VISTA/SobreNosotros.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "contacto":
            if ($metodo === 'GET') {
                require_once 'VISTA/Contacto.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        /* Panel Usuario */
        case "mis_pedidos":
            if ($metodo === 'GET') {
                require_once 'CONTROL/pedidoUsuario.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "modificar_datos":
            if ($metodo === 'GET') {
                require_once 'CONTROL/editar_cliente.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        /* Panel Admin: otros controladores */
        case "administrar_productos":
            if ($metodo === 'GET') {
                require_once 'CONTROL/controladorProductos.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "administrar_clientes":
            if ($metodo === 'GET') {
                require_once 'CONTROL/controladorCliente.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "eliminar_producto":
            if ($metodo === 'GET') {
                require_once 'CONTROL/eliminar_producto.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "eliminar_cliente":
            if ($metodo === 'GET') {
                require_once 'CONTROL/eliminar_cliente.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "editar_producto":
            if ($metodo === 'GET') {
                require_once 'CONTROL/editar_producto_controlador.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "consultar_pedidos":
            if ($metodo === 'GET') {
                require_once 'controladorPedidos.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "confirmacion_eliminar":
            if ($metodo === 'GET') {
                require_once 'VISTA/confirmacion_eliminar.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        /* Busqueda y detalles Producto */
        case "busqueda":
            if ($metodo === 'GET') {
                require_once 'controlador_busqueda.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

       

        /* Formularios */
        case "form_logado":
            if ($metodo === 'GET') {
                require_once 'VISTA/FormLogin.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "form_registro":
            if ($metodo === 'GET') {
                require_once 'VISTA/FormularioRegistro.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        /* AvisoLegal, Cookies y Privacidad */
        case "AvisoLegal":
            if ($metodo === 'GET') {
                require_once 'VISTA/AvisoLegal.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "Cookies":
            if ($metodo === 'GET') {
                require_once 'VISTA/Cookies.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        case "PoliticaPrivacidad":
            if ($metodo === 'GET') {
                require_once 'VISTA/PoliticaPrivacidad.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;

        default:
            if ($metodo === 'GET') {
                require_once 'VISTA/Home.php';
            } else {
                echo "Método no permitido para esta ruta.";
            }
            break;
    }
} else {
  if ($metodo === 'GET') {
        require_once '../VIEW/home.php';
    } else {
        echo "Método no permitido.";
    }
}

?>
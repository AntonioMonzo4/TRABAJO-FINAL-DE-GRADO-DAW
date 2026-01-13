<?php
require_once __DIR__ . '/../CONTROLLER/AuthGuard.php';
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$pedidoConfirmado = false;

/**
 * Si llegamos desde "Proceder al pago", vendrá POST cart_json desde localStorage.
 * Lo convertimos a $_SESSION['carrito'] para reutilizar este checkout tal como estaba.
 */
if ($method === 'POST' && isset($_POST['cart_json'])) {
    $raw = (string)$_POST['cart_json'];
    $decoded = json_decode($raw, true);

    if (is_array($decoded)) {
        $_SESSION['carrito'] = [];
        foreach ($decoded as $item) {
            if (!is_array($item)) continue;

            $id = isset($item['id']) ? (string)$item['id'] : '';
            $tipo = isset($item['tipo']) ? (string)$item['tipo'] : '';
            $titulo = isset($item['nombre']) ? (string)$item['nombre'] : '';
            $precio = isset($item['precio']) ? (float)$item['precio'] : 0.0;
            $cantidad = isset($item['cantidad']) ? (int)$item['cantidad'] : 1;

            if ($id === '' || $tipo === '' || $titulo === '' || $precio < 0 || $cantidad <= 0) continue;

            $key = $tipo . '-' . $id;
            $_SESSION['carrito'][$key] = [
                'id' => $id,
                'tipo' => $tipo,
                'titulo' => $titulo,
                'precio' => $precio,
                'cantidad' => max(1, $cantidad),
            ];
        }
    }

    header("Location: /checkout");
    exit;
}

/**
 * Confirmación de pedido (tu form original hace POST a /checkout con metodo_pago)
 */
if ($method === 'POST' && isset($_POST['metodo_pago'])) {
    // Aquí iría guardar pedido en BBDD, enviar email, etc.
    $pedidoConfirmado = true;

    // Vacía el carrito de sesión (opcional, pero lógico)
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'] ?? [];

if (!$pedidoConfirmado && !$carrito) {
    header("Location: /carrito");
    exit;
}

$total = 0;
foreach ($carrito as $i) {
    $total += ((float)$i['precio']) * ((int)$i['cantidad']);
}

/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Carrito', 'url' => '/carrito'],
    ['label' => 'Checkout', 'url' => null]
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">

        <?php if ($pedidoConfirmado): ?>
            <h1>Pedido confirmado</h1>
            <p>Tu pedido se ha registrado correctamente.</p>
            <a class="btn btn-primary" href="/tienda">Volver a la tienda</a>
        <?php else: ?>

            <h1>Confirmar pedido</h1>

            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $i): ?>
                        <tr>
                            <td><?= htmlspecialchars($i['titulo']) ?></td>
                            <td><?= number_format((float)$i['precio'], 2) ?> €</td>
                            <td><?= (int)$i['cantidad'] ?></td>
                            <td><?= number_format(((float)$i['precio']) * ((int)$i['cantidad']), 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="carrito-total"><strong>Total: <?= number_format($total, 2) ?> €</strong></p>

            <form method="post" action="/pedido/crear">

                <label>Método de pago</label>
                <select name="metodo_pago" required>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="paypal">PayPal</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="contrareembolso">Contra reembolso</option>
                </select>

                <button class="btn btn-primary" type="submit">Confirmar pedido</button>
            </form>

        <?php endif; ?>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
<?php
require_once __DIR__ . '/../CONTROLLER/AuthGuard.php';

require_once __DIR__ . '/header.php';

$carrito = $_SESSION['carrito'] ?? [];
if (!$carrito) {
    header("Location: /carrito");
    exit;
}

$total = 0;
foreach ($carrito as $i) {
    $total += $i['precio'] * $i['cantidad'];
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
                        <td><?= number_format($i['precio'], 2) ?> €</td>
                        <td><?= (int)$i['cantidad'] ?></td>
                        <td><?= number_format($i['precio'] * $i['cantidad'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="carrito-total"><strong>Total: <?= number_format($total, 2) ?> €</strong></p>

        <form method="post" action="/checkout">
            <label>Método de pago</label>
            <select name="metodo_pago" required>
                <option value="tarjeta">Tarjeta</option>
                <option value="paypal">PayPal</option>
                <option value="transferencia">Transferencia</option>
                <option value="contrareembolso">Contra reembolso</option>
            </select>

            <button class="btn btn-primary">Confirmar pedido</button>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
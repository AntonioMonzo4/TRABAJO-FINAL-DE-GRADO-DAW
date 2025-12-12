<?php
require_once __DIR__ . '/header.php';

$carrito = $_SESSION['carrito'] ?? [];

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Carrito', 'url' => null]
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">

        <h1>Carrito</h1>

        <?php if (!$carrito): ?>
            <p>Tu carrito está vacío.</p>
            <a href="/tienda" class="btn btn-primary">Ir a la tienda</a>
        <?php else: ?>

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

                    <?php $total = 0;
                    foreach ($carrito as $i):
                        $sub = $i['precio'] * $i['cantidad'];
                        $total += $sub;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($i['titulo']) ?></td>
                            <td><?= number_format($i['precio'], 2) ?> €</td>
                            <td><?= (int)$i['cantidad'] ?></td>
                            <td><?= number_format($sub, 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

            <p class="carrito-total"><strong>Total: <?= number_format($total, 2) ?> €</strong></p>

            <a href="/checkout" class="btn btn-primary">Finalizar compra</a>

        <?php endif; ?>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
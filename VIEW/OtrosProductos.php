<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

// Cargar otros productos
$stmt = $pdo->query("SELECT * FROM other_products");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => '/tienda'],
    ['label' => 'Otros productos', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
<section class="container">

<h1>Otros productos</h1>

<?php if (!$productos): ?>
    <p>No hay productos disponibles.</p>
<?php else: ?>

<div class="grid-products">
<?php foreach ($productos as $p): ?>
    <article class="product">
        <img
            src="/VIEW/img/productos/<?= htmlspecialchars($p['imagen'] ?? 'default-product.png') ?>"
            alt="<?= htmlspecialchars($p['nombre']) ?>"
            onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80'">

        <h3><?= htmlspecialchars($p['nombre']) ?></h3>

        <?php if (!empty($p['descripcion'])): ?>
            <p class="muted"><?= htmlspecialchars($p['descripcion']) ?></p>
        <?php else: ?>
            <p class="muted">&nbsp;</p>
        <?php endif; ?>

        <p><strong><?= number_format((float)$p['precio'], 2) ?> €</strong></p>

        <?php if ((int)($p['stock'] ?? 0) > 0): ?>
            <button
                type="button"
                class="btn btn-secondary add-other-to-cart"
                data-product-id="<?= (int)$p['product_id'] ?>"
                data-product-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                data-product-precio="<?= (float)$p['precio'] ?>"
                data-product-imagen="<?= htmlspecialchars($p['imagen'] ?? '') ?>"
            >
                Añadir al carrito
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-secondary" disabled>Sin stock</button>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
</div>

<?php endif; ?>

</section>
</main>

<script src="/VIEW/js/carrito.js"></script>

<?php require_once __DIR__ . '/footer.php'; ?>

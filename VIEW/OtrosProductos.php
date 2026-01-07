<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$stmt = $pdo->query("SELECT * FROM other_products ORDER BY nombre ASC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => '/tienda'],
    ['label' => 'Otros productos', 'url' => null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">
        <h1>Otros productos</h1>

        <?php if (!$productos): ?>
            <div class="empty">
                <h3>No hay productos para mostrar</h3>
                <a class="btn btn-primary" href="/tienda">Volver a tienda</a>
            </div>
        <?php else: ?>
            <div class="grid-products">
                <?php foreach ($productos as $p): ?>
                    <?php $pid = (int)($p['product_id'] ?? $p['id'] ?? $p['other_product_id'] ?? 0); ?>
                    <article class="product">
                        <img
                            src="/VIEW/img/productos/<?= htmlspecialchars($p['imagen'] ?? 'default-product.png') ?>"
                            alt="<?= htmlspecialchars($p['nombre']) ?>"
                            onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80'">
                        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                        <p class="muted"><?= htmlspecialchars($p['descripcion'] ?? '') ?></p>
                        <div class="row">
                            <span class="price"><?= number_format((float)$p['precio'], 2) ?> €</span>
                            <span class="stock <?= ((int)($p['stock'] ?? 0) > 0) ? 'ok' : 'bad' ?>">
                                <?= ((int)($p['stock'] ?? 0) > 0) ? 'En stock' : 'Sin stock' ?>
                            </span>
                        </div>

                        <button
                            class="btn btn-secondary add-other-to-cart"
                            type="button"
                            data-product-id="<?= $pid ?>"
                            data-product-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                            data-product-precio="<?= (float)$p['precio'] ?>"
                            data-product-imagen="<?= htmlspecialchars($p['imagen'] ?? '') ?>"
                        >
                            Añadir al carrito
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

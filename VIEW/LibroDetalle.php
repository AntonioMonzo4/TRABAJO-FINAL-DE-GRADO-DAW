<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = :id");
$stmt->execute([':id' => $id]);
$libro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$libro) {
    echo "<main class='page'><div class='container'><h2>Libro no encontrado</h2></div></main>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => '/tienda'],
    ['label' => 'Libros', 'url' => '/books'],
    ['label' => $libro['titulo'], 'url' => null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container libro-detalle">

        <div class="libro-detalle-grid">

            <div class="libro-imagen">
                <img
                    src="/VIEW/img/libros/<?= htmlspecialchars($libro['imagen'] ?? 'default-book.png') ?>"
                    alt="<?= htmlspecialchars($libro['titulo']) ?>"
                    onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=500&q=80'">
            </div>

            <div class="libro-info">
                <h1><?= htmlspecialchars($libro['titulo']) ?></h1>
                <p class="autor"><?= htmlspecialchars($libro['autor']) ?></p>

                <p class="precio">
                    <?= number_format((float)$libro['precio'], 2) ?> €
                </p>

                <p class="descripcion">
                    <?= nl2br(htmlspecialchars($libro['descripcion'] ?? 'Descripción no disponible.')) ?>
                </p>

                <p class="stock <?= ((int)$libro['stock'] > 0) ? 'ok' : 'bad' ?>">
                    <?= ((int)$libro['stock'] > 0) ? 'En stock' : 'Sin stock' ?>
                </p>

                <?php if ((int)$libro['stock'] > 0): ?>
                    <form method="post" action="/carrito/add">
                        <input type="hidden" name="product_type" value="book">
                        <input type="hidden" name="product_id" value="<?= $libro['book_id'] ?>">
                        <input type="hidden" name="titulo" value="<?= htmlspecialchars($libro['titulo']) ?>">
                        <input type="hidden" name="precio" value="<?= $libro['precio'] ?>">
                        <input type="hidden" name="imagen" value="<?= $libro['imagen'] ?>">
                        <input type="number" name="cantidad" value="1" min="1">
                        <button class="btn btn-primary">Añadir al carrito</button>
                    </form>

                <?php endif; ?>
            </div>

        </div>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
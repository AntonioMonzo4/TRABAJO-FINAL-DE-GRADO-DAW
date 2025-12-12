<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

$genero = $_GET['genero'] ?? null;

if ($genero) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE genero_literario = :g");
    $stmt->execute([':g' => $genero]);
} else {
    $stmt = $pdo->query("SELECT * FROM books");
}

$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => '/tienda'],
    ['label' => 'Libros', 'url' => null],
];

require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
<section class="container">

<h1>Libros</h1>

<?php if (!$libros): ?>
    <p>No hay libros disponibles.</p>
<?php else: ?>

<div class="grid-products">
<?php foreach ($libros as $l): ?>
    <article class="product">
        <img
            src="/VIEW/img/libros/<?= htmlspecialchars($l['imagen'] ?? 'default-book.png') ?>"
            alt="<?= htmlspecialchars($l['titulo']) ?>"
            onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=500&q=80'">

        <h3><?= htmlspecialchars($l['titulo']) ?></h3>
        <p><?= htmlspecialchars($l['autor']) ?></p>
        <p><strong><?= number_format($l['precio'], 2) ?> €</strong></p>

        <a class="btn btn-secondary" href="/book/<?= (int)$l['book_id'] ?>">
            Ver detalle
        </a>
    </article>
<?php endforeach; ?>
</div>

<?php endif; ?>

</section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

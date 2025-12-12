<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$genero = trim($_GET['genero'] ?? '');

$sql = "SELECT * FROM books";
$params = [];

if ($genero !== '') {
  $sql .= " WHERE genero_literario = :genero";
  $params[':genero'] = $genero;
}
$sql .= " ORDER BY titulo ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = [
  ['label'=>'Inicio', 'url'=>'/home'],
  ['label'=>'Tienda', 'url'=>'/tienda'],
  ['label'=>'Libros', 'url'=>null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">
    <h1>Libros</h1>

    <?php if ($genero !== ''): ?>
      <p class="muted">Filtrando por género: <strong><?= htmlspecialchars($genero) ?></strong></p>
    <?php endif; ?>

    <?php if (!$libros): ?>
      <div class="empty">
        <h3>No hay libros para mostrar</h3>
        <p>Prueba otro género o vuelve a la tienda.</p>
        <a class="btn btn-primary" href="/tienda">Volver a tienda</a>
      </div>
    <?php else: ?>
      <div class="grid-products">
        <?php foreach ($libros as $l): ?>
          <article class="product">
            <img
              src="/VIEW/img/libros/<?= htmlspecialchars($l['imagen'] ?? 'default-book.png') ?>"
              alt="<?= htmlspecialchars($l['titulo']) ?>"
              onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=500&q=80'">
            <h3><?= htmlspecialchars($l['titulo']) ?></h3>
            <p class="muted"><?= htmlspecialchars($l['autor'] ?? '') ?></p>
            <div class="row">
              <span class="price"><?= number_format((float)$l['precio'], 2) ?> €</span>
              <span class="stock <?= ((int)($l['stock'] ?? 0) > 0) ? 'ok' : 'bad' ?>">
                <?= ((int)($l['stock'] ?? 0) > 0) ? 'En stock' : 'Sin stock' ?>
              </span>
            </div>
            <a class="btn btn-secondary" href="/book/<?= (int)$l['book_id'] ?>">Ver detalle</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

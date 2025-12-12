<?php require_once __DIR__ . '/header.php'; ?>

<?php
$items = [
  ['label'=>'Inicio', 'url'=>'/home'],
  ['label'=>'Tienda', 'url'=>null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">
    <h1>Tienda</h1>
    <p>Elige qué quieres explorar:</p>

    <div class="cards-2">
      <a class="card-link" href="/books">
        <div class="card">
          <h2>Libros</h2>
          <p>Explora nuestro catálogo de libros por título, autor o género.</p>
        </div>
      </a>

      <a class="card-link" href="/otros">
        <div class="card">
          <h2>Otros productos</h2>
          <p>Accesorios, artículos y productos complementarios.</p>
        </div>
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

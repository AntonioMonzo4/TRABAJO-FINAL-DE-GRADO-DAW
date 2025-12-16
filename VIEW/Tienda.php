<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "TIENDA: OK 1<br>";

require_once __DIR__ . '/header.php';
echo "TIENDA: OK 2 (header)<br>";

$items = [
    ['label'=>'Inicio','url'=>'/home'],
    ['label'=>'Tienda','url'=>null]
];
require_once __DIR__ . '/partials/breadcrumb.php';
echo "TIENDA: OK 3 (breadcrumb)<br>";
?>

<main class="page">
<section class="container">

<h1>Tienda</h1>

<div class="cards-2">

    <a href="/books" class="card-link">
        <div class="card">
            <h2>Libros</h2>
            <p>Explora nuestro catálogo de libros.</p>
        </div>
    </a>

    <a href="/otros" class="card-link">
        <div class="card">
            <h2>Otros productos</h2>
            <p>Productos complementarios relacionados con la lectura.</p>
        </div>
    </a>

</div>

</section>
</main>
echo "TIENDA: OK 4 (antes footer)<br>";
<?php require_once __DIR__ . '/footer.php'; ?>

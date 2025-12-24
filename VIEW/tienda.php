<?php
require_once __DIR__ . '/header.php';

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => null]
];
require_once __DIR__ . '/partials/breadcrumb.php';
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
        <br>
    <img src="/VIEW/img/fondo.png" alt=".imagen de atenea" class="imagen_fondo">

    </section>
    

    
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
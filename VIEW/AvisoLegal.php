<?php
require_once __DIR__ . '/header.php';

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Aviso legal', 'url' => null]
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">
        <h1>Aviso legal</h1>

        <p>Este sitio web es un proyecto académico desarrollado como Trabajo Fin de Ciclo
            del Grado Superior en Desarrollo de Aplicaciones Web.</p>

        <p>El contenido mostrado no tiene fines comerciales reales.</p>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
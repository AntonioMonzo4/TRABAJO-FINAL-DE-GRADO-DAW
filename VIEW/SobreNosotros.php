<?php require_once __DIR__ . '/header.php'; ?>

<?php
$items = [
  ['label'=>'Inicio', 'url'=>'/home'],
  ['label'=>'Sobre nosotros', 'url'=>null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">
    <h1>Sobre nosotros</h1>
    <p>
      Los Círculos de Atenea es una librería online orientada a la lectura, la cultura y el conocimiento.
      Seleccionamos títulos y productos para lectores curiosos, desde clásicos hasta novedades.
    </p>

    <div class="cards-3">
      <article class="card">
        <h3>Nuestra misión</h3>
        <p>Acercar libros y cultura con un catálogo cuidado y una experiencia sencilla.</p>
      </article>
      <article class="card">
        <h3>Nuestros valores</h3>
        <p>Calidad, servicio, transparencia y amor por la lectura (sí, esto también cuenta).</p>
      </article>
      <article class="card">
        <h3>Contacto</h3>
        <p>Escríbenos desde la sección de contacto o mediante el formulario de la web.</p>
      </article>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

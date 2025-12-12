<?php
// IMPORTANTE: no pongas nada antes de este require
require_once __DIR__ . '/header.php';

// Migas de pan (definimos los items ANTES de incluir el partial)
$items = [
  ['label' => 'Inicio', 'url' => '/home'],
  ['label' => 'Sobre nosotros', 'url' => null],
];

// Incluir breadcrumb usando ruta ABSOLUTA correcta
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">
    <h1>Sobre nosotros</h1>

    <p>
      Los Círculos de Atenea es una librería online dedicada a la difusión de la
      lectura, la cultura y el conocimiento. Nuestro objetivo es ofrecer una
      experiencia de compra sencilla, accesible y cercana para todo tipo de lectores.
    </p>

    <div class="cards-3">
      <div class="card">
        <h3>Nuestra misión</h3>
        <p>
          Facilitar el acceso a libros y productos culturales de calidad,
          fomentando el aprendizaje y el pensamiento crítico.
        </p>
      </div>

      <div class="card">
        <h3>Nuestros valores</h3>
        <p>
          Pasión por la lectura, compromiso con el cliente y mejora continua
          de nuestros servicios.
        </p>
      </div>

      <div class="card">
        <h3>El proyecto</h3>
        <p>
          Esta plataforma ha sido desarrollada como proyecto final del ciclo
          formativo de Desarrollo de Aplicaciones Web, aplicando arquitectura MVC
          y buenas prácticas de programación.
        </p>
      </div>
    </div>
  </section>
</main>

<?php
require_once __DIR__ . '/footer.php';
?>
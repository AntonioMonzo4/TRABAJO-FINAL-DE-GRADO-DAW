<?php
require_once __DIR__ . '/header.php';

$items = [
  ['label' => 'Inicio', 'url' => '/home'],
  ['label' => 'Sobre nosotros', 'url' => null],
];

require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <!-- HERO / CABECERA -->
  <section class="about-hero">
    <div class="container about-hero-inner">
      <div class="about-hero-text">
        <h1>Sobre nosotros</h1>

        <p class="about-lead">
          En Los Círculos de Atenea creemos que la lectura es una forma de encuentro,
          reflexión y crecimiento personal. Nuestra librería nace con la vocación
          de acompañar a cada lector en el descubrimiento de nuevas historias,
          ideas y autores.
        </p>

        <div class="about-cta">
          <a href="/tienda" class="btn btn-primary">Explorar libros</a>
          <a href="/books" class="btn btn-secondary">Ver catálogo</a>
        </div>

        <div class="about-stats">
          <div class="stat">
            <span class="stat-number">Numerosos</span>
            <span class="stat-label">libros seleccionados</span>
          </div>
          <div class="stat">
            <span class="stat-number">Todos</span>
            <span class="stat-label">los géneros</span>
          </div>
          <div class="stat">
            <span class="stat-number">Para</span>
            <span class="stat-label">cada lector</span>
          </div>
        </div>
      </div>

      <div class="about-hero-media" aria-hidden="true">
        <img src="/VIEW/img/fondo.png" alt="imagen de atenea" class="imagen_fondo">
        <div class="about-hero-badge">
          
          <span class="badge-title">Atenea</span>
          <span class="badge-sub">lectura • cultura • conocimiento</span>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTENIDO -->
  <section class="container about-content">
    <div class="cards-3 about-cards">
      <div class="card about-card">
        <div class="about-card-icon"><i class="fa-solid fa-book-open"></i></div>
        <h3>Nuestra misión</h3>
        <p>
          Acercar la lectura a todos los públicos mediante un catálogo cuidado,
          diverso y pensado para despertar la curiosidad y el pensamiento.
        </p>
      </div>

      <div class="card about-card">
        <div class="about-card-icon"><i class="fa-solid fa-heart"></i></div>
        <h3>Nuestros valores</h3>
        <p>
          Amor por los libros, respeto por el lector y compromiso con la calidad
          de cada obra que ofrecemos.
        </p>
      </div>

      <div class="card about-card">
        <div class="about-card-icon"><i class="fa-solid fa-compass"></i></div>
        <h3>Nuestra librería</h3>
        <p>
          Un espacio digital pensado para disfrutar de la lectura con calma,
          descubrir recomendaciones y encontrar historias que dejen huella.
        </p>
      </div>
    </div>

    <div class="about-panel">
      <div class="about-panel-text">
        <h2>Un espacio para quienes aman leer</h2>
        <p>
          Cada libro es una puerta abierta a nuevas perspectivas. En Los Círculos
          de Atenea seleccionamos nuestro catálogo pensando en lectores curiosos,
          apasionados y con ganas de seguir aprendiendo a través de la lectura.
        </p>

        <ul class="about-list">
          <li><i class="fa-solid fa-check"></i> Libros para todos los gustos y edades</li>
          <li><i class="fa-solid fa-check"></i> Recomendaciones pensadas para el lector</li>
          <li><i class="fa-solid fa-check"></i> Habla con nuestros expertos para una recomendación </li>
        </ul>
      </div>

      <div class="about-panel-box">
        <h3>Descubre tu próxima lectura</h3>
        <p>
          Explora nuestro catálogo y encuentra ese libro que te acompañará
          durante horas, días o incluso toda la vida.
        </p>
        <a href="/tienda" class="btn btn-primary">Entrar a la tienda</a>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
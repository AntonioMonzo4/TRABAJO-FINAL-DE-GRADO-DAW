<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

/* Libros destacados */
$libros_destacados = [];
try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY RAND() LIMIT 8");
    $libros_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
}

/* Categorías */
$categorias = [];
try {
    $stmt = $pdo->query("
        SELECT DISTINCT genero_literario 
        FROM books 
        WHERE genero_literario IS NOT NULL 
        
    ");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
}
?>

<main class="main">

    <!-- HERO -->
    <section class="hero hero-home">
        <div class="container hero-content">
            <h1>Los Círculos de Atenea</h1>
            <div class="hero-buttons">
                <a href="/books" class="btn btn-primary">Explorar libros</a>
                <a href="/about" class="btn btn-secondary">Conócenos</a>
            </div>
        </div>
    </section>

    <!-- DESTACADOS -->
    <?php if ($libros_destacados): ?>
        <section class="destacados">
            <div class="container">
              
                <section class="carousel-container">
                    <div class="container">
                        <h2 class="carousel-title">Libros destacados</h2>

                        <div class="carousel-slide">
                            <?php
                            $count = 0;
                            foreach ($libros_destacados as $libro):
                                if ($count >= 8) break;
                                $count++;
                            ?>
                                <article class="carousel-item">
                                    <img
                                        src="/VIEW/img/libros/<?= htmlspecialchars($libro['imagen'] ?? 'default-book.png') ?>"
                                        alt="<?= htmlspecialchars($libro['titulo']) ?>"
                                        class="book-cover">

                                    <h3 class="book-title"><?= htmlspecialchars($libro['titulo']) ?></h3>
                                    <p class="book-author"><?= htmlspecialchars($libro['autor']) ?></p>
                                    <span class="book-price"><?= number_format($libro['precio'], 2) ?> €</span>

                                    <a href="/book/<?= (int)$libro['book_id'] ?>" class="btn btn-primary">
                                        Ver detalle
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>



            </div>
        </section>
    <?php endif; ?>

    <!-- CATEGORÍAS -->
    <section class="categorias">
        <div class="container">
            <h2>Explora por categorías</h2>
            <div class="grid-categorias">
                <?php foreach ($categorias as $cat): ?>
                    <a href="/books?genero=<?= urlencode($cat['genero_literario']) ?>"
                        class="categoria-card">
                        <h3><?= htmlspecialchars($cat['genero_literario']) ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- TESTIMONIOS -->
    <section class="testimonios">
        <div class="container">
            <h2>Lo que dicen nuestros lectores</h2>
            <div class="grid-testimonios">
                <div class="testimonio-card">
                    <p>“La mejor librería online.”</p>
                    <strong>María González</strong>
                </div>
                <div class="testimonio-card">
                    <p>“Servicio impecable y gran catálogo.”</p>
                    <strong>Carlos Ruiz</strong>
                </div>
                  <div class="testimonio-card">
                    <p>“Gran variedad para saciar mi hambre de lectura.”</p>
                    <strong>Pepe Navarro</strong>
                </div>
            </div>
        </div>
    </section>

  

</main>

<?php require_once __DIR__ . '/footer.php'; ?>

<script src="/VIEW/js/carrito.js"></script>
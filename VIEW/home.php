<?php
require_once 'header.php';
require_once '../MODEL/conexion.php';

$libros_destacados = [];

try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY RAND() LIMIT 8");
    $libros_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los libros como un arreglo asociativo FETCH_ASSOC
} catch (PDOException $e) {
    // Manejar el error, por ejemplo, registrarlo o mostrar un mensaje amigable
    $libros_destacados = [];
    error_log("Error al obtener libros destacados: " . $e->getMessage());
}

$categorias = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT genero_literario FROM books WHERE genero_lioterario IS NOT NULL LIMIT 6");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorias = [
        ['genero_literario' => 'Fantasía'],
        ['genero_literario' => 'Thriller'],
        ['genero_literario' => 'Terror'],
        ['genero_literario' => 'Ciencia Ficción'],
        ['genero_literario' => 'Clásico'],
        ['genero_literario' => 'Misterio']
    ];
    error_log("Error al obtener categorías: " . $e->getMessage());
}
?>

<main>

    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenido a Los Círculos de Atenea</h1>
            <p>Descubre un mundo de conocimiento, aventura y sabiduría a través de nuestros libros y productos exclusivos.</p>
            <div class="hero-buttons">
                <a href="../index.php?pagina=categoriaTienda" class="btn btn-primary">Explorar Tienda</a>
                <a href="../index.php?pagina=sobreNosotros" class="btn btn-secondary">Conócenos</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Libros y lectura">
        </div>
    </section>

    <!-- Carrusel de Libros Destacados -->
    <?php if (!empty($libros_destacados)): ?>
    <div class="carousel-container">
        <h2 class="carousel-title">Libros Destacados</h2>
        
        <div class="carousel-slide">
            <?php foreach ($libros_destacados as $libro): ?>
            <div class="carousel-item">
                <img src="../uploads/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>" 
                     alt="<?php echo htmlspecialchars($libro['titulo']); ?>" 
                     class="book-cover"
                     onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                <div class="book-title"><?php echo htmlspecialchars($libro['titulo']); ?></div>
                <div class="book-author"><?php echo htmlspecialchars($libro['autor']); ?></div>
                <div class="book-price">$<?php echo number_format($libro['precio'], 2); ?></div>
                <a href="#libro-<?php echo $libro['book_id']; ?>" class="carousel-link">Ver detalles</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="carousel-controls">
        <button id="prevBtn">Anterior</button>
        <button id="nextBtn">Siguiente</button>
    </div>

    <div class="carousel-indicators">
        <?php 
        $numIndicators = ceil(count($libros_destacados) / 4);
        for ($i = 0; $i < $numIndicators; $i++): 
        ?>
            <span class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>

    <!-- Detalles de los libros -->
    <?php foreach ($libros_destacados as $libro): ?>
    <div id="libro-<?php echo $libro['book_id']; ?>" class="book-details">
        <div class="book-details-header">
            <img src="../uploads/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>" 
                 alt="<?php echo htmlspecialchars($libro['titulo']); ?>" 
                 class="book-details-cover"
                 onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
            <div class="book-details-info">
                <h2 class="book-details-title"><?php echo htmlspecialchars($libro['titulo']); ?></h2>
                <p class="book-details-author"><?php echo htmlspecialchars($libro['autor']); ?></p>
                <p class="book-details-price">$<?php echo number_format($libro['precio'], 2); ?></p>
                <p class="book-details-description">
                    <?php echo htmlspecialchars($libro['descripcion'] ?? 'Descripción no disponible.'); ?>
                </p>
                <div class="book-meta">
                    <span class="book-category">Género: <?php echo htmlspecialchars($libro['genero_literario'] ?? 'No especificado'); ?></span>
                    <span class="book-stock">Stock: <?php echo $libro['stock'] ?? 0; ?></span>
                </div>
                <button class="add-to-cart" data-libro-id="<?php echo $libro['book_id']; ?>">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>
    <?php endforeach; ?>

    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: white; margin: 20px; border-radius: 8px;">
            <h3>No hay libros destacados en este momento</h3>
            <p>Próximamente añadiremos nuevos títulos destacados.</p>
        </div>
    <?php endif; ?>

    <!-- Sección de Categorías -->
    <section class="categorias">
        <div class="container">
            <h2>Explora por Categorías</h2>
            <div class="grid-categorias">
                <?php foreach ($categorias as $categoria): 
                    $genero = $categoria['genero_literario'];
                    $icono = match($genero) {
                        'Fantasía' => 'dragon',
                        'Thriller' => 'search',
                        'Terror' => 'ghost',
                        'Ciencia Ficción' => 'robot',
                        'Clásico' => 'book',
                        'Misterio' => 'user-secret',
                        'Romance' => 'heart',
                        'Mitología' => 'bolt',
                        'Ciencia' => 'flask',
                        'Biografía' => 'user',
                        default => 'book'
                    };
                ?>
                <a href="../index.php?pagina=categoriaTienda&genero=<?php echo urlencode($genero); ?>" class="categoria-card">
                    <div class="categoria-icono">
                        <i class="fas fa-<?php echo $icono; ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($genero); ?></h3>
                    <p>Descubre nuestros libros de <?php echo htmlspecialchars($genero); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Sección de Testimonios -->
    <section class="testimonios">
        <div class="container">
            <h2>Lo que dicen nuestros lectores</h2>
            <div class="grid-testimonios">
                <div class="testimonio-card">
                    <div class="testimonio-texto">
                        <p>"La mejor librería online. Encuentras desde clásicos hasta las últimas novedades con una calidad de servicio excepcional."</p>
                    </div>
                    <div class="testimonio-autor">
                        <strong>María González</strong>
                        <span>Lectora habitual</span>
                    </div>
                </div>

                <div class="testimonio-card">
                    <div class="testimonio-texto">
                        <p>"El servicio es excepcional y los libros llegan perfectamente empaquetados. La selección de libros es increíble."</p>
                    </div>
                    <div class="testimonio-autor">
                        <strong>Carlos Ruiz</strong>
                        <span>Coleccionista</span>
                    </div>
                </div>

                <div class="testimonio-card">
                    <div class="testimonio-texto">
                        <p>"Me encanta la variedad de productos y las recomendaciones personalizadas. Siempre encuentro algo interesante."</p>
                    </div>
                    <div class="testimonio-autor">
                        <strong>Ana Martínez</strong>
                        <span>Club de lectura</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>Mantente Informado</h2>
                <p>Suscríbete a nuestro newsletter y recibe las últimas novedades y ofertas exclusivas.</p>
                <form class="newsletter-form" action="../index.php?pagina=newsletter" method="POST">
                    <input type="email" name="email" placeholder="Tu correo electrónico" required>
                    <button type="submit" class="btn btn-primary">Suscribirse</button>
                </form>
            </div>
        </div>
    </section>


</main>

<?php require_once 'footer.php'; ?>
<script src="/VIEW/js/carrusel.js"></script>
<?php require_once 'header.php'; 

?>

<main>
   
   <section class="hero">
        <div class="hero-content">
            <h1>Bienvenido a Los Círculos de Atenea</h1>
            <p>Descubre un mundo de conocimiento, aventura y sabiduría a través de nuestros libros y productos exclusivos.</p>
            <div class="hero-buttons">
                <a href="index.php?pagina=categoriaTienda" class="btn btn-primary">Explorar Tienda</a>
                <a href="index.php?pagina=sobreNosotros" class="btn btn-secondary">Conócenos</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="./VIEW/img/hero-banner.jpg" alt="Libros y lectura" />
        </div>
    </section>

    <!-- Carrusel de Libros Destacados -->
    <div class="carousel-container">
        <h2 class="carousel-title">Libros Destacados</h2>
        
        <div class="carousel-slide">
            <!-- Libro 1 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Cien años de soledad" class="book-cover">
                <div class="book-title">Cien años de soledad</div>
                <div class="book-author">Gabriel García Márquez</div>
                <div class="book-price">$19.99</div>
                <a href="#cien-anos-soledad" class="carousel-link">Ver detalles</a>
            </div>
            
            <!-- Libro 2 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1531346680769-a1d79b57de5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Don Quijote de la Mancha" class="book-cover">
                <div class="book-title">Don Quijote de la Mancha</div>
                <div class="book-author">Miguel de Cervantes</div>
                <div class="book-price">$24.99</div>
                <a href="#don-quijote" class="carousel-link">Ver detalles</a>
            </div>
            
            <!-- Libro 3 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="1984" class="book-cover">
                <div class="book-title">1984</div>
                <div class="book-author">George Orwell</div>
                <div class="book-price">$16.99</div>
                <a href="#1984" class="carousel-link">Ver detalles</a>
            </div>
            
            <!-- Libro 4 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Orgullo y prejuicio" class="book-cover">
                <div class="book-title">Orgullo y prejuicio</div>
                <div class="book-author">Jane Austen</div>
                <div class="book-price">$18.99</div>
                <a href="#orgullo-prejuicio" class="carousel-link">Ver detalles</a>
            </div>
        </div>
    </div>

    <div class="carousel-controls">
        <button id="prevBtn">Anterior</button>
        <button id="nextBtn">Siguiente</button>
    </div>

    <div class="carousel-indicators">
        <span class="indicator active" data-index="0"></span>
        <span class="indicator" data-index="1"></span>
        <span class="indicator" data-index="2"></span>
    </div>

    <!-- Sección de Categorías -->
    <section class="categorias">
        <div class="container">
            <h2>Explora por Categorías</h2>
            <div class="grid-categorias">
                <a href="index.php?pagina=categoriaTienda&tipo=ficcion" class="categoria-card">
                    <div class="categoria-icono">
                        <i class="fas fa-dragon"></i>
                    </div>
                    <h3>Ficción</h3>
                    <p>Novelas y relatos de fantasía</p>
                </a>

                <a href="index.php?pagina=categoriaTienda&tipo=no-ficcion" class="categoria-card">
                    <div class="categoria-icono">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>No Ficción</h3>
                    <p>Biografías y ensayos</p>
                </a>

                <a href="index.php?pagina=categoriaTienda&tipo=poesia" class="categoria-card">
                    <div class="categoria-icono">
                        <i class="fas fa-feather"></i>
                    </div>
                    <h3>Poesía</h3>
                    <p>Versos y rimas</p>
                </a>

                <a href="index.php?pagina=categoriaTienda&tipo=comics" class="categoria-card">
                    <div class="categoria-icono">
                        <i class="fas fa-mask"></i>
                    </div>
                    <h3>Cómics</h3>
                    <p>Novelas gráficas</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Detalles de los libros -->
    <div id="cien-anos-soledad" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Cien años de soledad" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">Cien años de soledad</h2>
                <p class="book-details-author">Gabriel García Márquez</p>
                <p class="book-details-price">$19.99</p>
                <p class="book-details-description">
                    "Cien años de soledad" es una obra maestra del realismo mágico y la novela más conocida del escritor colombiano Gabriel García Márquez. La historia narra la saga de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <!-- ... resto de los detalles de libros ... -->

    <!-- Sección de Testimonios -->
    <section class="testimonios">
        <div class="container">
            <h2>Lo que dicen nuestros lectores</h2>
            <div class="grid-testimonios">
                <div class="testimonio-card">
                    <div class="testimonio-texto">
                        <p>"La mejor librería online. Encuentras desde clásicos hasta las últimas novedades."</p>
                    </div>
                    <div class="testimonio-autor">
                        <strong>María González</strong>
                        <span>Lectora habitual</span>
                    </div>
                </div>

                <div class="testimonio-card">
                    <div class="testimonio-texto">
                        <p>"El servicio es excepcional y los libros llegan perfectamente empaquetados."</p>
                    </div>
                    <div class="testimonio-autor">
                        <strong>Carlos Ruiz</strong>
                        <span>Coleccionista</span>
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
                <form class="newsletter-form">
                    <input type="email" placeholder="Tu correo electrónico" required>
                    <button type="submit" class="btn btn-primary">Suscribirse</button>
                </form>
            </div>
        </div>
    </section>

    
</main>

<?php require_once 'footer.php'; ?>
<script src="/VIEW/js/carrusel.js"></script>
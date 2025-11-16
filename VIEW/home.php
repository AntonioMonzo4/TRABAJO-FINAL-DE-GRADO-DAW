<?php require_once 'header.php'; 

?>
<main>
    <style>
        /* Estilos para el carrusel */
        .carousel-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto 40px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding: 20px 0;
        }
        
        .carousel-title {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 2rem;
        }
        
        .carousel-slide {
            display: flex;
            transition: transform 0.5s ease;
        }
        
        .carousel-item {
            min-width: 25%;
            padding: 0 15px;
            text-align: center;
        }
        
        .book-cover {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .book-cover:hover {
            transform: scale(1.05);
        }
        
        .book-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .book-author {
            color: #666;
            margin-bottom: 10px;
            font-style: italic;
        }
        
        .book-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        .carousel-controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .carousel-controls button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .carousel-controls button:hover {
            background-color: #2980b9;
        }
        
        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .carousel-indicators span {
            width: 12px;
            height: 12px;
            background-color: #bdc3c7;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .carousel-indicators span.active {
            background-color: #3498db;
        }
        
        .book-details {
            display: none;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .book-details.active {
            display: block;
        }
        
        .book-details-header {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        
        .book-details-cover {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .book-details-info {
            flex: 1;
        }
        
        .book-details-title {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .book-details-author {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .book-details-price {
            font-size: 1.5rem;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .book-details-description {
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .add-to-cart {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }
        
        .add-to-cart:hover {
            background-color: #27ae60;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 992px) {
            .carousel-item {
                min-width: 33.33%;
            }
        }
        
        @media (max-width: 768px) {
            .carousel-item {
                min-width: 50%;
            }
            
            .book-details-header {
                flex-direction: column;
            }
            
            .book-details-cover {
                align-self: center;
            }
        }
        
        @media (max-width: 576px) {
            .carousel-item {
                min-width: 100%;
            }
        }
    </style>

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
            
            <!-- Libro 5 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="El principito" class="book-cover">
                <div class="book-title">El principito</div>
                <div class="book-author">Antoine de Saint-Exupéry</div>
                <div class="book-price">$14.99</div>
                <a href="#principito" class="carousel-link">Ver detalles</a>
            </div>
            
            <!-- Libro 6 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Crimen y castigo" class="book-cover">
                <div class="book-title">Crimen y castigo</div>
                <div class="book-author">Fiódor Dostoyevski</div>
                <div class="book-price">$22.99</div>
                <a href="#crimen-castigo" class="carousel-link">Ver detalles</a>
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

    <div id="don-quijote" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1531346680769-a1d79b57de5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Don Quijote de la Mancha" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">Don Quijote de la Mancha</h2>
                <p class="book-details-author">Miguel de Cervantes</p>
                <p class="book-details-price">$24.99</p>
                <p class="book-details-description">
                    "El ingenioso hidalgo don Quijote de la Mancha" es una novela escrita por el español Miguel de Cervantes. Publicada en dos partes (1605 y 1615), es una de las obras más destacadas de la literatura española y universal, así como una de las más traducidas.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <div id="1984" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="1984" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">1984</h2>
                <p class="book-details-author">George Orwell</p>
                <p class="book-details-price">$16.99</p>
                <p class="book-details-description">
                    "1984" es una novela distópica del escritor británico George Orwell publicada en 1949. La obra presenta un estado totalitario que ejerce un control absoluto sobre la sociedad, incluyendo el pensamiento de los individuos, a través de la vigilancia constante.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <div id="orgullo-prejuicio" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Orgullo y prejuicio" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">Orgullo y prejuicio</h2>
                <p class="book-details-author">Jane Austen</p>
                <p class="book-details-price">$18.99</p>
                <p class="book-details-description">
                    "Orgullo y prejuicio" es una novela de la escritora británica Jane Austen publicada en 1813. La historia sigue la vida de Elizabeth Bennet, una de las cinco hijas de la familia Bennet, mientras navega por cuestiones de moral, educación, matrimonio y estatus social.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <div id="principito" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="El principito" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">El principito</h2>
                <p class="book-details-author">Antoine de Saint-Exupéry</p>
                <p class="book-details-price">$14.99</p>
                <p class="book-details-description">
                    "El principito" es una novela corta y la obra más famosa del escritor y aviador francés Antoine de Saint-Exupéry. La historia sigue a un piloto varado en el desierto del Sahara que conoce a un pequeño príncipe proveniente de otro planeta.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <div id="crimen-castigo" class="book-details">
        <div class="book-details-header">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Crimen y castigo" class="book-details-cover">
            <div class="book-details-info">
                <h2 class="book-details-title">Crimen y castigo</h2>
                <p class="book-details-author">Fiódor Dostoyevski</p>
                <p class="book-details-price">$22.99</p>
                <p class="book-details-description">
                    "Crimen y castigo" es una novela del escritor ruso Fiódor Dostoyevski publicada en 1866. La obra sigue la vida de Rodión Raskólnikov, un estudiante pobre en San Petersburgo que formula un plan para matar a una prestamista desalmada y robar su dinero.
                </p>
                <button class="add-to-cart">Añadir al carrito</button>
            </div>
        </div>
        <a href="#" class="back-link">Volver al carrusel</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del carrusel
            const carouselSlide = document.querySelector('.carousel-slide');
            const carouselItems = document.querySelectorAll('.carousel-item');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const indicators = document.querySelectorAll('.indicator');
            const carouselLinks = document.querySelectorAll('.carousel-link');
            const backLinks = document.querySelectorAll('.back-link');
            const bookDetails = document.querySelectorAll('.book-details');
            
            // Configuración
            let counter = 0;
            const itemsToShow = 4; // Número de libros a mostrar a la vez
            const totalItems = carouselItems.length;
            let itemWidth = carouselItems[0].offsetWidth;
            const autoSlideInterval = 5000; // 5 segundos
            let autoSlide;
            
            // Calcular el ancho total del carrusel
            function updateItemWidth() {
                itemWidth = carouselItems[0].offsetWidth;
                carouselSlide.style.transform = 'translateX(' + (-itemWidth * counter) + 'px)';
            }
            
            // Inicializar el carrusel
            function initCarousel() {
                updateItemWidth();
                startAutoSlide();
            }
            
            // Navegación automática
            function startAutoSlide() {
                autoSlide = setInterval(() => {
                    nextSlide();
                }, autoSlideInterval);
            }
            
            function resetAutoSlide() {
                clearInterval(autoSlide);
                startAutoSlide();
            }
            
            // Cambiar a la siguiente diapositiva
            function nextSlide() {
                if (counter >= totalItems - itemsToShow) {
                    counter = 0;
                } else {
                    counter++;
                }
                updateCarousel();
            }
            
            // Cambiar a la diapositiva anterior
            function prevSlide() {
                if (counter <= 0) {
                    counter = totalItems - itemsToShow;
                } else {
                    counter--;
                }
                updateCarousel();
            }
            
            // Actualizar la posición del carrusel
            function updateCarousel() {
                carouselSlide.style.transform = 'translateX(' + (-itemWidth * counter) + 'px)';
                
                // Actualizar indicadores
                const indicatorIndex = Math.floor(counter / itemsToShow);
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === indicatorIndex);
                });
                
                resetAutoSlide();
            }
            
            // Navegar a una diapositiva específica
            function goToSlide(index) {
                counter = index * itemsToShow;
                updateCarousel();
            }
            
            // Mostrar detalles del libro
            function showBookDetails(bookId) {
                // Ocultar todos los detalles
                bookDetails.forEach(detail => {
                    detail.classList.remove('active');
                });
                
                // Mostrar el libro seleccionado
                const targetBook = document.getElementById(bookId);
                if (targetBook) {
                    targetBook.classList.add('active');
                    
                    // Desplazar hacia los detalles
                    targetBook.scrollIntoView({ behavior: 'smooth' });
                }
            }
            
            // Event Listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Indicadores
            indicators.forEach(indicator => {
                indicator.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    goToSlide(index);
                });
            });
            
            // Enlaces del carrusel
            carouselLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    showBookDetails(targetId);
                });
            });
            
            // Enlaces de vuelta al carrusel
            backLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Ocultar todos los detalles
                    bookDetails.forEach(detail => {
                        detail.classList.remove('active');
                    });
                    
                    // Desplazar hacia el carrusel
                    document.querySelector('.carousel-container').scrollIntoView({ behavior: 'smooth' });
                });
            });
            
            // Botones de añadir al carrito
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const bookTitle = this.closest('.book-details').querySelector('.book-details-title').textContent;
                    alert(`"${bookTitle}" ha sido añadido al carrito`);
                });
            });
            
            // Inicializar el carrusel
            initCarousel();
            
            // Redimensionar ventana
            window.addEventListener('resize', function() {
                updateItemWidth();
            });
        });
    </script>
</main>

<?php require_once 'footer.php'; ?>
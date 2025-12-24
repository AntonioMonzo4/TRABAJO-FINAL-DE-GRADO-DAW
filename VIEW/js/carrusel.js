// VIEW/js/carrusel.js
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
        if (carouselItems[0]) {
            itemWidth = carouselItems[0].offsetWidth;
            carouselSlide.style.transform = 'translateX(' + (-itemWidth * counter) + 'px)';
        }
    }
    
    // Inicializar el carrusel
    function initCarousel() {
        if (carouselItems.length > 0) {
            updateItemWidth();
            startAutoSlide();
        }
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
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
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
            const carouselContainer = document.querySelector('.carousel-container');
            if (carouselContainer) {
                carouselContainer.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    
    // Botones de añadir al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const bookTitle = this.closest('.book-details').querySelector('.book-details-title').textContent;
            alert(`"${bookTitle}" ha sido añadido al carrito`);
            
            // Aquí puedes añadir la lógica real del carrito después
            // Por ejemplo: addToCart(bookId, bookTitle, price);
        });
    });
    
    // Inicializar el carrusel
    initCarousel();
    
    // Redimensionar ventana
    window.addEventListener('resize', function() {
        updateItemWidth();
    });
});
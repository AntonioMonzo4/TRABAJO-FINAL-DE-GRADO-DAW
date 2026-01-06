// VIEW/js/detalle_producto.js - Funcionalidades para la página de detalle

document.addEventListener('DOMContentLoaded', function () {
    // Tabs functionality
    const tabHeaders = document.querySelectorAll('.tab-header');
    const tabContents = document.querySelectorAll('.tab-content');

    tabHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const tabId = this.getAttribute('data-tab');

            // Remove active class from all headers and contents
            tabHeaders.forEach(h => h.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to current header and content
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Quantity controls
    const cantidadInput = document.getElementById('cantidad');
    const btnRestar = document.querySelector('.btn-restar');
    const btnSumar = document.querySelector('.btn-sumar');

    if (btnRestar && btnSumar && cantidadInput) {
        btnRestar.addEventListener('click', function () {
            let currentValue = parseInt(cantidadInput.value);
            if (currentValue > 1) {
                cantidadInput.value = currentValue - 1;
            }
        });

        btnSumar.addEventListener('click', function () {
            let currentValue = parseInt(cantidadInput.value);
            const maxStock = parseInt(cantidadInput.getAttribute('max'));
            if (currentValue < maxStock) {
                cantidadInput.value = currentValue + 1;
            }
        });

        cantidadInput.addEventListener('change', function () {
            let value = parseInt(this.value);
            const maxStock = parseInt(this.getAttribute('max'));
            const minStock = parseInt(this.getAttribute('min'));

            if (value < minStock) {
                this.value = minStock;
            } else if (value > maxStock) {
                this.value = maxStock;
            }
        });
    }


    /*
    // Add to cart functionality with quantity
    const btnAnadirCarrito = document.querySelector('.btn-anadir-carrito-detalle');
    
    if (btnAnadirCarrito) {
        btnAnadirCarrito.addEventListener('click', function() {
            if (this.classList.contains('disabled')) return;

            const cantidad = parseInt(cantidadInput.value);
            const producto = {
                id: this.getAttribute('data-book-id'),
                tipo: 'book',
                nombre: this.getAttribute('data-book-titulo'),
                precio: parseFloat(this.getAttribute('data-book-precio')),
                imagen: this.getAttribute('data-book-imagen')
            };

            if (typeof window.carritoManager !== 'undefined') {
                // Add the product multiple times based on quantity
                for (let i = 0; i < cantidad; i++) {
                    window.carritoManager.agregarAlCarrito(producto);
                }
                
                // Show success message with quantity
                const notificacion = document.createElement('div');
                notificacion.className = 'notificacion-carrito';
                notificacion.innerHTML = `
                    <div class="notificacion-contenido">
                        <span>${cantidad} x "${producto.nombre}" añadido(s) al carrito</span>
                        <button class="notificacion-cerrar">&times;</button>
                    </div>
                `;
                
                // Apply styles and add to DOM
                notificacion.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--color-secundario);
                    color: var(--color-blanco);
                    padding: 15px 20px;
                    border-radius: var(--radio-borde);
                    box-shadow: var(--sombra-media);
                    z-index: 10000;
                    animation: slideInRight 0.3s ease;
                `;
                
                document.body.appendChild(notificacion);

                // Auto-remove after 3 seconds
                setTimeout(() => {
                    if (notificacion.parentElement) {
                        notificacion.remove();
                    }
                }, 3000);
            } else {
                alert('Error: Sistema de carrito no disponible');
            }
        });
    }

    */

    // Image gallery functionality (for future implementation)
    const miniaturas = document.querySelectorAll('.miniatura');
    const imagenPrincipal = document.getElementById('imagen-principal');

    miniaturas.forEach(miniatura => {
        miniatura.addEventListener('click', function () {
            const nuevaImagen = this.getAttribute('data-imagen');

            // Remove active class from all thumbnails
            miniaturas.forEach(m => m.classList.remove('active'));

            // Add active class to clicked thumbnail
            this.classList.add('active');

            // Update main image
            imagenPrincipal.src = nuevaImagen;
        });
    });

    // Buy now button functionality
    const btnComprarAhora = document.querySelector('.btn-comprar-ahora');

    if (btnComprarAhora) {
        btnComprarAhora.addEventListener('click', function () {
            if (this.classList.contains('disabled')) return;

            const cantidad = parseInt(cantidadInput.value);
            const producto = {
                id: btnAnadirCarrito.getAttribute('data-book-id'),
                tipo: 'book',
                nombre: btnAnadirCarrito.getAttribute('data-book-titulo'),
                precio: parseFloat(btnAnadirCarrito.getAttribute('data-book-precio')),
                imagen: btnAnadirCarrito.getAttribute('data-book-imagen')
            };

            if (typeof window.carritoManager !== 'undefined') {
                // Clear cart and add only this product
                window.carritoManager.vaciarCarrito();

                // Add the product multiple times based on quantity
                for (let i = 0; i < cantidad; i++) {
                    window.carritoManager.agregarAlCarrito(producto);
                }

                // Redirect to checkout
                setTimeout(() => {
                    window.location.href = '../index.php?pagina=checkout';
                }, 500);
            } else {
                alert('Error: Sistema de carrito no disponible');
            }
        });
    }
});
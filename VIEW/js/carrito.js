// VIEW/js/carrito.js - Sistema de gestión del carrito

class CarritoManager {
    constructor() {
        this.carrito = this.obtenerCarritoLocalStorage();
        this.actualizarContadorCarrito();
        this.inicializarEventListeners();
    }

    // Obtener carrito desde localStorage
    obtenerCarritoLocalStorage() {
        const carrito = localStorage.getItem('carrito_circulos_atenea');
        return carrito ? JSON.parse(carrito) : [];
    }

    // Guardar carrito en localStorage
    guardarCarritoLocalStorage() {
        localStorage.setItem('carrito_circulos_atenea', JSON.stringify(this.carrito));
    }

    // Actualizar contador del carrito en el header

    actualizarContadorCarrito() {
        const contador = document.querySelector('.carrito-count');
        if (!contador) return;

        const totalItems = this.carrito.reduce(
            (total, item) => total + item.cantidad,
            0
        );

        contador.textContent = totalItems;
        contador.style.display = totalItems > 0 ? 'flex' : 'none';
    }

    // Añadir producto al carrito
    agregarAlCarrito(producto) {
        console.log('Producto añadido:', producto);

        const productoExistente = this.carrito.find(
            item => item.id === producto.id && item.tipo === producto.tipo
        );

        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            this.carrito.push({
                id: producto.id,
                tipo: producto.tipo,
                nombre: producto.nombre,
                precio: producto.precio,
                imagen: producto.imagen,
                cantidad: 1
            });
        }

        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
        this.mostrarNotificacion(`${producto.nombre} añadido al carrito`);
    }

    // Eliminar producto del carrito
    eliminarDelCarrito(id, tipo) {
        this.carrito = this.carrito.filter(item =>
            !(item.id === id && item.tipo === tipo)
        );
        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

    // Actualizar cantidad de un producto
    actualizarCantidad(id, tipo, nuevaCantidad) {
        const producto = this.carrito.find(item =>
            item.id === id && item.tipo === tipo
        );

        if (producto) {
            if (nuevaCantidad <= 0) {
                this.eliminarDelCarrito(id, tipo);
            } else {
                producto.cantidad = nuevaCantidad;
                this.guardarCarritoLocalStorage();
                this.actualizarContadorCarrito();
            }
        }
    }
    // Vaciar carrito
    vaciarCarrito() {
        this.carrito = [];
        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

    // Obtener total del carrito
    obtenerTotalCarrito() {
        return this.carrito.reduce((total, item) =>
            total + (item.precio * item.cantidad), 0
        );
    }

    

    // Mostrar notificación
    mostrarNotificacion(mensaje) {
        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = 'notificacion-carrito';
        notificacion.innerHTML = `
            <div class="notificacion-contenido">
                <span>${mensaje}</span>
                <button class="notificacion-cerrar">&times;</button>
            </div>
        `;

        // Añadir estilos
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

        notificacion.querySelector('.notificacion-contenido').style.cssText = `
            display: flex;
            align-items: center;
            gap: 10px;
        `;

        notificacion.querySelector('.notificacion-cerrar').style.cssText = `
            background: none;
            border: none;
            color: var(--color-blanco);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        `;

        // Añadir al DOM
        document.body.appendChild(notificacion);

        // Evento para cerrar notificación
        notificacion.querySelector('.notificacion-cerrar').addEventListener('click', () => {
            notificacion.remove();
        });

        // Auto-eliminar después de 3 segundos
        setTimeout(() => {
            if (notificacion.parentElement) {
                notificacion.remove();
            }
        }, 3000);
    }

    // Inicializar event listeners
    inicializarEventListeners() {
        // Delegación de eventos para botones "Añadir al carrito"
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart')) {
                e.preventDefault();

                const button = e.target;
                const producto = {
                    id: button.getAttribute('data-book-id'),
                    tipo: 'book',
                    nombre: button.getAttribute('data-book-titulo'),
                    precio: parseFloat(button.getAttribute('data-book-precio')),
                    imagen: button.getAttribute('data-book-imagen')
                };

                this.agregarAlCarrito(producto);
            }
        });

        // También puedes añadir para otros tipos de productos en el futuro
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-other-to-cart')) {
                e.preventDefault();
                const button = e.target;
                const producto = {
                    id: button.getAttribute('data-product-id'),
                    tipo: 'other',
                    nombre: button.getAttribute('data-product-nombre'),
                    precio: parseFloat(button.getAttribute('data-product-precio')),
                    imagen: button.getAttribute('data-product-imagen')
                };
                this.agregarAlCarrito(producto);
            }
        });
    }

    // Obtener todos los items del carrito (para la página del carrito)
    obtenerItemsCarrito() {
        return this.carrito;
    }
}

// Inicializar el carrito cuando se carga la página
document.addEventListener('DOMContentLoaded', function () {
    window.carritoManager = new CarritoManager();
});

// Añadir estilos CSS para las animaciones
const carritoStyles = `
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notificacion-carrito {
    animation: slideInRight 0.3s ease;
}
`;

// Insertar estilos en el head
if (!document.querySelector('#carrito-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'carrito-styles';
    styleElement.textContent = carritoStyles;
    document.head.appendChild(styleElement);
}
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
    // - producto: { id, tipo, nombre, precio, imagen, autor? }
    // - cantidad: número de unidades a añadir (por defecto 1)
    agregarAlCarrito(producto, cantidad = 1) {
        console.log('Producto añadido:', producto);

        const cantidadAAgregar = parseInt(String(cantidad ?? '1'), 10);
        const qty = Number.isFinite(cantidadAAgregar) && cantidadAAgregar > 0 ? cantidadAAgregar : 1;

        const productoExistente = this.carrito.find(
            item => item.id === producto.id && item.tipo === producto.tipo
        );

        if (productoExistente) {
            productoExistente.cantidad += qty;
        } else {
            this.carrito.push({
                id: producto.id,
                tipo: producto.tipo,
                nombre: producto.nombre,
                autor: producto.autor,
                precio: producto.precio,
                imagen: producto.imagen,
                cantidad: qty
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
        const notificacion = document.createElement('div');
        notificacion.className = 'notificacion-carrito';
        notificacion.innerHTML = `
            <div class="notificacion-contenido">
                <span>${mensaje}</span>
                <button class="notificacion-cerrar">&times;</button>
            </div>
        `;

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

        document.body.appendChild(notificacion);

        notificacion.querySelector('.notificacion-cerrar').addEventListener('click', () => {
            notificacion.remove();
        });

        setTimeout(() => {
            if (notificacion.parentElement) {
                notificacion.remove();
            }
        }, 3000);
    }

    // Inicializar event listeners
    inicializarEventListeners() {
        // Delegación robusta: funciona aunque se pinche en un <i>/<span> dentro del botón.
        // Además, evita submits accidentales cuando el botón está dentro de un <form>.
        document.addEventListener('click', (e) => {
            const btnBook = e.target.closest('.add-to-cart');
            const btnOther = e.target.closest('.add-other-to-cart');
            const button = btnBook || btnOther;
            if (!button) return;

            e.preventDefault();

            // Si hay un input de cantidad cerca (por ejemplo en la ficha), lo usamos.
            const cantidadInput = button.closest('form, .product, .libro-info, .card, article')?.querySelector('input[name="cantidad"]');
            const cantidad = cantidadInput ? parseInt(cantidadInput.value, 10) : 1;

            if (btnBook) {
                const producto = {
                    id: button.getAttribute('data-book-id'),
                    tipo: 'book',
                    nombre: button.getAttribute('data-book-titulo'),
                    autor: button.getAttribute('data-book-autor') || undefined,
                    precio: parseFloat(button.getAttribute('data-book-precio')),
                    imagen: button.getAttribute('data-book-imagen')
                };
                this.agregarAlCarrito(producto, cantidad);
                return;
            }

            const producto = {
                id: button.getAttribute('data-product-id'),
                tipo: 'other',
                nombre: button.getAttribute('data-product-nombre'),
                precio: parseFloat(button.getAttribute('data-product-precio')),
                imagen: button.getAttribute('data-product-imagen')
            };
            this.agregarAlCarrito(producto, cantidad);
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

if (!document.querySelector('#carrito-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'carrito-styles';
    styleElement.textContent = carritoStyles;
    document.head.appendChild(styleElement);
}

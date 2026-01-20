// VIEW/js/carrito.js

class CarritoManager {
    constructor() {
        this.carrito = this.obtenerCarritoLocalStorage();
        this.actualizarContadorCarrito();
        this.inicializarEventListeners();
    }

    obtenerCarritoLocalStorage() {
        const carrito = localStorage.getItem('carrito_circulos_atenea');
        return carrito ? JSON.parse(carrito) : [];
    }

    guardarCarritoLocalStorage() {
        localStorage.setItem('carrito_circulos_atenea', JSON.stringify(this.carrito));
    }

    actualizarContadorCarrito() {
        const contador = document.querySelector('.carrito-count');
        if (!contador) return;

        const totalItems = this.carrito.reduce((t, item) => t + (Number(item.cantidad) || 0), 0);
        contador.textContent = totalItems;
        contador.style.display = totalItems > 0 ? 'flex' : 'none';
    }

    agregarAlCarrito(producto, cantidad = 1) {
        const id = String(producto.id ?? '');
        const tipo = String(producto.tipo ?? '');
        if (!id || !tipo) return;

        const precio = Number(producto.precio);
        const precioSeguro = Number.isFinite(precio) && precio >= 0 ? precio : 0;

        const qty = Math.max(1, parseInt(String(cantidad ?? 1), 10) || 1);

        const existente = this.carrito.find(i => String(i.id) === id && String(i.tipo) === tipo);

        if (existente) {
            existente.cantidad = (Number(existente.cantidad) || 0) + qty;
        } else {
            this.carrito.push({
                id,
                tipo,
                nombre: producto.nombre ?? '',
                autor: producto.autor,
                precio: precioSeguro,
                imagen: producto.imagen ?? '',
                cantidad: qty
            });
        }

        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
        this.mostrarNotificacion(`${producto.nombre} añadido al carrito`);
    }

    eliminarDelCarrito(id, tipo) {
        const _id = String(id);
        const _tipo = String(tipo);
        this.carrito = this.carrito.filter(i => !(String(i.id) === _id && String(i.tipo) === _tipo));
        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

    actualizarCantidad(id, tipo, nuevaCantidad) {
        const _id = String(id);
        const _tipo = String(tipo);

        const item = this.carrito.find(i => String(i.id) === _id && String(i.tipo) === _tipo);
        if (!item) return;

        const nc = Number(nuevaCantidad);
        if (!Number.isFinite(nc) || nc <= 0) {
            this.eliminarDelCarrito(_id, _tipo);
            return;
        }

        item.cantidad = Math.min(99, Math.max(1, Math.floor(nc)));
        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

    obtenerTotalCarrito() {
        return this.carrito.reduce((t, i) => (t + (Number(i.precio) || 0) * (Number(i.cantidad) || 0)), 0);
    }

    obtenerItemsCarrito() {
        return this.carrito;
    }

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
            position: fixed; top: 20px; right: 20px;
            background: var(--color-secundario); color: var(--color-blanco);
            padding: 15px 20px; border-radius: var(--radio-borde);
            box-shadow: var(--sombra-media); z-index: 10000;
        `;

        document.body.appendChild(notificacion);

        notificacion.querySelector('.notificacion-cerrar').addEventListener('click', () => notificacion.remove());
        setTimeout(() => notificacion.remove(), 2500);
    }

    inicializarEventListeners() {
        // Delegación robusta para botones de añadir desde listados/detalle
        document.addEventListener('click', (e) => {
            const btnBook = e.target.closest('.add-to-cart');
            const btnOther = e.target.closest('.add-other-to-cart');
            const button = btnBook || btnOther;
            if (!button) return;

            e.preventDefault();

            const cantidadInput = button.closest('form, .product, .libro-info, .card, article')?.querySelector('input[name="cantidad"]');
            const cantidad = cantidadInput ? parseInt(cantidadInput.value, 10) : 1;

            if (btnBook) {
                this.agregarAlCarrito({
                    id: String(button.getAttribute('data-book-id') ?? ''),
                    tipo: 'book',
                    nombre: button.getAttribute('data-book-titulo') ?? '',
                    autor: button.getAttribute('data-book-autor') || undefined,
                    precio: parseFloat(button.getAttribute('data-book-precio')),
                    imagen: button.getAttribute('data-book-imagen') ?? ''
                }, cantidad);
                return;
            }

            this.agregarAlCarrito({
                id: String(button.getAttribute('data-product-id') ?? ''),
                tipo: 'other',
                nombre: button.getAttribute('data-product-nombre') ?? '',
                precio: parseFloat(button.getAttribute('data-product-precio')),
                imagen: button.getAttribute('data-product-imagen') ?? ''
            }, cantidad);
        });
    }

    vaciarCarrito() {
        this.carrito = [];
        this.guardarCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

}

document.addEventListener('DOMContentLoaded', () => {
    window.carritoManager = new CarritoManager();
});

// VIEW/js/carrito_pagina.js

class CarritoPagina {
    constructor() {
        this.carritoManager = window.carritoManager;
        this.carritoItemsEl = document.getElementById('carrito-items');
        this.init();
    }

    init() {
        this.bloquearDescuento();
        this.renderCarrito();
        this.setupDelegation();
        this.setupVaciarCarrito();
        this.setupCheckout();
    }

    bloquearDescuento() {
        const input = document.getElementById('codigo-descuento');
        const btn = document.getElementById('aplicar-descuento');
        const msg = document.getElementById('descuento-mensaje');

        if (input) {
            input.value = '';
            input.disabled = true;
            input.readOnly = true;
            input.placeholder = 'Códigos no disponibles';
        }
        if (btn) btn.disabled = true;
        if (msg) msg.textContent = 'Actualmente no tenemos disponible ningún código de descuento';
    }

    renderCarrito() {
        const items = this.carritoManager.obtenerItemsCarrito();
        const carritoVacio = document.getElementById('carrito-vacio');
        const carritoCount = document.getElementById('carrito-count');

        carritoCount.textContent = `${items.length} producto${items.length !== 1 ? 's' : ''}`;

        // limpia todo (menos el bloque de vacío que reinsertamos si aplica)
        this.carritoItemsEl.innerHTML = '';

        if (items.length === 0) {
            carritoVacio.style.display = 'block';
            this.carritoItemsEl.appendChild(carritoVacio);
            this.actualizarResumen(0, 0, 0, 0);
            this.toggleProcederPago(false);
            return;
        }

        carritoVacio.style.display = 'none';

        const template = document.getElementById('template-carrito-item');

        items.forEach((item, index) => {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.carrito-item');

            row.dataset.productId = String(item.id);
            row.dataset.productType = String(item.tipo);

            const img = row.querySelector('.item-imagen img');
            const baseImg = item.tipo === 'other' ? '/VIEW/img/productos/' : '/VIEW/img/libros/';
            img.src = `${baseImg}${item.imagen}`;
            img.alt = item.nombre;

            row.querySelector('.item-titulo').textContent = item.nombre;

            const autorEl = row.querySelector('.item-autor');
            if (item.tipo === 'book') {
                autorEl.style.display = '';
                autorEl.textContent = `por ${item.autor || 'Autor desconocido'}`;
            } else {
                autorEl.style.display = 'none';
            }

            const precio = Number(item.precio) || 0;
            const cantidad = Number(item.cantidad) || 1;

            row.querySelector('.item-precio-unitario').textContent = `${precio.toFixed(2)} € c/u`;

            const input = row.querySelector('.input-cantidad');
            input.value = cantidad;
            input.id = `cantidad-${index}`;

            row.querySelector('.total-precio').textContent = `${(precio * cantidad).toFixed(2)} €`;

            this.carritoItemsEl.appendChild(clone);
        });

        this.actualizarResumenTotal();
        this.toggleProcederPago(true);
    }

    setupDelegation() {
        if (!this.carritoItemsEl) return;

        this.carritoItemsEl.addEventListener('click', (e) => {
            const itemEl = e.target.closest('.carrito-item');
            if (!itemEl) return;

            const id = itemEl.dataset.productId;
            const tipo = itemEl.dataset.productType;

            // + / -
            if (e.target.closest('.btn-sumar')) {
                e.preventDefault();
                const input = itemEl.querySelector('.input-cantidad');
                const actual = parseInt(input.value, 10) || 1;
                this.carritoManager.actualizarCantidad(id, tipo, actual + 1);
                this.actualizarFila(itemEl);
                this.actualizarResumenTotal();
                return;
            }

            if (e.target.closest('.btn-restar')) {
                e.preventDefault();
                const input = itemEl.querySelector('.input-cantidad');
                const actual = parseInt(input.value, 10) || 1;
                const nueva = actual - 1;

                if (nueva <= 0) {
                    this.carritoManager.eliminarDelCarrito(id, tipo);
                    itemEl.remove();               // quita el hueco
                    this.postEliminarRefresh();     // refresca contadores/empty state
                } else {
                    this.carritoManager.actualizarCantidad(id, tipo, nueva);
                    this.actualizarFila(itemEl);
                    this.actualizarResumenTotal();
                }
                return;
            }

            // Eliminar
            if (e.target.closest('.btn-eliminar-item')) {
                e.preventDefault();
                this.carritoManager.eliminarDelCarrito(id, tipo);
                itemEl.remove();                   // quita el hueco
                this.postEliminarRefresh();
                this.mostrarNotificacion('Producto eliminado del carrito');
            }
        });

        // Cambio directo en input cantidad
        this.carritoItemsEl.addEventListener('change', (e) => {
            const input = e.target.closest('.input-cantidad');
            if (!input) return;

            const itemEl = e.target.closest('.carrito-item');
            if (!itemEl) return;

            const id = itemEl.dataset.productId;
            const tipo = itemEl.dataset.productType;

            const nueva = parseInt(input.value, 10);
            if (!Number.isFinite(nueva) || nueva <= 0) {
                this.carritoManager.eliminarDelCarrito(id, tipo);
                itemEl.remove();
                this.postEliminarRefresh();
                return;
            }

            this.carritoManager.actualizarCantidad(id, tipo, Math.min(99, Math.max(1, nueva)));
            this.actualizarFila(itemEl);
            this.actualizarResumenTotal();
        });
    }

    actualizarFila(itemEl) {
        const id = itemEl.dataset.productId;
        const tipo = itemEl.dataset.productType;

        const items = this.carritoManager.obtenerItemsCarrito();
        const item = items.find(i => String(i.id) === String(id) && String(i.tipo) === String(tipo));
        if (!item) return;

        const precio = Number(item.precio) || 0;
        const cantidad = Number(item.cantidad) || 1;

        const input = itemEl.querySelector('.input-cantidad');
        input.value = cantidad;

        itemEl.querySelector('.total-precio').textContent = `${(precio * cantidad).toFixed(2)} €`;
    }

    postEliminarRefresh() {
        const items = this.carritoManager.obtenerItemsCarrito();

        const carritoCount = document.getElementById('carrito-count');
        if (carritoCount) {
            carritoCount.textContent = `${items.length} producto${items.length !== 1 ? 's' : ''}`;
        }

        if (items.length === 0) {
            this.resetResumenVacio();   // <- aquí se pone todo a cero
            return;
        }

        this.actualizarResumenTotal();
        this.toggleProcederPago(true);
    }



    actualizarResumenTotal() {
        const subtotal = this.carritoManager.obtenerTotalCarrito();
        let envio = 5.99;
        if (subtotal >= 50 || subtotal === 0) envio = 0;

        const descuento = 0;
        const total = subtotal + envio - descuento;

        this.actualizarResumen(subtotal, envio, descuento, total);
    }

    actualizarResumen(subtotal, envio, descuento, total) {
        document.getElementById('subtotal').textContent = `${Number(subtotal).toFixed(2)} €`;
        document.getElementById('envio-costo').textContent = envio === 0 ? 'Gratis' : `${Number(envio).toFixed(2)} €`;
        document.getElementById('total').textContent = `${Number(total).toFixed(2)} €`;

        const descuentoLinea = document.getElementById('descuento-linea');
        if (descuentoLinea) descuentoLinea.style.display = 'none';
    }

    toggleProcederPago(habilitar) {
        const btn = document.getElementById('proceder-pago');
        if (!btn) return;
        btn.disabled = !habilitar;
    }

    setupCheckout() {
        const btn = document.getElementById('proceder-pago');
        if (!btn) return;

        btn.onclick = () => {
            const cartJson = localStorage.getItem('carrito_circulos_atenea') || '[]';

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/checkout';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cart_json';
            input.value = cartJson;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        };
    }

    mostrarNotificacion(mensaje) {
        if (window.carritoManager && typeof window.carritoManager.mostrarNotificacion === 'function') {
            window.carritoManager.mostrarNotificacion(mensaje);
        }
    }

    resetResumenVacio() {
        this.actualizarResumen(0, 0, 0, 0);
        this.toggleProcederPago(false);

        const carritoVacio = document.getElementById('carrito-vacio');
        if (carritoVacio) {
            carritoVacio.style.display = 'block';
            this.carritoItemsEl.innerHTML = '';
            this.carritoItemsEl.appendChild(carritoVacio);
        }
    }

    setupVaciarCarrito() {
        const btnVaciar = document.getElementById('vaciar-carrito'); // ajusta el id si es otro
        if (!btnVaciar) return;

        btnVaciar.addEventListener('click', (e) => {
            e.preventDefault();
            this.carritoManager.vaciarCarrito();
            this.resetResumenVacio();
            this.mostrarNotificacion('Carrito vaciado');
        });
    }

    resetResumenVacio() {
        this.actualizarResumen(0, 0, 0, 0);
        this.toggleProcederPago(false);

        const carritoVacio = document.getElementById('carrito-vacio');
        if (carritoVacio) {
            carritoVacio.style.display = 'block';
            this.carritoItemsEl.innerHTML = '';
            this.carritoItemsEl.appendChild(carritoVacio);
        }

        const carritoCount = document.getElementById('carrito-count');
        if (carritoCount) carritoCount.textContent = '0 productos';
    }



}

document.addEventListener('DOMContentLoaded', () => {
    // Si por orden de scripts aún no existe, espera un poco
    const boot = () => {
        if (window.carritoManager) {
            window.carritoPagina = new CarritoPagina();
        } else {
            setTimeout(boot, 50);
        }
    };
    boot();
});
s
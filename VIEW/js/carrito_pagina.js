// VIEW/js/carrito_pagina.js
// CarritoPagina v2 (reset resumen inmediato cuando el carrito queda vacío)

class CarritoPagina {
    constructor() {
        this.carritoManager = window.carritoManager;
        this.carritoItemsEl = document.getElementById('carrito-items');
        this.template = document.getElementById('template-carrito-item');

        this.init();
    }

    init() {
        this.bloquearDescuento();
        this.setupDelegation();
        this.setupCheckout();
        this.renderCarrito();
        console.log('CarritoPagina v2 cargado'); // para confirmar que se carga el archivo correcto
    }

    bloquearDescuento() {
        const input = document.getElementById('codigo-descuento');
        const btn = document.getElementById('aplicar-descuento');
        const msg = document.getElementById('descuento-mensaje');
        const linea = document.getElementById('descuento-linea');

        if (input) {
            input.disabled = true;
            input.readOnly = true;
            input.value = '';
            input.placeholder = 'Códigos no disponibles';
        }
        if (btn) btn.disabled = true;
        if (msg) msg.textContent = 'Actualmente no tenemos disponible ningún código de descuento';
        if (linea) linea.style.display = 'none';
    }

    // ---------- Render principal ----------
    renderCarrito() {
        const items = this.carritoManager?.obtenerItemsCarrito?.() || [];

        const carritoCount = document.getElementById('carrito-count');
        if (carritoCount) {
            carritoCount.textContent = `${items.length} producto${items.length !== 1 ? 's' : ''}`;
        }

        // Limpia contenedor
        if (this.carritoItemsEl) this.carritoItemsEl.innerHTML = '';

        // Si está vacío => reset DOM inmediato
        if (!items || items.length === 0) {
            this.resetResumenVacio();
            return;
        }

        // Oculta bloque vacío
        const carritoVacio = document.getElementById('carrito-vacio');
        if (carritoVacio) carritoVacio.style.display = 'none';

        // Pinta items
        items.forEach((item) => {
            const clone = this.template.content.cloneNode(true);
            const row = clone.querySelector('.carrito-item');

            row.dataset.productId = String(item.id);
            row.dataset.productType = String(item.tipo);

            // Imagen
            const img = row.querySelector('.item-imagen img');
            const baseImg = item.tipo === 'other' ? '/VIEW/img/productos/' : '/VIEW/img/libros/';
            img.src = `${baseImg}${item.imagen || ''}`;
            img.alt = item.nombre || '';
            img.onerror = function () {
                this.src = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80';
            };

            // Título
            row.querySelector('.item-titulo').textContent = item.nombre || '';

            // Autor (solo libros)
            const autorEl = row.querySelector('.item-autor');
            if (autorEl) {
                if (item.tipo === 'book' && item.autor) {
                    autorEl.textContent = item.autor;
                    autorEl.style.display = '';
                } else {
                    autorEl.textContent = '';
                    autorEl.style.display = 'none';
                }
            }

            // Precio unitario y total fila
            const precioUnit = Number(item.precio || 0);
            const qty = Number(item.cantidad || 1);

            row.querySelector('.item-precio-unitario').textContent = `${precioUnit.toFixed(2)} € / unidad`;

            const inputCantidad = row.querySelector('.input-cantidad');
            inputCantidad.value = String(qty);

            row.querySelector('.total-precio').textContent = `${(precioUnit * qty).toFixed(2)} €`;

            this.carritoItemsEl.appendChild(clone);
        });

        // Resumen
        this.actualizarResumenTotal();
        this.toggleProcederPago(true);
    }

    // ---------- Delegación de eventos ----------
    setupDelegation() {
        if (!this.carritoItemsEl) return;

        // Clicks (+ / - / eliminar)
        this.carritoItemsEl.addEventListener('click', (e) => {
            const itemEl = e.target.closest('.carrito-item');
            if (!itemEl) return;

            const id = itemEl.dataset.productId;
            const tipo = itemEl.dataset.productType;

            // SUMAR
            if (e.target.closest('.btn-sumar')) {
                e.preventDefault();
                const input = itemEl.querySelector('.input-cantidad');
                const actual = parseInt(input.value, 10) || 1;
                this.carritoManager.actualizarCantidad(id, tipo, actual + 1);
                this.renderCarrito();
                return;
            }

            // RESTAR
            if (e.target.closest('.btn-restar')) {
                e.preventDefault();
                const input = itemEl.querySelector('.input-cantidad');
                const actual = parseInt(input.value, 10) || 1;
                const nueva = actual - 1;

                if (nueva <= 0) this.carritoManager.eliminarDelCarrito(id, tipo);
                else this.carritoManager.actualizarCantidad(id, tipo, nueva);

                this.renderCarrito();
                return;
            }

            // ELIMINAR
            if (e.target.closest('.btn-eliminar-item')) {
                e.preventDefault();
                this.carritoManager.eliminarDelCarrito(id, tipo);
                this.renderCarrito();
                this.mostrarNotificacion('Producto eliminado del carrito');
                return;
            }
        });

        // Cambios manuales del input cantidad
        this.carritoItemsEl.addEventListener('change', (e) => {
            const input = e.target.closest('.input-cantidad');
            if (!input) return;

            const itemEl = e.target.closest('.carrito-item');
            if (!itemEl) return;

            const id = itemEl.dataset.productId;
            const tipo = itemEl.dataset.productType;

            let nueva = parseInt(input.value, 10);

            if (!Number.isFinite(nueva) || nueva <= 0) {
                this.carritoManager.eliminarDelCarrito(id, tipo);
                this.renderCarrito();
                return;
            }

            if (nueva > 99) nueva = 99;
            this.carritoManager.actualizarCantidad(id, tipo, nueva);
            this.renderCarrito();
        });
    }

    // ---------- Resumen ----------
    actualizarResumenTotal() {
        const subtotal = Number(this.carritoManager.obtenerTotalCarrito() || 0);

        // Envío 5.99€, gratis a partir de 50€
        let envio = 5.99;
        if (subtotal >= 50) envio = 0;

        const descuento = 0;
        const total = subtotal + envio - descuento;

        this.setResumenDOM(subtotal, envio, descuento, total);
    }

    // Escribe directamente en el DOM (más fiable que depender de estados intermedios)
    setResumenDOM(subtotal, envio, descuento, total) {
        const elSubtotal = document.getElementById('subtotal');
        const elEnvio = document.getElementById('envio-costo');
        const elDescuentoLinea = document.getElementById('descuento-linea');
        const elDescuentoMonto = document.getElementById('descuento-monto');
        const elTotal = document.getElementById('total');

        if (elSubtotal) elSubtotal.textContent = `${Number(subtotal).toFixed(2)} €`;

        if (elEnvio) {
            if (Number(envio) <= 0) elEnvio.textContent = 'Gratis';
            else elEnvio.textContent = `${Number(envio).toFixed(2)} €`;
        }

        if (elDescuentoLinea) elDescuentoLinea.style.display = 'none';
        if (elDescuentoMonto) elDescuentoMonto.textContent = `-${Number(descuento).toFixed(2)} €`;

        if (elTotal) elTotal.textContent = `${Number(total).toFixed(2)} €`;
    }

    resetResumenVacio() {
        // Resumen a 0, inmediato y explícito
        this.setResumenDOM(0, 0, 0, 0);
        this.toggleProcederPago(false);

        const carritoVacio = document.getElementById('carrito-vacio');
        if (carritoVacio && this.carritoItemsEl) {
            carritoVacio.style.display = 'block';
            this.carritoItemsEl.innerHTML = '';
            this.carritoItemsEl.appendChild(carritoVacio);
        }

        const carritoCount = document.getElementById('carrito-count');
        if (carritoCount) carritoCount.textContent = '0 productos';
    }

    toggleProcederPago(habilitar) {
        const btn = document.getElementById('proceder-pago');
        if (btn) btn.disabled = !habilitar;
    }

    mostrarNotificacion(mensaje) {
        if (window.carritoManager && typeof window.carritoManager.mostrarNotificacion === 'function') {
            window.carritoManager.mostrarNotificacion(mensaje);
        }
    }

    // ---------- Checkout ----------
    setupCheckout() {
        const btn = document.getElementById('proceder-pago');
        if (!btn) return;

        btn.addEventListener('click', () => {
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
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!window.carritoManager) return;
    new CarritoPagina();
});

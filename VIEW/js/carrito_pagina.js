// VIEW/js/carrito_pagina.js
class CarritoPagina {
  constructor() {
    this.carritoManager = window.carritoManager;
    this.carritoItemsEl = document.getElementById('carrito-items');
    this.template = document.getElementById('template-carrito-item');

    if (!this.carritoManager || !this.carritoItemsEl || !this.template) return;

    this.init();
  }

  init() {
    this.bloquearDescuento();
    this.setupDelegation();
    this.setupCheckout();
    this.renderCarrito();
  }

  bloquearDescuento() {
    const input = document.getElementById('codigo-descuento');
    const btn = document.getElementById('aplicar-descuento');
    const msg = document.getElementById('descuento-mensaje');
    const linea = document.getElementById('descuento-linea');

    if (input) {
      input.value = '';
      input.disabled = true;
      input.readOnly = true;
      input.placeholder = 'Códigos no disponibles';
    }
    if (btn) btn.disabled = true;
    if (msg) msg.textContent = 'Actualmente no tenemos disponible ningún código de descuento';
    if (linea) linea.style.display = 'none';
  }

  normalizarStock(stock) {
    if (stock === undefined || stock === null || stock === '') return null;
    const n = Number(stock);
    if (!Number.isFinite(n) || n < 0) return null;
    return Math.floor(n);
  }

  renderCarrito() {
    const items = this.carritoManager.obtenerItemsCarrito() || [];

    const carritoCount = document.getElementById('carrito-count');
    if (carritoCount) carritoCount.textContent = `${items.length} producto${items.length !== 1 ? 's' : ''}`;

    this.carritoItemsEl.innerHTML = '';

    if (items.length === 0) {
      this.resetResumenVacio();
      return;
    }

    const carritoVacio = document.getElementById('carrito-vacio');
    if (carritoVacio) carritoVacio.style.display = 'none';

    // Si algún item tiene stock=0, lo quitamos (por coherencia)
    let huboLimpieza = false;
    items.slice().forEach((it) => {
      const st = this.normalizarStock(it.stock);
      if (st !== null && st <= 0) {
        this.carritoManager.eliminarDelCarrito(it.id, it.tipo);
        huboLimpieza = true;
      }
    });
    if (huboLimpieza) {
      this.renderCarrito();
      return;
    }

    items.forEach((item) => {
      const clone = this.template.content.cloneNode(true);
      const row = clone.querySelector('.carrito-item');

      row.dataset.productId = String(item.id);
      row.dataset.productType = String(item.tipo);

      const img = row.querySelector('.item-imagen img');
      const baseImg = item.tipo === 'other' ? '/VIEW/img/productos/' : '/VIEW/img/libros/';
      img.src = `${baseImg}${item.imagen || ''}`;
      img.alt = item.nombre || '';
      img.onerror = function () {
        this.src = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=500&q=80';
      };

      row.querySelector('.item-titulo').textContent = item.nombre || '';

      const autorEl = row.querySelector('.item-autor');
      if (autorEl) {
        if (item.tipo === 'book') {
          autorEl.style.display = '';
          autorEl.textContent = item.autor ? `por ${item.autor}` : ' ';
        } else {
          autorEl.style.display = 'none';
          autorEl.textContent = '';
        }
      }

      const precio = Number(item.precio) || 0;
      const stock = this.normalizarStock(item.stock);
      let cantidad = Number(item.cantidad) || 1;

      // Clamp por stock (si existe)
      if (stock !== null) cantidad = Math.min(cantidad, stock);

      row.querySelector('.item-precio-unitario').textContent = `${precio.toFixed(2)} € c/u`;

      const input = row.querySelector('.input-cantidad');
      input.value = String(cantidad);

      // Importante: max real según stock
      if (stock !== null) {
        input.max = String(stock);
        input.title = `Máximo disponible: ${stock}`;
      } else {
        input.max = '99';
        input.title = '';
      }

      // Deshabilitar + si ya está al máximo
      const btnSumar = row.querySelector('.btn-sumar');
      if (btnSumar && stock !== null) {
        btnSumar.disabled = cantidad >= stock;
        btnSumar.style.opacity = btnSumar.disabled ? '0.5' : '';
        btnSumar.style.cursor = btnSumar.disabled ? 'not-allowed' : '';
      }

      row.querySelector('.total-precio').textContent = `${(precio * cantidad).toFixed(2)} €`;

      this.carritoItemsEl.appendChild(clone);

      // Persistimos clamp si ha cambiado
      if (cantidad !== (Number(item.cantidad) || 1)) {
        this.carritoManager.actualizarCantidad(item.id, item.tipo, cantidad);
      }
    });

    this.actualizarResumenTotal();
    this.toggleProcederPago(true);
  }

  setupDelegation() {
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

        // Respeta max del input (stock)
        const max = parseInt(input.max, 10);
        if (Number.isFinite(max) && actual >= max) {
          this.mostrarNotificacion(`No puedes añadir más. Stock máximo: ${max}`);
          return;
        }

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

    // Cambio directo en input cantidad
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

      // Respeta max (stock si lo hay)
      const max = parseInt(input.max, 10);
      if (Number.isFinite(max)) nueva = Math.min(nueva, max);

      nueva = Math.min(99, Math.max(1, nueva));
      this.carritoManager.actualizarCantidad(id, tipo, nueva);
      this.renderCarrito();
    });
  }

  actualizarResumenTotal() {
    const subtotal = Number(this.carritoManager.obtenerTotalCarrito() || 0);
    let envio = 5.99;
    if (subtotal >= 50 || subtotal === 0) envio = 0;

    const total = subtotal + envio;

    const elSubtotal = document.getElementById('subtotal');
    const elEnvio = document.getElementById('envio-costo');
    const elTotal = document.getElementById('total');

    if (elSubtotal) elSubtotal.textContent = `${subtotal.toFixed(2)} €`;
    if (elEnvio) elEnvio.textContent = envio === 0 ? 'Gratis' : `${envio.toFixed(2)} €`;
    if (elTotal) elTotal.textContent = `${total.toFixed(2)} €`;
  }

  resetResumenVacio() {
    const elSubtotal = document.getElementById('subtotal');
    const elEnvio = document.getElementById('envio-costo');
    const elTotal = document.getElementById('total');

    if (elSubtotal) elSubtotal.textContent = '0.00 €';
    if (elEnvio) elEnvio.textContent = 'Gratis';
    if (elTotal) elTotal.textContent = '0.00 €';

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

  toggleProcederPago(habilitar) {
    const btn = document.getElementById('proceder-pago');
    if (btn) btn.disabled = !habilitar;
  }

  mostrarNotificacion(mensaje) {
    if (window.carritoManager && typeof window.carritoManager.mostrarNotificacion === 'function') {
      window.carritoManager.mostrarNotificacion(mensaje);
    }
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
}

document.addEventListener('DOMContentLoaded', () => {
  const boot = () => {
    if (window.carritoManager) new CarritoPagina();
    else setTimeout(boot, 50);
  };
  boot();
});

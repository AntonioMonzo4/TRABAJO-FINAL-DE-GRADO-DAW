// VIEW/js/carrito_pagina.js - Funcionalidades para la página del carrito

class CarritoPagina {
    constructor() {
        this.carritoManager = window.carritoManager;
        this.init();
    }

    init() {
        this.renderCarrito();
        this.setupEventListeners();
        this.cargarProductosRecomendados();
    }

    // Renderizar el carrito
    renderCarrito() {
        const items = this.carritoManager.obtenerItemsCarrito();
        const carritoItems = document.getElementById('carrito-items');
        const carritoVacio = document.getElementById('carrito-vacio');
        const carritoCount = document.getElementById('carrito-count');
        
        // Actualizar contador
        carritoCount.textContent = `${items.length} producto${items.length !== 1 ? 's' : ''}`;
        
        if (items.length === 0) {
            carritoVacio.style.display = 'block';
            carritoItems.innerHTML = '';
            carritoItems.appendChild(carritoVacio);
            this.actualizarResumen(0, 0, 0);
            this.toggleProcederPago(false);
            return;
        }

        carritoVacio.style.display = 'none';
        
        // Limpiar items existentes
        carritoItems.innerHTML = '';
        
        // Renderizar cada item
        items.forEach((item, index) => {
            const itemElement = this.crearItemCarrito(item, index);
            carritoItems.appendChild(itemElement);
        });

        this.actualizarResumenTotal();
    }

    // Crear elemento de item del carrito
    crearItemCarrito(item, index) {
        const template = document.getElementById('template-carrito-item');
        const clone = template.content.cloneNode(true);
        const itemElement = clone.querySelector('.carrito-item');
        
        // Configurar datos
        itemElement.setAttribute('data-product-id', item.id);
        itemElement.setAttribute('data-product-type', item.tipo);
        
        // Imagen
        const img = itemElement.querySelector('.item-imagen img');
        const baseImg = item.tipo === 'other' ? '/VIEW/img/productos/' : '/VIEW/img/libros/';
        img.src = `${baseImg}${item.imagen}`;
        img.alt = item.nombre;
        img.onerror = function() {
            this.src = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';
        };
        
        // Información
        itemElement.querySelector('.item-titulo').textContent = item.nombre;
        const autorEl = itemElement.querySelector('.item-autor');
        if (item.tipo === 'book') {
            autorEl.textContent = `por ${item.autor || 'Autor desconocido'}`;
            autorEl.style.display = '';
        } else {
            autorEl.textContent = '';
            autorEl.style.display = 'none';
        }

        itemElement.querySelector('.item-precio-unitario').textContent = `${item.precio.toFixed(2)} € c/u`;
        
        // Cantidad
        const inputCantidad = itemElement.querySelector('.input-cantidad');
        inputCantidad.value = item.cantidad;
        inputCantidad.id = `cantidad-${index}`;
        
        // Total
        const total = item.precio * item.cantidad;
        itemElement.querySelector('.total-precio').textContent = `${total.toFixed(2)} €`;
        
        // Event listeners para controles de cantidad
        const btnRestar = itemElement.querySelector('.btn-restar');
        const btnSumar = itemElement.querySelector('.btn-sumar');
        
        btnRestar.addEventListener('click', () => this.actualizarCantidad(item.id, item.tipo, item.cantidad - 1));
        btnSumar.addEventListener('click', () => this.actualizarCantidad(item.id, item.tipo, item.cantidad + 1));
        
        inputCantidad.addEventListener('change', (e) => {
            const nuevaCantidad = parseInt(e.target.value);
            if (nuevaCantidad > 0 && nuevaCantidad <= 99) {
                this.actualizarCantidad(item.id, item.tipo, nuevaCantidad);
            } else {
                e.target.value = item.cantidad;
            }
        });
        
        // Event listeners para eliminar
        const btnsEliminar = itemElement.querySelectorAll('.btn-eliminar-item');
        btnsEliminar.forEach(btn => {
            btn.addEventListener('click', () => this.eliminarItem(item.id, item.tipo));
        });
        
        return itemElement;
    }

    // Actualizar cantidad de un item
    actualizarCantidad(id, tipo, nuevaCantidad) {
        if (nuevaCantidad < 1) nuevaCantidad = 1;
        if (nuevaCantidad > 99) nuevaCantidad = 99;
        
        this.carritoManager.actualizarCantidad(id, tipo, nuevaCantidad);
        this.renderCarrito();
        
        // Mostrar feedback visual
        const itemElement = document.querySelector(`[data-product-id="${id}"][data-product-type="${tipo}"]`);
        if (itemElement) {
            itemElement.classList.add('actualizando');
            setTimeout(() => {
                itemElement.classList.remove('actualizando');
            }, 300);
        }
    }

    // Eliminar item del carrito
    eliminarItem(id, tipo) {
        const itemElement = document.querySelector(`[data-product-id="${id}"][data-product-type="${tipo}"]`);
        
        if (itemElement) {
            itemElement.classList.add('eliminando');
            setTimeout(() => {
                this.carritoManager.eliminarDelCarrito(id, tipo);
                this.renderCarrito();
                this.mostrarNotificacion('Producto eliminado del carrito');
            }, 300);
        }
    }

    // Actualizar resumen del pedido
    actualizarResumenTotal() {
        const items = this.carritoManager.obtenerItemsCarrito();
        const subtotal = this.carritoManager.obtenerTotalCarrito();
        
        // Calcular envío (gratis para compras > 50€)
        let envio = 5.99;
        if (subtotal >= 50 || subtotal === 0) {
            envio = 0;
        }
        
        // Calcular descuento (si existe)
        const descuento = this.obtenerDescuentoAplicado();
        const total = subtotal + envio - descuento;
        
        this.actualizarResumen(subtotal, envio, descuento, total);
        this.toggleProcederPago(items.length > 0);
    }

    actualizarResumen(subtotal, envio, descuento, total = null) {
        if (total === null) {
            total = subtotal + envio - descuento;
        }
        
        document.getElementById('subtotal').textContent = `${subtotal.toFixed(2)} €`;
        document.getElementById('envio-costo').textContent = envio === 0 ? 'Gratis' : `${envio.toFixed(2)} €`;
        
        const descuentoLinea = document.getElementById('descuento-linea');
        const descuentoMonto = document.getElementById('descuento-monto');
        
        if (descuento > 0) {
            descuentoLinea.style.display = 'flex';
            descuentoMonto.textContent = `-${descuento.toFixed(2)} €`;
        } else {
            descuentoLinea.style.display = 'none';
        }
        
        document.getElementById('total').textContent = `${total.toFixed(2)} €`;
    }

    // Obtener descuento aplicado
    obtenerDescuentoAplicado() {
        // Simulación de descuento del 10% para compras > 100€
        const subtotal = this.carritoManager.obtenerTotalCarrito();
        return subtotal > 100 ? subtotal * 0.1 : 0;
    }

    // Habilitar/deshabilitar botón de proceder al pago
    toggleProcederPago(habilitar) {
        const btnProcederPago = document.getElementById('proceder-pago');
        if (btnProcederPago) {
            btnProcederPago.disabled = !habilitar;
            if (habilitar) {
                btnProcederPago.addEventListener('click', () => this.procederAlPago());
            } else {
                btnProcederPago.removeEventListener('click', () => this.procederAlPago());
            }
        }
    }

    // Proceder al pago
    procederAlPago() {
        window.location.href = '../index.php?pagina=checkout';
    }

    // Cargar productos recomendados
    async cargarProductosRecomendados() {
        try {
            const productosRecomendados = [
                { id: 1, titulo: 'El Resplandor', autor: 'Stephen King', precio: 15.99, imagen: 'el-resplandor.png' },
                { id: 2, titulo: 'Cien años de soledad', autor: 'Gabriel García Márquez', precio: 19.99, imagen: 'cien-anos-de-soledad.png' },
                { id: 3, titulo: '1984', autor: 'George Orwell', precio: 12.99, imagen: '1984.png' },
                { id: 4, titulo: 'Don Quijote', autor: 'Miguel de Cervantes', precio: 22.50, imagen: 'don-quijote.png' }
            ];

            this.renderProductosRecomendados(productosRecomendados);
        } catch (error) {
            console.error('Error al cargar productos recomendados:', error);
        }
    }

    // Renderizar productos recomendados
    renderProductosRecomendados(productos) {
        const gridRecomendados = document.getElementById('grid-recomendados');
        if (!gridRecomendados) return;

        gridRecomendados.innerHTML = '';

        productos.forEach(producto => {
            const template = document.getElementById('template-producto-recomendado');
            const clone = template.content.cloneNode(true);
            const productoElement = clone.querySelector('.producto-recomendado');

            const img = productoElement.querySelector('img');
            img.src = `/VIEW/img/libros/${producto.imagen}`;
            img.alt = producto.titulo;
            img.onerror = function() {
                this.src = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';
            };

            productoElement.querySelector('.producto-titulo').textContent = producto.titulo;
            productoElement.querySelector('.producto-autor').textContent = producto.autor;
            productoElement.querySelector('.producto-precio strong').textContent = `${producto.precio.toFixed(2)} €`;

            const btnAnadir = productoElement.querySelector('.btn-anadir-carrito-recomendado');
            btnAnadir.setAttribute('data-book-id', producto.id);
            btnAnadir.setAttribute('data-book-titulo', producto.titulo);
            btnAnadir.setAttribute('data-book-precio', producto.precio);
            btnAnadir.setAttribute('data-book-imagen', producto.imagen);

            btnAnadir.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const productoData = {
                    id: producto.id,
                    tipo: 'book',
                    nombre: producto.titulo,
                    autor: producto.autor,
                    precio: producto.precio,
                    imagen: producto.imagen
                };

                this.carritoManager.agregarAlCarrito(productoData);
                this.renderCarrito();
            });

            gridRecomendados.appendChild(productoElement);
        });
    }

    // Configurar event listeners
    setupEventListeners() {
        const btnAplicarDescuento = document.getElementById('aplicar-descuento');
        const inputDescuento = document.getElementById('codigo-descuento');

        if (btnAplicarDescuento) {
            btnAplicarDescuento.addEventListener('click', () => {
                const codigo = inputDescuento.value.trim();
                this.aplicarDescuento(codigo);
            });

            inputDescuento.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.aplicarDescuento(inputDescuento.value.trim());
                }
            });
        }
    }

    // Aplicar código de descuento
    aplicarDescuento(codigo) {
        const descuentos = {
            'BIENVENIDA10': 0.1,
            'LIBRO15': 0.15,
            'ENVIOGRATIS': 5.99
        };

        if (!codigo) {
            this.mostrarMensajeDescuento('Por favor, ingresa un código de descuento', 'error');
            return;
        }

        const descuento = descuentos[codigo.toUpperCase()];

        if (descuento) {
            this.mostrarMensajeDescuento('¡Código de descuento aplicado correctamente!', 'success');
            setTimeout(() => {
                this.actualizarResumenTotal();
            }, 500);
        } else {
            this.mostrarMensajeDescuento('Código de descuento no válido', 'error');
        }
    }

    mostrarMensajeDescuento(mensaje, tipo) {
        const mensajeDescuento = document.getElementById('descuento-mensaje');
        if (!mensajeDescuento) return;

        mensajeDescuento.textContent = mensaje;
        mensajeDescuento.className = 'descuento-mensaje';
        mensajeDescuento.classList.add(tipo);

        setTimeout(() => {
            mensajeDescuento.textContent = '';
            mensajeDescuento.className = 'descuento-mensaje';
        }, 5000);
    }

    // Mostrar notificación
    mostrarNotificacion(mensaje) {
        if (window.carritoManager && typeof window.carritoManager.mostrarNotificacion === 'function') {
            window.carritoManager.mostrarNotificacion(mensaje);
        }
    }
}

// Inicializar página del carrito
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.carritoManager !== 'undefined') {
        window.carritoPagina = new CarritoPagina();
    } else {
        setTimeout(() => {
            if (typeof window.carritoManager !== 'undefined') {
                window.carritoPagina = new CarritoPagina();
            } else {
                console.error('CarritoManager no está disponible');
            }
        }, 1000);
    }
});

// Actualizar el carrito cuando cambie desde otras páginas
window.addEventListener('storage', function(e) {
    if (e.key === 'carrito_circulos_atenea' && window.carritoPagina) {
        window.carritoPagina.renderCarrito();
    }
});

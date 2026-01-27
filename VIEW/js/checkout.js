// VIEW/js/checkout.js - Funcionalidades para la página de checkout

class CheckoutManager {
    constructor() {
        this.carritoManager = window.carritoManager;
        this.init();
    }

    init() {
        this.renderResumen();
        this.setupEventListeners();
        this.calculateShipping();
    }

    // Renderizar resumen del pedido
    renderResumen() {
        const items = this.carritoManager.obtenerItemsCarrito();
        const resumenItems = document.getElementById('resumen-items');
        
        resumenItems.innerHTML = '';

        if (items.length === 0) {
            resumenItems.innerHTML = `
                <div class="carrito-vacio">
                    <p>No hay productos en el carrito</p>
                    <a href="../index.php?pagina=categoriaTienda" class="btn btn-primary">
                        Continuar Comprando
                    </a>
                </div>
            `;
            this.updateSummary(0, 0, 0);
            return;
        }

        let subtotal = 0;

        items.forEach(item => {
            const template = document.getElementById('template-resumen-item');
            const clone = template.content.cloneNode(true);
            const itemElement = clone.querySelector('.resumen-item');

            // Imagen
            const img = itemElement.querySelector('.item-imagen img');
            img.src = `img/libros/${item.imagen}`;
            img.alt = item.nombre;
            img.onerror = function() {
                this.src = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80';
            };

            // Información
            itemElement.querySelector('.item-titulo').textContent = item.nombre;
            itemElement.querySelector('.item-cantidad').textContent = `Cantidad: ${item.cantidad}`;

            // Precio
            const totalItem = item.precio * item.cantidad;
            itemElement.querySelector('.item-precio span').textContent = `$${totalItem.toFixed(2)}`;

            subtotal += totalItem;
            resumenItems.appendChild(itemElement);
        });

        this.updateSummaryTotal();
    }

    // Calcular envío
    calculateShipping() {
        const subtotal = this.carritoManager.obtenerTotalCarrito();
        const envioEstandar = document.getElementById('envio-estandar');
        const envioExpress = document.getElementById('envio-express');
        
        // Envío gratis para compras > $50
        if (subtotal >= 50) {
            document.querySelector('.envio-precio').textContent = 'Gratis';
            if (envioExpress) {
                envioExpress.querySelector('.envio-precio').textContent = '$9.99';
            }
        } else {
            document.querySelector('.envio-precio').textContent = '$5.99';
            if (envioExpress) {
                envioExpress.querySelector('.envio-precio').textContent = '$9.99';
            }
        }
    }

    // Actualizar resumen total
    updateSummaryTotal() {
        const items = this.carritoManager.obtenerItemsCarrito();
        const subtotal = this.carritoManager.obtenerTotalCarrito();
        
        // Calcular envío
        let envio = 0;
        const envioEstandar = document.getElementById('envio-estandar');
        const envioExpress = document.getElementById('envio-express');
        const envioRecogida = document.getElementById('envio-recogida');

        if (envioEstandar && envioEstandar.checked) {
            envio = subtotal >= 50 ? 0 : 5.99;
        } else if (envioExpress && envioExpress.checked) {
            envio = 9.99;
        } else if (envioRecogida && envioRecogida.checked) {
            envio = 0;
        }

        // Calcular descuento
        const descuento = this.getAppliedDiscount();
        const total = subtotal + envio - descuento;

        this.updateSummary(subtotal, envio, descuento, total);
    }

    // Actualizar valores del resumen
    updateSummary(subtotal, envio, descuento, total = null) {
        if (total === null) {
            total = subtotal + envio - descuento;
        }
        
        document.getElementById('resumen-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('resumen-envio').textContent = envio === 0 ? 'Gratis' : `$${envio.toFixed(2)}`;
        
        const descuentoLinea = document.getElementById('resumen-descuento-linea');
        const descuentoMonto = document.getElementById('resumen-descuento');
        
        if (descuento > 0) {
            descuentoLinea.style.display = 'flex';
            descuentoMonto.textContent = `-$${descuento.toFixed(2)}`;
        } else {
            descuentoLinea.style.display = 'none';
        }
        
        document.getElementById('resumen-total').textContent = `$${total.toFixed(2)}`;
    }

    // Obtener descuento aplicado
    getAppliedDiscount() {
        // En una implementación real, esto vendría del código de descuento
        return 0;
    }

    // Configurar event listeners
    setupEventListeners() {
        // Métodos de envío
        const metodosEnvio = document.querySelectorAll('input[name="metodo_envio"]');
        metodosEnvio.forEach(radio => {
            radio.addEventListener('change', () => {
                this.updateSummaryTotal();
            });
        });

        // Métodos de pago
        const metodosPago = document.querySelectorAll('input[name="metodo_pago"]');
        metodosPago.forEach(radio => {
            radio.addEventListener('change', this.togglePaymentMethod.bind(this));
        });

        // Código de descuento
        const btnAplicarDescuento = document.getElementById('aplicar-descuento-checkout');
        if (btnAplicarDescuento) {
            btnAplicarDescuento.addEventListener('click', this.applyDiscount.bind(this));
        }

        // Confirmar pedido
        const btnConfirmar = document.getElementById('confirmar-pedido');
        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', this.confirmOrder.bind(this));
        }

        // Validación de formularios en tiempo real
        this.setupFormValidation();
    }

    // Alternar entre métodos de pago
    togglePaymentMethod() {
        const formTarjeta = document.getElementById('form-tarjeta');
        const infoPaypal = document.getElementById('info-paypal');
        const infoTransferencia = document.getElementById('info-transferencia');

        formTarjeta.style.display = 'none';
        infoPaypal.style.display = 'none';
        infoTransferencia.style.display = 'none';

        const metodoSeleccionado = document.querySelector('input[name="metodo_pago"]:checked').value;

        switch (metodoSeleccionado) {
            case 'tarjeta':
                formTarjeta.style.display = 'block';
                break;
            case 'paypal':
                infoPaypal.style.display = 'block';
                break;
            case 'transferencia':
                infoTransferencia.style.display = 'block';
                break;
        }
    }

    // Aplicar descuento
    applyDiscount() {
        const inputDescuento = document.getElementById('codigo-descuento-checkout');
        const mensajeDescuento = document.getElementById('descuento-mensaje-checkout');
        const codigo = inputDescuento.value.trim();

        // Simulación de códigos de descuento
        const descuentos = {
            'BIENVENIDA10': 0.1,  // 10% de descuento
            'LIBRO15': 0.15,      // 15% de descuento
            'ENVIOGRATIS': 5.99   // Envío gratis
        };

        if (!codigo) {
            this.showDiscountMessage('Por favor, ingresa un código de descuento', 'error');
            return;
        }

        const descuento = descuentos[codigo.toUpperCase()];

        if (descuento) {
            this.showDiscountMessage('¡Código de descuento aplicado correctamente!', 'success');
            // En una implementación real, guardaríamos el descuento aplicado
            setTimeout(() => {
                this.updateSummaryTotal();
            }, 500);
        } else {
            this.showDiscountMessage('Código de descuento no válido', 'error');
        }
    }

    // Mostrar mensaje de descuento
    showDiscountMessage(mensaje, tipo) {
        const mensajeDescuento = document.getElementById('descuento-mensaje-checkout');
        mensajeDescuento.textContent = mensaje;
        mensajeDescuento.className = 'descuento-mensaje';
        mensajeDescuento.classList.add(tipo);
        
        setTimeout(() => {
            mensajeDescuento.textContent = '';
            mensajeDescuento.className = 'descuento-mensaje';
        }, 5000);
    }

    // Configurar validación de formularios
    setupFormValidation() {
        const form = document.getElementById('form-envio');
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', this.validateField.bind(this));
            input.addEventListener('input', this.clearFieldError.bind(this));
        });

        // Validación de tarjeta
        const numeroTarjeta = document.getElementById('numero_tarjeta');
        const fechaExpiracion = document.getElementById('fecha_expiracion');
        const cvv = document.getElementById('cvv');

        if (numeroTarjeta) {
            numeroTarjeta.addEventListener('input', this.formatCardNumber.bind(this));
        }

        if (fechaExpiracion) {
            fechaExpiracion.addEventListener('input', this.formatExpirationDate.bind(this));
        }

        if (cvv) {
            cvv.addEventListener('input', this.formatCVV.bind(this));
        }
    }

    // Validar campo individual
    validateField(e) {
        const field = e.target;
        this.clearFieldError({ target: field });

        if (!field.value.trim()) {
            this.showFieldError(field, 'Este campo es obligatorio');
            return false;
        }

        // Validaciones específicas por tipo de campo
        switch (field.type) {
            case 'email':
                if (!this.isValidEmail(field.value)) {
                    this.showFieldError(field, 'Por favor, ingresa un email válido');
                    return false;
                }
                break;
            case 'tel':
                if (!this.isValidPhone(field.value)) {
                    this.showFieldError(field, 'Por favor, ingresa un teléfono válido');
                    return false;
                }
                break;
        }

        return true;
    }

    // Mostrar error en campo
    showFieldError(field, message) {
        field.classList.add('error');
        
        let errorElement = field.parentNode.querySelector('.error-message');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            field.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }

    // Limpiar error del campo
    clearFieldError(e) {
        const field = e.target;
        field.classList.remove('error');
        
        const errorElement = field.parentNode.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Validaciones
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,9}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    // Formatear número de tarjeta
    formatCardNumber(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    }

    // Formatear fecha de expiración
    formatExpirationDate(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        
        e.target.value = value;
    }

    // Formatear CVV
    formatCVV(e) {
        e.target.value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '').substring(0, 3);
    }

    // Confirmar pedido
    async confirmOrder() {
        const btnConfirmar = document.getElementById('confirmar-pedido');
        
        // Validar formulario de envío
        if (!this.validateShippingForm()) {
            this.showNotification('Por favor, completa todos los campos obligatorios', 'error');
            return;
        }

        // Validar términos y condiciones
        if (!document.getElementById('acepto_terminos').checked) {
            this.showNotification('Debes aceptar los términos y condiciones', 'error');
            return;
        }

        // Validar método de pago
        if (!this.validatePaymentMethod()) {
            return;
        }

        // Mostrar estado de carga
        btnConfirmar.classList.add('loading');
        btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        btnConfirmar.disabled = true;

        try {
            // Simular procesamiento del pago
            await this.processPayment();
            
            // Crear pedido
            const orderId = await this.createOrder();
            
            // Limpiar carrito
            this.carritoManager.vaciarCarrito();
            
            // Mostrar éxito y redirigir
            this.showNotification('¡Pedido realizado con éxito! Redirigiendo...', 'success');
            
            setTimeout(() => {
                window.location.href = `../index.php?pagina=confirmacion_pedido&order_id=${orderId}`;
            }, 2000);
            
        } catch (error) {
            console.error('Error al procesar el pedido:', error);
            this.showNotification('Error al procesar el pedido. Inténtalo de nuevo.', 'error');
            
            // Restaurar botón
            btnConfirmar.classList.remove('loading');
            btnConfirmar.innerHTML = '<i class="fas fa-lock"></i> Confirmar y Pagar';
            btnConfirmar.disabled = false;
        }
    }

    // Validar formulario de envío
    validateShippingForm() {
        const form = document.getElementById('form-envio');
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField({ target: field })) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Validar método de pago
    validatePaymentMethod() {
        const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;

        switch (metodoPago) {
            case 'tarjeta':
                return this.validateCardForm();
            case 'paypal':
            case 'transferencia':
                return true; // No necesita validación adicional
            default:
                return false;
        }
    }

    // Validar formulario de tarjeta
    validateCardForm() {
        const numeroTarjeta = document.getElementById('numero_tarjeta').value.replace(/\s/g, '');
        const fechaExpiracion = document.getElementById('fecha_expiracion').value;
        const cvv = document.getElementById('cvv').value;
        const nombreTarjeta = document.getElementById('nombre_tarjeta').value;

        let isValid = true;

        if (numeroTarjeta.length !== 16 || !/^\d+$/.test(numeroTarjeta)) {
            this.showFieldError(document.getElementById('numero_tarjeta'), 'Número de tarjeta inválido');
            isValid = false;
        }

        if (!/^\d{2}\/\d{2}$/.test(fechaExpiracion)) {
            this.showFieldError(document.getElementById('fecha_expiracion'), 'Fecha de expiración inválida');
            isValid = false;
        }

        if (cvv.length !== 3 || !/^\d+$/.test(cvv)) {
            this.showFieldError(document.getElementById('cvv'), 'CVV inválido');
            isValid = false;
        }

        if (!nombreTarjeta.trim()) {
            this.showFieldError(document.getElementById('nombre_tarjeta'), 'Nombre en la tarjeta es obligatorio');
            isValid = false;
        }

        return isValid;
    }

    // Procesar pago (simulación)
    async processPayment() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simular éxito del pago (90% de probabilidad)
                if (Math.random() < 0.9) {
                    resolve();
                } else {
                    reject(new Error('Pago rechazado'));
                }
            }, 3000);
        });
    }

    // Crear pedido (simulación)
    async createOrder() {
        return new Promise((resolve) => {
            setTimeout(() => {
                const orderId = 'ORD-' + Date.now();
                resolve(orderId);
            }, 1000);
        });
    }

    // Mostrar notificación
    showNotification(mensaje, tipo) {
        if (typeof this.carritoManager.mostrarNotificacion === 'function') {
            this.carritoManager.mostrarNotificacion(mensaje);
        } else {
            // Notificación básica
            const notificacion = document.createElement('div');
            notificacion.className = `notificacion ${tipo}`;
            notificacion.textContent = mensaje;
            notificacion.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${tipo === 'error' ? '#e74c3c' : '#2ecc71'};
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            document.body.appendChild(notificacion);

            setTimeout(() => {
                notificacion.remove();
            }, 5000);
        }
    }
}

// Inicializar checkout cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.carritoManager !== 'undefined') {
        window.checkoutManager = new CheckoutManager();
    } else {
        setTimeout(() => {
            if (typeof window.carritoManager !== 'undefined') {
                window.checkoutManager = new CheckoutManager();
            } else {
                console.error('CarritoManager no está disponible');
            }
        }, 1000);
    }
});
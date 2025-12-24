// VIEW/js/confirmacion_pedido.js - Funcionalidades para la página de confirmación

class ConfirmacionPedido {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.startOrderTracking();
        this.setupPrintFunctionality();
    }

    // Configurar event listeners
    setupEventListeners() {
        // Botón de descarga de factura
        const btnDescargarFactura = document.getElementById('descargar-factura');
        if (btnDescargarFactura) {
            btnDescargarFactura.addEventListener('click', this.descargarFactura.bind(this));
        }

        // Botones de acción rápida
        this.setupQuickActions();

        // Efectos visuales
        this.setupVisualEffects();
    }

    // Simular seguimiento del pedido
    startOrderTracking() {
        // Simular actualización del estado del pedido
        setTimeout(() => {
            this.updateOrderStatus('preparando');
        }, 5000);

        setTimeout(() => {
            this.updateOrderStatus('en_camino');
        }, 15000);

        setTimeout(() => {
            this.updateOrderStatus('entregado');
        }, 30000);
    }

    // Actualizar estado del pedido
    updateOrderStatus(nuevoEstado) {
        const estados = {
            'confirmado': { texto: 'Confirmado', clase: 'confirmado' },
            'preparando': { texto: 'Preparando', clase: 'activo' },
            'en_camino': { texto: 'En Camino', clase: 'activo' },
            'entregado': { texto: 'Entregado', clase: 'completado' }
        };

        const estadoElement = document.querySelector('.estado-pedido');
        const pasos = document.querySelectorAll('.paso-item');

        if (estadoElement && estados[nuevoEstado]) {
            estadoElement.textContent = estados[nuevoEstado].texto;
            estadoElement.className = `estado-pedido ${estados[nuevoEstado].clase}`;
        }

        // Actualizar pasos
        pasos.forEach((paso, index) => {
            paso.classList.remove('completado', 'activo');
            
            if (nuevoEstado === 'preparando' && index === 1) {
                paso.classList.add('activo');
            } else if (nuevoEstado === 'en_camino' && index === 2) {
                paso.classList.add('activo');
            } else if (nuevoEstado === 'entregado' && index === 3) {
                paso.classList.add('completado');
            }
        });

        // Mostrar notificación de actualización
        if (nuevoEstado !== 'confirmado') {
            this.mostrarNotificacion(`Estado del pedido actualizado: ${estados[nuevoEstado].texto}`);
        }
    }

    // Configurar funcionalidad de impresión
    setupPrintFunctionality() {
        // Agregar botón de impresión si no existe
        if (!document.getElementById('imprimir-pedido')) {
            const btnImprimir = document.createElement('button');
            btnImprimir.id = 'imprimir-pedido';
            btnImprimir.className = 'btn btn-outline';
            btnImprimir.innerHTML = '<i class="fas fa-print"></i> Imprimir Resumen';
            btnImprimir.addEventListener('click', this.imprimirResumen.bind(this));
            
            const acciones = document.querySelector('.botones-accion');
            if (acciones) {
                acciones.appendChild(btnImprimir);
            }
        }
    }

    // Descargar factura (simulación)
    descargarFactura() {
        const btn = document.getElementById('descargar-factura');
        const originalText = btn.innerHTML;
        
        // Simular descarga
        btn.classList.add('loading');
        btn.disabled = true;
        
        setTimeout(() => {
            // Crear PDF simulado (en una implementación real, esto generaría un PDF real)
            this.generarPDFFactura();
            
            btn.classList.remove('loading');
            btn.disabled = false;
            btn.innerHTML = originalText;
            
            this.mostrarNotificacion('Factura descargada correctamente', 'success');
        }, 2000);
    }

    // Generar PDF de factura (simulación)
    generarPDFFactura() {
        const orderId = document.querySelector('.order-id').textContent;
        const fecha = new Date().toLocaleDateString('es-ES');
        
        // En una implementación real, aquí se generaría un PDF
        // Por ahora, simulamos la descarga
        const blob = new Blob([`Factura del Pedido ${orderId}\nFecha: ${fecha}\n\nGracias por su compra.`], 
                             { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = `factura-${orderId}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Imprimir resumen del pedido
    imprimirResumen() {
        const ventanaImpresion = window.open('', '_blank');
        const contenido = document.querySelector('.resumen-pedido-confirmacion').innerHTML;
        
        ventanaImpresion.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Resumen del Pedido</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .section-title { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
                    .producto-pedido { display: flex; justify-content: space-between; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; }
                    .total-final { border-top: 2px solid #2c3e50; padding-top: 10px; font-weight: bold; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <h1>Resumen del Pedido</h1>
                ${contenido}
                <script>
                    window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 1000); }
                </script>
            </body>
            </html>
        `);
        ventanaImpresion.document.close();
    }

    // Configurar acciones rápidas
    setupQuickActions() {
        // Agregar funcionalidad de compartir pedido
        const btnCompartir = document.createElement('button');
        btnCompartir.className = 'btn btn-outline';
        btnCompartir.innerHTML = '<i class="fas fa-share-alt"></i> Compartir Pedido';
        btnCompartir.addEventListener('click', this.compartirPedido.bind(this));
        
        const acciones = document.querySelector('.botones-accion');
        if (acciones) {
            acciones.appendChild(btnCompartir);
        }
    }

    // Compartir pedido
    compartirPedido() {
        const orderId = document.querySelector('.order-id').textContent;
        const texto = `¡Acabo de realizar mi pedido en Los Círculos de Atenea! Número de pedido: ${orderId}`;
        
        if (navigator.share) {
            navigator.share({
                title: 'Mi Pedido - Los Círculos de Atenea',
                text: texto,
                url: window.location.href
            }).then(() => {
                this.mostrarNotificacion('Pedido compartido correctamente', 'success');
            }).catch(() => {
                this.compartirFallback(texto);
            });
        } else {
            this.compartirFallback(texto);
        }
    }

    // Fallback para compartir
    compartirFallback(texto) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(texto).then(() => {
                this.mostrarNotificacion('Texto copiado al portapapeles', 'success');
            }).catch(() => {
                prompt('Copia este texto para compartir:', texto);
            });
        } else {
            prompt('Copia este texto para compartir:', texto);
        }
    }

    // Configurar efectos visuales
    setupVisualEffects() {
        // Efecto de confeti al cargar la página
        this.lanzarConfeti();
        
        // Animación de los productos
        this.animarProductos();
    }

    // Lanzar efecto de confeti
    lanzarConfeti() {
        const confettiSettings = { target: 'confetti-canvas', max: 150, size: 1.5, animate: true };
        
        // Crear canvas para confeti
        const canvas = document.createElement('canvas');
        canvas.id = 'confetti-canvas';
        canvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        `;
        document.body.appendChild(canvas);

        // Simular confeti simple con CSS
        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                this.crearConfeti();
            }, i * 100);
        }

        // Eliminar canvas después de la animación
        setTimeout(() => {
            canvas.remove();
        }, 5000);
    }

    // Crear partícula de confeti
    crearConfeti() {
        const confeti = document.createElement('div');
        confeti.style.cssText = `
            position: fixed;
            top: -10px;
            left: ${Math.random() * 100}%;
            width: 10px;
            height: 10px;
            background: ${this.getRandomColor()};
            border-radius: 2px;
            pointer-events: none;
            z-index: 9999;
            animation: fall linear forwards;
        `;

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(${Math.random() * 360}deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(confeti);

        setTimeout(() => {
            confeti.remove();
            style.remove();
        }, 5000);
    }

    // Obtener color aleatorio para confeti
    getRandomColor() {
        const colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Animar productos
    animarProductos() {
        const productos = document.querySelectorAll('.producto-pedido');
        productos.forEach((producto, index) => {
            producto.style.animationDelay = `${index * 0.1}s`;
            producto.style.animation = 'slideInUp 0.5s ease forwards';
        });
    }

    // Mostrar notificación
    mostrarNotificacion(mensaje, tipo = 'info') {
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion ${tipo}`;
        notificacion.textContent = mensaje;
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${tipo === 'error' ? '#e74c3c' : tipo === 'success' ? '#2ecc71' : '#3498db'};
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
        `;
        document.body.appendChild(notificacion);

        setTimeout(() => {
            notificacion.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => {
                notificacion.remove();
            }, 300);
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.confirmacionPedido = new ConfirmacionPedido();
});

// Añadir estilos de animación
const animacionStyles = `
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
`;

// Insertar estilos si no existen
if (!document.querySelector('#animacion-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'animacion-styles';
    styleElement.textContent = animacionStyles;
    document.head.appendChild(styleElement);
}
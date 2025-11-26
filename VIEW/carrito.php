<?php
require_once 'header.php';
require_once '../controller/conexion.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<main class="carrito-page">
    <!-- Migas de Pan -->
    <nav class="migas-pan">
        <div class="container">
            <a href="../index.php">Inicio</a> / 
            <a href="../index.php?pagina=categoriaTienda">Tienda</a> / 
            <span>Carrito de Compra</span>
        </div>
    </nav>

    <div class="container">
        <div class="carrito-grid">
            <!-- Lista de Productos en el Carrito -->
            <div class="carrito-items-section">
                <div class="section-header">
                    <h1>Tu Carrito de Compra</h1>
                    <span class="items-count" id="carrito-count">0 productos</span>
                </div>

                <div class="carrito-items" id="carrito-items">
                    <!-- Los productos se cargarán dinámicamente con JavaScript -->
                    <div class="carrito-vacio" id="carrito-vacio">
                        <div class="carrito-vacio-content">
                            <i class="fas fa-shopping-cart"></i>
                            <h2>Tu carrito está vacío</h2>
                            <p>¡Descubre nuestros libros y productos exclusivos!</p>
                            <a href="../index.php?pagina=categoriaTienda" class="btn btn-primary">
                                <i class="fas fa-book"></i>
                                Explorar Tienda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="resumen-pedido-section">
                <div class="resumen-pedido">
                    <h2>Resumen del Pedido</h2>
                    
                    <div class="resumen-detalles">
                        <div class="resumen-linea">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="resumen-linea">
                            <span>Envío:</span>
                            <span id="envio-costo">$0.00</span>
                        </div>
                        <div class="resumen-linea descuento-linea" id="descuento-linea" style="display: none;">
                            <span>Descuento:</span>
                            <span id="descuento-monto">-$0.00</span>
                        </div>
                        <div class="resumen-linea total-linea">
                            <strong>Total:</strong>
                            <strong id="total">$0.00</strong>
                        </div>
                    </div>

                    <!-- Código de Descuento -->
                    <div class="codigo-descuento">
                        <label for="codigo-descuento">¿Tienes un código de descuento?</label>
                        <div class="descuento-input-group">
                            <input type="text" id="codigo-descuento" placeholder="Ingresa tu código">
                            <button type="button" id="aplicar-descuento" class="btn btn-outline">Aplicar</button>
                        </div>
                        <div class="descuento-mensaje" id="descuento-mensaje"></div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="carrito-acciones">
                        <a href="../index.php?pagina=categoriaTienda" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Seguir Comprando
                        </a>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button id="proceder-pago" class="btn btn-primary" disabled>
                                <i class="fas fa-lock"></i>
                                Proceder al Pago
                            </button>
                        <?php else: ?>
                            <div class="login-required">
                                <p>Inicia sesión para proceder con la compra</p>
                                <a href="../index.php?pagina=form_logado" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Iniciar Sesión
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Garantías y Seguridad -->
                    <div class="garantias">
                        <div class="garantia-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Compra 100% Segura</strong>
                                <span>Datos protegidos con encriptación SSL</span>
                            </div>
                        </div>
                        <div class="garantia-item">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Envío Gratis</strong>
                                <span>En pedidos superiores a $50</span>
                            </div>
                        </div>
                        <div class="garantia-item">
                            <i class="fas fa-undo"></i>
                            <div>
                                <strong>Devoluciones</strong>
                                <span>30 días para cambiar de opinión</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Recomendados -->
        <section class="productos-recomendados">
            <h2>También te puede interesar</h2>
            <div class="grid-recomendados" id="grid-recomendados">
                <!-- Los productos recomendados se cargarán dinámicamente -->
            </div>
        </section>
    </div>
</main>

<?php require_once 'footer.php'; ?>

<!-- Template para items del carrito -->
<template id="template-carrito-item">
    <div class="carrito-item" data-product-id="" data-product-type="">
        <div class="item-imagen">
            <img src="" alt="">
        </div>
        <div class="item-info">
            <h3 class="item-titulo"></h3>
            <p class="item-autor"></p>
            <p class="item-precio-unitario"></p>
            <div class="item-acciones-mobile">
                <button class="btn-eliminar-item">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            </div>
        </div>
        <div class="item-cantidad">
            <label for="cantidad-">Cantidad:</label>
            <div class="cantidad-controls">
                <button type="button" class="btn-cantidad btn-restar">-</button>
                <input type="number" class="input-cantidad" value="1" min="1" max="99">
                <button type="button" class="btn-cantidad btn-sumar">+</button>
            </div>
        </div>
        <div class="item-total">
            <span class="total-precio"></span>
        </div>
        <div class="item-acciones">
            <button class="btn-eliminar-item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
</template>

<!-- Template para productos recomendados -->
<template id="template-producto-recomendado">
    <div class="producto-recomendado">
        <a href="../index.php?pagina=detalle_producto&id=" class="producto-link">
            <div class="producto-imagen">
                <img src="" alt="">
            </div>
            <div class="producto-info">
                <h3></h3>
                <p class="producto-autor"></p>
                <p class="producto-precio"></p>
                <button class="btn-anadir-carrito-recomendado" 
                        data-book-id=""
                        data-book-titulo=""
                        data-book-precio=""
                        data-book-imagen="">
                    <i class="fas fa-cart-plus"></i>
                    Añadir al Carrito
                </button>
            </div>
        </a>
    </div>
</template>

<script src="js/carrito_pagina.js"></script>
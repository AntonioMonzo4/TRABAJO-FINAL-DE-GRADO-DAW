<?php
require_once __DIR__ . '/header.php';

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Carrito', 'url' => null]
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">

        <h1>Carrito</h1>

        <p id="carrito-count" class="muted" style="margin: 0 0 20px;"></p>

        <div id="carrito-items">
            <div id="carrito-vacio" style="display:none; padding:20px 0;">
                <p>Tu carrito está vacío.</p>
                <a href="/tienda" class="btn btn-primary">Ir a la tienda</a>
            </div>
        </div>

        <div class="carrito-resumen" style="margin-top: 30px;">
            <h3>Resumen</h3>

            <div class="resumen-linea" style="display:flex;justify-content:space-between;gap:10px;">
                <span>Subtotal</span>
                <span id="subtotal">0.00 €</span>
            </div>

            <div class="resumen-linea" style="display:flex;justify-content:space-between;gap:10px;">
                <span>Envío</span>
                <span id="envio-costo">Gratis</span>
            </div>

            <div id="descuento-linea" class="resumen-linea" style="display:none;justify-content:space-between;gap:10px;">
                <span>Descuento</span>
                <span id="descuento-monto">-0.00 €</span>
            </div>

            <hr style="margin: 12px 0;">

            <div class="resumen-linea" style="display:flex;justify-content:space-between;gap:10px;font-weight:700;">
                <span>Total</span>
                <span id="total">0.00 €</span>
            </div>

            <!-- Descuento visible pero deshabilitado -->
            <div class="descuento" style="margin-top: 15px; display:flex; gap:10px; flex-wrap:wrap;">
                <input id="codigo-descuento" type="text" placeholder="Códigos no disponibles" style="max-width:220px;" disabled readonly>
                <button id="aplicar-descuento" type="button" class="btn btn-secondary" disabled>Aplicar</button>
                <span id="descuento-mensaje" class="descuento-mensaje" style="min-width: 260px;">
                    Actualmente no tenemos disponible ningún código de descuento
                </span>
            </div>

            <div style="margin-top: 20px; display:flex; gap:10px; flex-wrap:wrap;">
                <button id="proceder-pago" type="button" class="btn btn-primary" disabled>Proceder al pago</button>
                <a href="/tienda" class="btn btn-secondary">Seguir comprando</a>
            </div>
        </div>

        <!-- Template item carrito -->
        <template id="template-carrito-item">
            <div class="carrito-item" style="display:flex;gap:15px;align-items:flex-start;border-bottom:1px solid rgba(0,0,0,0.08);padding:15px 0;">
                <div class="item-imagen" style="width:90px;flex:0 0 90px;">
                    <img src="" alt="" style="width:90px;height:90px;object-fit:cover;border-radius:10px;">
                </div>

                <div class="item-info" style="flex:1;min-width: 200px;">
                    <div class="item-titulo" style="font-weight:700;"></div>
                    <div class="item-autor muted" style="font-size:0.95rem;"></div>
                    <div class="item-precio-unitario" style="margin-top:6px;"></div>

                    <div class="item-controles" style="margin-top:12px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <button type="button" class="btn-restar" aria-label="Restar" style="padding:6px 10px;">-</button>
                        <input class="input-cantidad" type="number" min="1" max="99" value="1" style="width:70px;">
                        <button type="button" class="btn-sumar" aria-label="Sumar" style="padding:6px 10px;">+</button>
                        <button type="button" class="btn-eliminar-item btn btn-secondary" style="margin-left:auto;">Eliminar</button>
                    </div>
                </div>

                <div class="item-total" style="min-width:120px;text-align:right;">
                    <div class="muted" style="font-size:0.9rem;">Total</div>
                    <div class="total-precio" style="font-weight:700;"></div>
                </div>
            </div>
        </template>

    </section>
</main>

<script src="/VIEW/js/carrito.js"></script>
<script src="/VIEW/js/carrito_pagina.js"></script>

<?php require_once __DIR__ . '/footer.php'; ?>

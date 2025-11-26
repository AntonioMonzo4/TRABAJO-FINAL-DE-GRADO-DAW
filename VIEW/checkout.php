<?php
require_once 'header.php';
require_once '../controller/conexion.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?pagina=form_logado');
    exit;
}

// Obtener datos del usuario
$user_id = $_SESSION['user_id'];
$usuario = null;

try {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener usuario: " . $e->getMessage());
}

// Si no hay usuario, redirigir
if (!$usuario) {
    header('Location: ../index.php?pagina=form_logado');
    exit;
}
?>

<main class="checkout-page">
    <!-- Migas de Pan -->
    <nav class="migas-pan">
        <div class="container">
            <a href="../index.php">Inicio</a> / 
            <a href="../index.php?pagina=carrito">Carrito</a> / 
            <span>Checkout</span>
        </div>
    </nav>

    <div class="container">
        <h1 class="checkout-title">Finalizar Compra</h1>
        
        <div class="checkout-grid">
            <!-- Formulario de Checkout -->
            <div class="checkout-form-section">
                <!-- Información de Envío -->
                <section class="checkout-section">
                    <h2 class="section-title">
                        <i class="fas fa-truck"></i>
                        Información de Envío
                    </h2>
                    
                    <form id="form-envio" class="checkout-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nombre">Nombre *</label>
                                <input type="text" id="nombre" name="nombre" required 
                                       value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="apellidos">Apellidos *</label>
                                <input type="text" id="apellidos" name="apellidos" required
                                       value="<?php echo htmlspecialchars($usuario['apellidos'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono *</label>
                                <input type="tel" id="telefono" name="telefono" required
                                       value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label for="direccion">Dirección *</label>
                                <input type="text" id="direccion" name="direccion" required
                                       placeholder="Calle, número, piso...">
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad *</label>
                                <input type="text" id="ciudad" name="ciudad" required>
                            </div>
                            <div class="form-group">
                                <label for="codigo_postal">Código Postal *</label>
                                <input type="text" id="codigo_postal" name="codigo_postal" required>
                            </div>
                            <div class="form-group">
                                <label for="provincia">Provincia *</label>
                                <select id="provincia" name="provincia" required>
                                    <option value="">Selecciona una provincia</option>
                                    <option value="madrid">Madrid</option>
                                    <option value="barcelona">Barcelona</option>
                                    <option value="valencia">Valencia</option>
                                    <option value="sevilla">Sevilla</option>
                                    <option value="zaragoza">Zaragoza</option>
                                    <option value="bilbao">Bilbao</option>
                                    <!-- Más provincias... -->
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label for="instrucciones">Instrucciones de entrega (opcional)</label>
                                <textarea id="instrucciones" name="instrucciones" 
                                         placeholder="Ej: Timbre en la puerta azul, dejar con el vecino..."></textarea>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Método de Envío -->
                <section class="checkout-section">
                    <h2 class="section-title">
                        <i class="fas fa-shipping-fast"></i>
                        Método de Envío
                    </h2>
                    
                    <div class="metodos-envio">
                        <div class="metodo-envio-option">
                            <input type="radio" id="envio-estandar" name="metodo_envio" value="estandar" checked>
                            <label for="envio-estandar">
                                <div class="envio-info">
                                    <strong>Envío Estándar</strong>
                                    <span>3-5 días laborables</span>
                                </div>
                                <div class="envio-precio">Gratis</div>
                            </label>
                        </div>
                        
                        <div class="metodo-envio-option">
                            <input type="radio" id="envio-express" name="metodo_envio" value="express">
                            <label for="envio-express">
                                <div class="envio-info">
                                    <strong>Envío Express</strong>
                                    <span>1-2 días laborables</span>
                                </div>
                                <div class="envio-precio">$9.99</div>
                            </label>
                        </div>
                        
                        <div class="metodo-envio-option">
                            <input type="radio" id="envio-recogida" name="metodo_envio" value="recogida">
                            <label for="envio-recogida">
                                <div class="envio-info">
                                    <strong>Recogida en Tienda</strong>
                                    <span>Recoge cuando quieras</span>
                                </div>
                                <div class="envio-precio">Gratis</div>
                            </label>
                        </div>
                    </div>
                </section>

                <!-- Método de Pago -->
                <section class="checkout-section">
                    <h2 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        Método de Pago
                    </h2>
                    
                    <div class="metodos-pago">
                        <div class="metodo-pago-option">
                            <input type="radio" id="pago-tarjeta" name="metodo_pago" value="tarjeta" checked>
                            <label for="pago-tarjeta">
                                <i class="fas fa-credit-card"></i>
                                Tarjeta de Crédito/Débito
                            </label>
                        </div>
                        
                        <div class="metodo-pago-option">
                            <input type="radio" id="pago-paypal" name="metodo_pago" value="paypal">
                            <label for="pago-paypal">
                                <i class="fab fa-paypal"></i>
                                PayPal
                            </label>
                        </div>
                        
                        <div class="metodo-pago-option">
                            <input type="radio" id="pago-transferencia" name="metodo_pago" value="transferencia">
                            <label for="pago-transferencia">
                                <i class="fas fa-university"></i>
                                Transferencia Bancaria
                            </label>
                        </div>
                    </div>

                    <!-- Formulario de Tarjeta -->
                    <div id="form-tarjeta" class="form-tarjeta">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="numero_tarjeta">Número de Tarjeta *</label>
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta" 
                                       placeholder="1234 5678 9012 3456" maxlength="19">
                                <div class="tarjetas-aceptadas">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-amex"></i>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="fecha_expiracion">Fecha de Expiración *</label>
                                <input type="text" id="fecha_expiracion" name="fecha_expiracion" 
                                       placeholder="MM/AA" maxlength="5">
                            </div>
                            
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3">
                                <span class="cvv-info" title="Código de seguridad de 3 dígitos en el reverso de tu tarjeta">
                                    <i class="fas fa-question-circle"></i>
                                </span>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="nombre_tarjeta">Nombre en la Tarjeta *</label>
                                <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" 
                                       placeholder="Como aparece en la tarjeta">
                            </div>
                        </div>
                    </div>

                    <!-- Información PayPal -->
                    <div id="info-paypal" class="info-pago" style="display: none;">
                        <div class="paypal-info">
                            <i class="fab fa-paypal"></i>
                            <p>Serás redirigido a PayPal para completar tu pago de forma segura.</p>
                        </div>
                    </div>

                    <!-- Información Transferencia -->
                    <div id="info-transferencia" class="info-pago" style="display: none;">
                        <div class="transferencia-info">
                            <h4>Datos para la transferencia:</h4>
                            <div class="datos-bancarios">
                                <div class="dato-bancario">
                                    <strong>Banco:</strong>
                                    <span>Banco Santander</span>
                                </div>
                                <div class="dato-bancario">
                                    <strong>IBAN:</strong>
                                    <span>ES12 3456 7890 1234 5678 9012</span>
                                </div>
                                <div class="dato-bancario">
                                    <strong>Beneficiario:</strong>
                                    <span>Los Círculos de Atenea S.L.</span>
                                </div>
                                <div class="dato-bancario">
                                    <strong>Concepto:</strong>
                                    <span>Pedido #<?php echo time(); ?></span>
                                </div>
                            </div>
                            <p class="transferencia-nota">Una vez recibido el pago, procederemos a enviar tu pedido.</p>
                        </div>
                    </div>
                </section>

                <!-- Términos y Condiciones -->
                <div class="terminos-section">
                    <label class="checkbox-label">
                        <input type="checkbox" id="acepto_terminos" name="acepto_terminos" required>
                        <span class="checkmark"></span>
                        He leído y acepto los <a href="../terminos.php" target="_blank">términos y condiciones</a> 
                        y la <a href="../privacidad.php" target="_blank">política de privacidad</a>
                    </label>
                    
                    <label class="checkbox-label">
                        <input type="checkbox" id="acepto_marketing" name="acepto_marketing">
                        <span class="checkmark"></span>
                        Deseo recibir ofertas y novedades por email
                    </label>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="checkout-resumen-section">
                <div class="resumen-pedido">
                    <h2>Resumen del Pedido</h2>
                    
                    <div class="resumen-items" id="resumen-items">
                        <!-- Los items se cargarán dinámicamente -->
                    </div>

                    <div class="resumen-detalles">
                        <div class="resumen-linea">
                            <span>Subtotal:</span>
                            <span id="resumen-subtotal">$0.00</span>
                        </div>
                        <div class="resumen-linea">
                            <span>Envío:</span>
                            <span id="resumen-envio">$0.00</span>
                        </div>
                        <div class="resumen-linea" id="resumen-descuento-linea" style="display: none;">
                            <span>Descuento:</span>
                            <span id="resumen-descuento">-$0.00</span>
                        </div>
                        <div class="resumen-linea total-linea">
                            <strong>Total:</strong>
                            <strong id="resumen-total">$0.00</strong>
                        </div>
                    </div>

                    <!-- Código de Descuento -->
                    <div class="codigo-descuento-checkout">
                        <label for="codigo-descuento-checkout">¿Tienes un código de descuento?</label>
                        <div class="descuento-input-group">
                            <input type="text" id="codigo-descuento-checkout" placeholder="Ingresa tu código">
                            <button type="button" id="aplicar-descuento-checkout" class="btn btn-outline">Aplicar</button>
                        </div>
                        <div class="descuento-mensaje" id="descuento-mensaje-checkout"></div>
                    </div>

                    <!-- Botón de Confirmación -->
                    <button type="button" id="confirmar-pedido" class="btn btn-primary btn-confirmar">
                        <i class="fas fa-lock"></i>
                        Confirmar y Pagar
                    </button>

                    <!-- Seguridad -->
                    <div class="seguridad-checkout">
                        <div class="seguridad-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Compra 100% segura con encriptación SSL</span>
                        </div>
                        <div class="seguridad-item">
                            <i class="fas fa-lock"></i>
                            <span>Tus datos están protegidos</span>
                        </div>
                        <div class="seguridad-item">
                            <i class="fas fa-undo"></i>
                            <span>Devoluciones en 30 días</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>

<!-- Template para items del resumen -->
<template id="template-resumen-item">
    <div class="resumen-item">
        <div class="item-imagen">
            <img src="" alt="">
        </div>
        <div class="item-info">
            <h4 class="item-titulo"></h4>
            <p class="item-cantidad">Cantidad: </p>
        </div>
        <div class="item-precio">
            <span></span>
        </div>
    </div>
</template>

<script src="js/checkout.js"></script>
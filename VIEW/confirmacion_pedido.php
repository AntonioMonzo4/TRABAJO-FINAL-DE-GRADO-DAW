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

// Obtener ID del pedido desde la URL
$order_id = $_GET['order_id'] ?? null;

// Si no hay order_id, generar uno ficticio para la demostración
if (!$order_id) {
    $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);
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

// Simular datos del pedido (en una implementación real, esto vendría de la base de datos)
$pedido = [
    'order_id' => $order_id,
    'fecha_pedido' => date('d/m/Y H:i'),
    'estado' => 'confirmado',
    'metodo_pago' => $_GET['metodo_pago'] ?? 'tarjeta',
    'metodo_envio' => $_GET['metodo_envio'] ?? 'estandar',
    'precio_total' => $_GET['total'] ?? '0.00'
];

// Simular productos del pedido (en una implementación real, esto vendría de la base de datos)
$productos_pedido = [
    [
        'titulo' => 'Una corte de rosas y espinas',
        'autor' => 'Sarah J. Maas',
        'cantidad' => 1,
        'precio' => 19.99,
        'imagen' => 'una-corte-de-rosas-y-espinas.png'
    ],
    [
        'titulo' => 'La paciente silenciosa',
        'autor' => 'Alex Michaelides',
        'cantidad' => 1,
        'precio' => 16.99,
        'imagen' => 'la-paciente-silenciosa.png'
    ]
];
?>

<main class="confirmacion-page">
    <!-- Migas de Pan -->
    <nav class="migas-pan">
        <div class="container">
            <a href="../index.php">Inicio</a> / 
            <a href="../index.php?pagina=carrito">Carrito</a> / 
            <a href="../index.php?pagina=checkout">Checkout</a> / 
            <span>Confirmación</span>
        </div>
    </nav>

    <div class="container">
        <!-- Header de Confirmación -->
        <section class="confirmacion-header">
            <div class="confirmacion-icono">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>¡Pedido Confirmado!</h1>
            <p class="confirmacion-mensaje">
                Gracias por tu compra. Tu pedido ha sido procesado exitosamente.
            </p>
            <div class="numero-pedido">
                <strong>Número de pedido:</strong>
                <span class="order-id"><?php echo htmlspecialchars($pedido['order_id']); ?></span>
            </div>
        </section>

        <div class="confirmacion-grid">
            <!-- Resumen del Pedido -->
            <section class="resumen-pedido-confirmacion">
                <h2 class="section-title">
                    <i class="fas fa-box"></i>
                    Resumen del Pedido
                </h2>

                <div class="pedido-detalles">
                    <div class="detalle-linea">
                        <span>Fecha del pedido:</span>
                        <span><?php echo $pedido['fecha_pedido']; ?></span>
                    </div>
                    <div class="detalle-linea">
                        <span>Estado:</span>
                        <span class="estado-pedido confirmado">Confirmado</span>
                    </div>
                    <div class="detalle-linea">
                        <span>Método de pago:</span>
                        <span>
                            <?php 
                            $metodos_pago = [
                                'tarjeta' => 'Tarjeta de Crédito/Débito',
                                'paypal' => 'PayPal',
                                'transferencia' => 'Transferencia Bancaria'
                            ];
                            echo $metodos_pago[$pedido['metodo_pago']] ?? 'Tarjeta de Crédito/Débito';
                            ?>
                        </span>
                    </div>
                    <div class="detalle-linea">
                        <span>Método de envío:</span>
                        <span>
                            <?php 
                            $metodos_envio = [
                                'estandar' => 'Envío Estándar (3-5 días)',
                                'express' => 'Envío Express (1-2 días)',
                                'recogida' => 'Recogida en Tienda'
                            ];
                            echo $metodos_envio[$pedido['metodo_envio']] ?? 'Envío Estándar';
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Productos del Pedido -->
                <div class="productos-pedido">
                    <h3>Productos en tu pedido</h3>
                    <div class="productos-lista">
                        <?php foreach ($productos_pedido as $producto): ?>
                        <div class="producto-pedido">
                            <div class="producto-imagen">
                                <img src="img/libros/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                     alt="<?php echo htmlspecialchars($producto['titulo']); ?>"
                                     onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                            </div>
                            <div class="producto-info">
                                <h4><?php echo htmlspecialchars($producto['titulo']); ?></h4>
                                <p class="producto-autor"><?php echo htmlspecialchars($producto['autor']); ?></p>
                                <p class="producto-cantidad">Cantidad: <?php echo $producto['cantidad']; ?></p>
                            </div>
                            <div class="producto-precio">
                                $<?php echo number_format($producto['precio'], 2); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Total del Pedido -->
                <div class="total-pedido">
                    <div class="total-linea">
                        <span>Subtotal:</span>
                        <span>
                            <?php
                            $subtotal = array_sum(array_map(function($p) {
                                return $p['precio'] * $p['cantidad'];
                            }, $productos_pedido));
                            echo '$' . number_format($subtotal, 2);
                            ?>
                        </span>
                    </div>
                    <div class="total-linea">
                        <span>Envío:</span>
                        <span>
                            <?php
                            $envio = $pedido['metodo_envio'] === 'express' ? 9.99 : 0;
                            echo $envio === 0 ? 'Gratis' : '$' . number_format($envio, 2);
                            ?>
                        </span>
                    </div>
                    <div class="total-linea total-final">
                        <strong>Total:</strong>
                        <strong>$<?php echo number_format($pedido['precio_total'] ?: ($subtotal + $envio), 2); ?></strong>
                    </div>
                </div>
            </section>

            <!-- Información de Envío y Siguientes Pasos -->
            <div class="info-lateral">
                <!-- Información de Envío -->
                <section class="info-envio">
                    <h2 class="section-title">
                        <i class="fas fa-truck"></i>
                        Información de Envío
                    </h2>
                    
                    <div class="direccion-envio">
                        <?php if ($usuario): ?>
                        <p><strong><?php echo htmlspecialchars($usuario['nombre'] . ' ' . ($usuario['apellidos'] ?? '')); ?></strong></p>
                        <p><?php echo htmlspecialchars($usuario['direccion'] ?? 'Calle Ejemplo 123'); ?></p>
                        <p><?php echo htmlspecialchars($usuario['ciudad'] ?? 'Ciudad'); ?>, <?php echo htmlspecialchars($usuario['codigo_postal'] ?? '28001'); ?></p>
                        <p><?php echo htmlspecialchars($usuario['provincia'] ?? 'Madrid'); ?></p>
                        <p>Teléfono: <?php echo htmlspecialchars($usuario['telefono'] ?? '+34 123 456 789'); ?></p>
                        <?php else: ?>
                        <p>Los datos de envío se han guardado correctamente.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Siguientes Pasos -->
                <section class="siguientes-pasos">
                    <h2 class="section-title">
                        <i class="fas fa-list-alt"></i>
                        ¿Qué sigue ahora?
                    </h2>
                    
                    <div class="pasos-lista">
                        <div class="paso-item completado">
                            <div class="paso-icono">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="paso-info">
                                <strong>Pedido Confirmado</strong>
                                <span>Hemos recibido tu pedido</span>
                            </div>
                        </div>
                        
                        <div class="paso-item activo">
                            <div class="paso-icono">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="paso-info">
                                <strong>Preparando Pedido</strong>
                                <span>Estamos preparando tu envío</span>
                            </div>
                        </div>
                        
                        <div class="paso-item">
                            <div class="paso-icono">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="paso-info">
                                <strong>En Camino</strong>
                                <span>Tu pedido ha sido enviado</span>
                            </div>
                        </div>
                        
                        <div class="paso-item">
                            <div class="paso-icono">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="paso-info">
                                <strong>Entregado</strong>
                                <span>¡Disfruta de tu compra!</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tiempo estimado de entrega -->
                    <div class="tiempo-entrega">
                        <h4>Tiempo estimado de entrega:</h4>
                        <p>
                            <?php
                            $dias_entrega = $pedido['metodo_envio'] === 'express' ? '1-2 días laborables' : 
                                           ($pedido['metodo_envio'] === 'recogida' ? 'Disponible en 24h' : '3-5 días laborables');
                            echo $dias_entrega;
                            ?>
                        </p>
                    </div>
                </section>

                <!-- Acciones Rápidas -->
                <section class="acciones-rapidas">
                    <h2 class="section-title">
                        <i class="fas fa-bolt"></i>
                        Acciones Rápidas
                    </h2>
                    
                    <div class="botones-accion">
                        <a href="../index.php?pagina=mis_pedidos" class="btn btn-primary">
                            <i class="fas fa-clipboard-list"></i>
                            Ver Mis Pedidos
                        </a>
                        <a href="../index.php?pagina=categoriaTienda" class="btn btn-secondary">
                            <i class="fas fa-book"></i>
                            Seguir Comprando
                        </a>
                        <button id="descargar-factura" class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Descargar Factura
                        </button>
                    </div>
                </section>

                <!-- Soporte -->
                <section class="soporte-confirmacion">
                    <h2 class="section-title">
                        <i class="fas fa-headset"></i>
                        ¿Necesitas ayuda?
                    </h2>
                    
                    <div class="info-soporte">
                        <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos:</p>
                        
                        <div class="canales-soporte">
                            <div class="canal-soporte">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Email</strong>
                                    <span>pedidos@loscirculosdeatenea.com</span>
                                </div>
                            </div>
                            <div class="canal-soporte">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Teléfono</strong>
                                    <span>+34 123 456 789</span>
                                </div>
                            </div>
                            <div class="canal-soporte">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Horario</strong>
                                    <span>Lun-Vie: 9:00-18:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Productos Recomendados -->
        <section class="recomendaciones-confirmacion">
            <h2>Quizás también te interese</h2>
            <div class="grid-recomendaciones">
                <div class="producto-recomendado">
                    <a href="../index.php?pagina=detalle_producto&id=1" class="producto-link">
                        <div class="producto-imagen">
                            <img src="https://images.unsplash.com/photo-1531346680769-a1d79b57de5e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Don Quijote">
                        </div>
                        <div class="producto-info">
                            <h3>Don Quijote de la Mancha</h3>
                            <p class="producto-autor">Miguel de Cervantes</p>
                            <p class="producto-precio">$24.99</p>
                        </div>
                    </a>
                </div>
                
                <div class="producto-recomendado">
                    <a href="../index.php?pagina=detalle_producto&id=2" class="producto-link">
                        <div class="producto-imagen">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="1984">
                        </div>
                        <div class="producto-info">
                            <h3>1984</h3>
                            <p class="producto-autor">George Orwell</p>
                            <p class="producto-precio">$16.99</p>
                        </div>
                    </a>
                </div>
                
                <div class="producto-recomendado">
                    <a href="../index.php?pagina=detalle_producto&id=3" class="producto-link">
                        <div class="producto-imagen">
                            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Orgullo y prejuicio">
                        </div>
                        <div class="producto-info">
                            <h3>Orgullo y prejuicio</h3>
                            <p class="producto-autor">Jane Austen</p>
                            <p class="producto-precio">$18.99</p>
                        </div>
                    </a>
                </div>
                
                <div class="producto-recomendado">
                    <a href="../index.php?pagina=detalle_producto&id=4" class="producto-link">
                        <div class="producto-imagen">
                            <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="El principito">
                        </div>
                        <div class="producto-info">
                            <h3>El principito</h3>
                            <p class="producto-autor">Antoine de Saint-Exupéry</p>
                            <p class="producto-precio">$14.99</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
</main>

<?php require_once 'footer.php'; ?>

<script src="js/confirmacion_pedido.js"></script>
<?php
require_once 'header.php';
require_once '../controller/conexion.php';

// Verificar que se haya proporcionado un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php?pagina=categoriaTienda');
    exit;
}

$book_id = intval($_GET['id']);

// Obtener información del libro
try {
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$book_id]);
    $libro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$libro) {
        header('Location: ../index.php?pagina=categoriaTienda');
        exit;
    }
} catch (PDOException $e) {
    error_log("Error al obtener libro: " . $e->getMessage());
    header('Location: ../index.php?pagina=categoriaTienda');
    exit;
}

// Obtener libros relacionados (mismo género)
try {
    $sqlRelacionados = "SELECT * FROM books WHERE genero_literario = ? AND book_id != ? ORDER BY RAND() LIMIT 4";
    $stmtRelacionados = $pdo->prepare($sqlRelacionados);
    $stmtRelacionados->execute([$libro['genero_literario'], $book_id]);
    $librosRelacionados = $stmtRelacionados->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener libros relacionados: " . $e->getMessage());
    $librosRelacionados = [];
}
?>

<main class="detalle-producto-page">
    <!-- Migas de Pan -->
    <nav class="migas-pan">
        <div class="container">
            <a href="../index.php">Inicio</a> / 
            <a href="../index.php?pagina=categoriaTienda">Tienda</a> / 
            <a href="../index.php?pagina=categoriaTienda&genero=<?php echo urlencode($libro['genero_literario']); ?>">
                <?php echo htmlspecialchars($libro['genero_literario']); ?>
            </a> / 
            <span><?php echo htmlspecialchars($libro['titulo']); ?></span>
        </div>
    </nav>

    <!-- Detalle del Producto -->
    <section class="detalle-producto">
        <div class="container">
            <div class="detalle-grid">
                <!-- Galería de Imágenes -->
                <div class="detalle-galeria">
                    <div class="imagen-principal">
                        <img src="img/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>" 
                             alt="<?php echo htmlspecialchars($libro['titulo']); ?>"
                             id="imagen-principal"
                             onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                    </div>
                    <!-- En una implementación real, aquí irían miniaturas de otras imágenes -->
                    <div class="miniaturas">
                        <div class="miniatura active" data-imagen="img/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>">
                            <img src="img/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>" 
                                 alt="Vista previa"
                                 onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                        </div>
                    </div>
                </div>

                <!-- Información del Producto -->
                <div class="detalle-info">
                    <h1 class="producto-titulo"><?php echo htmlspecialchars($libro['titulo']); ?></h1>
                    <p class="producto-autor">por <strong><?php echo htmlspecialchars($libro['autor']); ?></strong></p>
                    
                    <div class="rating">
                        <div class="estrellas">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-text">(4.5/5 - 128 reseñas)</span>
                    </div>

                    <div class="precio-detalle">
                        <span class="precio-actual">$<?php echo number_format($libro['precio'], 2); ?></span>
                        <!-- <span class="precio-anterior">$29.99</span> -->
                        <!-- <span class="descuento">-20%</span> -->
                    </div>

                    <div class="stock-detalle">
                        <?php if ($libro['stock'] > 0): ?>
                            <span class="stock-disponible">
                                <i class="fas fa-check-circle"></i>
                                En stock (<?php echo $libro['stock']; ?> disponibles)
                            </span>
                        <?php else: ?>
                            <span class="stock-agotado">
                                <i class="fas fa-times-circle"></i>
                                Agotado temporalmente
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="descripcion-corta">
                        <p><?php echo htmlspecialchars($libro['descripcion'] ?? 'Descripción no disponible.'); ?></p>
                    </div>

                    <div class="caracteristicas-rapidas">
                        <div class="caracteristica">
                            <i class="fas fa-book"></i>
                            <span>Género: <?php echo htmlspecialchars($libro['genero_literario'] ?? 'No especificado'); ?></span>
                        </div>
                        <div class="caracteristica">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Envío gratuito en pedidos > $50</span>
                        </div>
                        <div class="caracteristica">
                            <i class="fas fa-undo"></i>
                            <span>Devoluciones en 30 días</span>
                        </div>
                    </div>

                    <!-- Selector de Cantidad y Añadir al Carrito -->
                    <div class="acciones-compra">
                        <div class="selector-cantidad">
                            <label for="cantidad">Cantidad:</label>
                            <div class="cantidad-controls">
                                <button type="button" class="btn-cantidad btn-restar">-</button>
                                <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $libro['stock']; ?>" <?php echo $libro['stock'] <= 0 ? 'disabled' : ''; ?>>
                                <button type="button" class="btn-cantidad btn-sumar">+</button>
                            </div>
                        </div>

                        <div class="botones-accion">
                            <button class="btn btn-primary btn-anadir-carrito-detalle add-to-cart
                                        <?php echo $libro['stock'] <= 0 ? 'disabled' : ''; ?>"
                                    data-book-id="<?php echo $libro['book_id']; ?>"
                                    data-book-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                                    data-book-precio="<?php echo $libro['precio']; ?>"
                                    data-book-imagen="<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>"
                                    <?php echo $libro['stock'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="fas fa-shopping-cart"></i>
                                <?php echo $libro['stock'] > 0 ? 'Añadir al Carrito' : 'Agotado'; ?>
                            </button>
                            
                            <button class="btn btn-outline btn-comprar-ahora 
                                        <?php echo $libro['stock'] <= 0 ? 'disabled' : ''; ?>"
                                    <?php echo $libro['stock'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="fas fa-bolt"></i>
                                Comprar Ahora
                            </button>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="info-adicional">
                        <div class="info-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Compra 100% segura</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-headset"></i>
                            <span>Soporte 24/7</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-gift"></i>
                            <span>Embalaje especial para regalo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs de Información Detallada -->
    <section class="tabs-detalle">
        <div class="container">
            <div class="tabs-container">
                <div class="tabs-headers">
                    <button class="tab-header active" data-tab="descripcion">Descripción</button>
                    <button class="tab-header" data-tab="detalles">Detalles</button>
                    <button class="tab-header" data-tab="envio">Envío y Devoluciones</button>
                    <button class="tab-header" data-tab="reseñas">Reseñas</button>
                </div>

                <div class="tabs-content">
                    <!-- Descripción -->
                    <div class="tab-content active" id="descripcion">
                        <h3>Acerca de este libro</h3>
                        <div class="descripcion-completa">
                            <p><?php echo htmlspecialchars($libro['descripcion'] ?? 'Descripción no disponible.'); ?></p>
                            
                            <div class="caracteristicas-lista">
                                <h4>Características principales:</h4>
                                <ul>
                                    <li>Edición de alta calidad</li>
                                    <li>Papel ecológico certificado</li>
                                    <li>Encuadernación duradera</li>
                                    <li>Letra de tamaño cómodo para lectura</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="tab-content" id="detalles">
                        <h3>Información del Producto</h3>
                        <div class="detalles-lista">
                            <div class="detalle-item">
                                <strong>ISBN:</strong>
                                <span>978-<?php echo sprintf('%013d', $libro['book_id']); ?></span>
                            </div>
                            <div class="detalle-item">
                                <strong>Autor:</strong>
                                <span><?php echo htmlspecialchars($libro['autor']); ?></span>
                            </div>
                            <div class="detalle-item">
                                <strong>Género:</strong>
                                <span><?php echo htmlspecialchars($libro['genero_literario'] ?? 'No especificado'); ?></span>
                            </div>
                            <div class="detalle-item">
                                <strong>Páginas:</strong>
                                <span>350-450 (aprox.)</span>
                            </div>
                            <div class="detalle-item">
                                <strong>Editorial:</strong>
                                <span>Los Círculos de Atenea</span>
                            </div>
                            <div class="detalle-item">
                                <strong>Idioma:</strong>
                                <span>Español</span>
                            </div>
                        </div>
                    </div>

                    <!-- Envío -->
                    <div class="tab-content" id="envio">
                        <h3>Envío y Devoluciones</h3>
                        <div class="envio-info">
                            <div class="envio-item">
                                <i class="fas fa-shipping-fast"></i>
                                <div>
                                    <h4>Envío Estándar</h4>
                                    <p>3-5 días laborables - Gratis en pedidos superiores a $50</p>
                                </div>
                            </div>
                            <div class="envio-item">
                                <i class="fas fa-rocket"></i>
                                <div>
                                    <h4>Envío Express</h4>
                                    <p>1-2 días laborables - $9.99</p>
                                </div>
                            </div>
                            <div class="envio-item">
                                <i class="fas fa-undo"></i>
                                <div>
                                    <h4>Devoluciones</h4>
                                    <p>30 días para devoluciones gratuitas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reseñas -->
                    <div class="tab-content" id="reseñas">
                        <h3>Reseñas de Clientes</h3>
                        <div class="resumen-resenas">
                            <div class="puntuacion-global">
                                <div class="puntuacion-numero">4.5</div>
                                <div class="estrellas-grandes">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <div class="total-resenas">Basado en 128 reseñas</div>
                            </div>
                        </div>

                        <div class="lista-resenas">
                            <div class="resena-item">
                                <div class="resena-header">
                                    <div class="resena-autor">
                                        <strong>María G.</strong>
                                        <div class="estrellas">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="resena-fecha">Hace 2 semanas</span>
                                </div>
                                <p class="resena-texto">¡Increíble libro! La calidad de impresión es excelente y la historia me mantuvo enganchada desde la primera página.</p>
                            </div>

                            <div class="resena-item">
                                <div class="resena-header">
                                    <div class="resena-autor">
                                        <strong>Carlos R.</strong>
                                        <div class="estrellas">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                    <span class="resena-fecha">Hace 1 mes</span>
                                </div>
                                <p class="resena-texto">Muy buena edición. Llegó perfectamente empaquetado y antes de lo esperado. Recomendado.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Relacionados -->
    <?php if (!empty($librosRelacionados)): ?>
    <section class="productos-relacionados">
        <div class="container">
            <h2>Libros Relacionados</h2>
            <div class="grid-relacionados">
                <?php foreach ($librosRelacionados as $relacionado): ?>
                <div class="producto-relacionado">
                    <a href="../index.php?pagina=detalle_producto&id=<?php echo $relacionado['book_id']; ?>" class="producto-link">
                        <div class="producto-imagen">
                            <img src="img/libros/<?php echo htmlspecialchars($relacionado['imagen'] ?? 'default-book.png'); ?>" 
                                 alt="<?php echo htmlspecialchars($relacionado['titulo']); ?>"
                                 onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                        </div>
                        <div class="producto-info">
                            <h3><?php echo htmlspecialchars($relacionado['titulo']); ?></h3>
                            <p class="producto-autor"><?php echo htmlspecialchars($relacionado['autor']); ?></p>
                            <p class="producto-precio">$<?php echo number_format($relacionado['precio'], 2); ?></p>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php require_once 'footer.php'; ?>

<script src="js/detalle_producto.js"></script>
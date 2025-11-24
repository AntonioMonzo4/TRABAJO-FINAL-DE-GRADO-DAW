<?php
require_once 'header.php';
require_once '../controller/conexion.php';

// Parámetros de filtros
$genero = $_GET['genero'] ?? '';
$tipo = $_GET['tipo'] ?? 'libros';
$pagina = $_GET['pagina'] ?? 1;
$porPagina = 12;

// Construir consulta base
$sql = "SELECT * FROM books WHERE 1=1";
$params = [];

if ($genero) {
    $sql .= " AND genero_literario = ?";
    $params[] = $genero;
}

// Contar total para paginación
$sqlCount = "SELECT COUNT(*) as total FROM books WHERE 1=1";
if ($genero) {
    $sqlCount .= " AND genero_literario = ?";
}

$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalLibros = $stmtCount->fetchColumn();

// Calcular paginación
$totalPaginas = ceil($totalLibros / $porPagina);
$offset = ($pagina - 1) * $porPagina;

// Obtener libros
$sql .= " LIMIT ? OFFSET ?";
$params[] = $porPagina;
$params[] = $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener géneros para filtros
$stmtGeneros = $pdo->query("SELECT DISTINCT genero_literario FROM books WHERE genero_literario IS NOT NULL ORDER BY genero_literario");
$generos = $stmtGeneros->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="tienda-page">
    <!-- Header de la Tienda -->
    <section class="tienda-header">
        <div class="container">
            <h1>Nuestra Tienda</h1>
            <p>Descubre nuestra amplia selección de libros y productos</p>
        </div>
    </section>

    <!-- Filtros y Ordenamiento -->
    <section class="filtros-section">
        <div class="container">
            <div class="filtros-grid">
                <!-- Filtro por Género -->
                <div class="filtro-group">
                    <label for="filtro-genero">Género Literario:</label>
                    <select id="filtro-genero" class="filtro-select">
                        <option value="">Todos los géneros</option>
                        <?php foreach ($generos as $gen): ?>
                        <option value="<?php echo htmlspecialchars($gen['genero_literario']); ?>" 
                                <?php echo $genero === $gen['genero_literario'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($gen['genero_literario']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por Tipo -->
                <div class="filtro-group">
                    <label for="filtro-tipo">Tipo de Producto:</label>
                    <select id="filtro-tipo" class="filtro-select">
                        <option value="libros" <?php echo $tipo === 'libros' ? 'selected' : ''; ?>>Libros</option>
                        <option value="otros" <?php echo $tipo === 'otros' ? 'selected' : ''; ?>>Otros Productos</option>
                    </select>
                </div>

                <!-- Ordenamiento -->
                <div class="filtro-group">
                    <label for="ordenar-por">Ordenar por:</label>
                    <select id="ordenar-por" class="filtro-select">
                        <option value="relevancia">Relevancia</option>
                        <option value="precio-asc">Precio: Menor a Mayor</option>
                        <option value="precio-desc">Precio: Mayor a Menor</option>
                        <option value="titulo-asc">Título: A-Z</option>
                        <option value="titulo-desc">Título: Z-A</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Resultados -->
    <section class="resultados-section">
        <div class="container">
            <div class="resultados-header">
                <p class="resultados-count">
                    Mostrando <?php echo count($libros); ?> de <?php echo $totalLibros; ?> resultados
                    <?php echo $genero ? "en <strong>" . htmlspecialchars($genero) . "</strong>" : ""; ?>
                </p>
            </div>

            <?php if (!empty($libros)): ?>
            <div class="grid-productos-tienda">
                <?php foreach ($libros as $libro): ?>
                <div class="producto-card-tienda">
                    <div class="producto-imagen-tienda">
                        <img src="img/libros/<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>" 
                             alt="<?php echo htmlspecialchars($libro['titulo']); ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80'">
                        <div class="producto-acciones">
                            <button class="btn-rapido-carrito" 
                                    data-book-id="<?php echo $libro['book_id']; ?>"
                                    data-book-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                                    data-book-precio="<?php echo $libro['precio']; ?>"
                                    data-book-imagen="<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <a href="../index.php?pagina=detalle_producto&id=<?php echo $libro['book_id']; ?>" class="btn-ver-detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <div class="producto-info-tienda">
                        <h3 class="producto-titulo"><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                        <p class="producto-autor"><?php echo htmlspecialchars($libro['autor']); ?></p>
                        <p class="producto-genero"><?php echo htmlspecialchars($libro['genero_literario'] ?? 'Sin género'); ?></p>
                        <div class="producto-precio-tienda">
                            <span class="precio">$<?php echo number_format($libro['precio'], 2); ?></span>
                            <?php if ($libro['stock'] > 0): ?>
                                <span class="stock-disponible">En stock</span>
                            <?php else: ?>
                                <span class="stock-agotado">Agotado</span>
                            <?php endif; ?>
                        </div>
                        <button class="btn-anadir-carrito-tienda"
                                data-book-id="<?php echo $libro['book_id']; ?>"
                                data-book-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                                data-book-precio="<?php echo $libro['precio']; ?>"
                                data-book-imagen="<?php echo htmlspecialchars($libro['imagen'] ?? 'default-book.png'); ?>">
                            Añadir al Carrito
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <?php if ($totalPaginas > 1): ?>
            <div class="paginacion">
                <?php if ($pagina > 1): ?>
                    <a href="../index.php?pagina=categoriaTienda&genero=<?php echo urlencode($genero); ?>&tipo=<?php echo $tipo; ?>&pagina=<?php echo $pagina - 1; ?>" class="pagina-anterior">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>

                <div class="numeros-pagina">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $pagina): ?>
                            <span class="pagina-actual"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="../index.php?pagina=categoriaTienda&genero=<?php echo urlencode($genero); ?>&tipo=<?php echo $tipo; ?>&pagina=<?php echo $i; ?>" class="pagina-numero"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($pagina < $totalPaginas): ?>
                    <a href="../index.php?pagina=categoriaTienda&genero=<?php echo urlencode($genero); ?>&tipo=<?php echo $tipo; ?>&pagina=<?php echo $pagina + 1; ?>" class="pagina-siguiente">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="sin-resultados">
                <i class="fas fa-search"></i>
                <h3>No se encontraron resultados</h3>
                <p>Intenta con otros filtros o <a href="../index.php?pagina=categoriaTienda">ver todos los productos</a></p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once 'footer.php'; ?>

<script>
// Filtros en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const filtroGenero = document.getElementById('filtro-genero');
    const filtroTipo = document.getElementById('filtro-tipo');
    const ordenarPor = document.getElementById('ordenar-por');

    function aplicarFiltros() {
        const genero = filtroGenero.value;
        const tipo = filtroTipo.value;
        const orden = ordenarPor.value;
        
        let url = `../index.php?pagina=categoriaTienda&genero=${encodeURIComponent(genero)}&tipo=${tipo}`;
        if (orden !== 'relevancia') {
            url += `&orden=${orden}`;
        }
        
        window.location.href = url;
    }

    filtroGenero.addEventListener('change', aplicarFiltros);
    filtroTipo.addEventListener('change', aplicarFiltros);
    ordenarPor.addEventListener('change', aplicarFiltros);

    // Botones de añadir al carrito
    document.querySelectorAll('.btn-anadir-carrito-tienda, .btn-rapido-carrito').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (typeof window.carritoManager !== 'undefined') {
                const producto = {
                    id: this.getAttribute('data-book-id'),
                    tipo: 'book',
                    nombre: this.getAttribute('data-book-titulo'),
                    precio: parseFloat(this.getAttribute('data-book-precio')),
                    imagen: this.getAttribute('data-book-imagen')
                };

                window.carritoManager.agregarAlCarrito(producto);
            } else {
                alert('Error: Sistema de carrito no disponible');
            }
        });
    });
});
</script>
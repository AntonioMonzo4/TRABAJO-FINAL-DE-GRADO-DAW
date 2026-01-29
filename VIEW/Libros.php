<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

/* ===== Obtener géneros para botones (NUEVO) ===== */
$stGen = $pdo->query("
    SELECT DISTINCT genero_literario
    FROM books
    WHERE genero_literario IS NOT NULL AND genero_literario <> ''
    ORDER BY genero_literario ASC
");
$generos = $stGen->fetchAll(PDO::FETCH_COLUMN);

/* ===== Mantener tu lógica actual de GET (opcional) ===== */
$genero = isset($_GET['genero']) ? trim((string)$_GET['genero']) : null;
if ($genero === '') $genero = null;

/* ===== IMPORTANTE: para que “Todos” muestre todos SIN romper nada,
   cargamos SIEMPRE todos los libros y filtramos por JS ===== */
$stmt = $pdo->query("SELECT * FROM books");
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Migas de pan (NO CAMBIO TU BREADCRUMB) */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Tienda', 'url' => '/tienda'],
    ['label' => 'Libros', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
<section class="container">

<h1>Libros</h1>

<!-- ===== NUEVO: FILTRO ARRIBA (sin tocar tu grid) ===== -->
<div class="genre-toolbar" aria-label="Filtros por género">
    <button type="button" class="genre-btn is-active" data-genre="__all">Todos</button>

    <?php foreach ($generos as $g): ?>
        <button type="button" class="genre-btn" data-genre="<?= htmlspecialchars($g) ?>">
            <?= htmlspecialchars($g) ?>
        </button>
    <?php endforeach; ?>
</div>

<?php if (!$libros): ?>
    <p>No hay libros disponibles.</p>
<?php else: ?>

<div class="grid-products">
<?php foreach ($libros as $l): ?>
    <article class="product" data-genre="<?= htmlspecialchars($l['genero_literario'] ?? '') ?>">
        <img
            src="/VIEW/img/libros/<?= htmlspecialchars($l['imagen'] ?? 'default-book.png') ?>"
            alt="<?= htmlspecialchars($l['titulo']) ?>"
            onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=500&q=80'">

        <h3><?= htmlspecialchars($l['titulo']) ?></h3>
        <p><?= htmlspecialchars($l['autor']) ?></p>
        <p><strong><?= number_format((float)$l['precio'], 2) ?> €</strong></p>

        <a class="btn btn-secondary" href="/book/<?= (int)$l['book_id'] ?>">
            Ver detalle
        </a>
    </article>
<?php endforeach; ?>
</div>

<?php endif; ?>

</section>
</main>

<!-- ===== NUEVO: JS para filtrar SIN recargar ===== -->
<script>
(function () {
    const btns  = document.querySelectorAll('.genre-btn');
    const cards = document.querySelectorAll('.grid-products .product[data-genre]');

    function normalize(s){ return (s || '').trim().toLowerCase(); }

    function setActive(btn){
        btns.forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');
    }

    function applyFilter(genre){
        if (genre === '__all') {
            cards.forEach(c => c.style.display = '');
            return;
        }
        const target = normalize(genre);
        cards.forEach(card => {
            const g = normalize(card.getAttribute('data-genre'));
            card.style.display = (g === target) ? '' : 'none';
        });
    }

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            const genre = btn.getAttribute('data-genre');
            setActive(btn);
            applyFilter(genre);
        });
    });

    // Si vienes con ?genero=... desde el home, aplica el filtro al cargar (sin cambiar tu layout)
    const urlParams = new URLSearchParams(window.location.search);
    const fromGenero = (urlParams.get('genero') || '').trim();
    if (fromGenero) {
        const match = Array.from(btns).find(b => normalize(b.getAttribute('data-genre')) === normalize(fromGenero));
        if (match) match.click();
        else applyFilter('__all');
    }
})();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>

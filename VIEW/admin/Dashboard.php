<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';

require_once __DIR__ . '/../../MODEL/conexion.php';
$pdo = conexion::conexionBBDD();

// KPI básicos
$totalPedidos  = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalIngresos = $pdo->query("SELECT COALESCE(SUM(precio_total), 0) FROM orders")->fetchColumn();
$totalUsuarios = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$librosSinStock = (int)$pdo->query("SELECT COUNT(*) FROM books WHERE stock <= 0")->fetchColumn();

// Otros productos sin stock (si existe la tabla)
$otrosSinStock = 0;
try {
    $otrosSinStock = (int)$pdo->query("SELECT COUNT(*) FROM other_products WHERE stock <= 0")->fetchColumn();
} catch (Throwable $e) {
    // Si no existe o hay error, no rompemos el panel
    $otrosSinStock = 0;
}

// Migas
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => null],
];
if (file_exists(__DIR__ . '/../partials/breadcrumb.php')) {
    require __DIR__ . '/../partials/breadcrumb.php';
}
?>

<main class="page">
    <section class="container">

        <h1>Panel de administración</h1>

        <div class="cards-4">
            <div class="card">
                <h3>Pedidos</h3>
                <p><?= $totalPedidos ?></p>
            </div>

            <div class="card">
                <h3>Ingresos</h3>
                <p><?= number_format((float)$totalIngresos, 2) ?> €</p>
            </div>

            <div class="card">
                <h3>Usuarios</h3>
                <p><?= $totalUsuarios ?></p>
            </div>

            <div class="card">
                <h3>Sin stock</h3>
                <p><?= $librosSinStock + $otrosSinStock ?></p>
                <small>Libros: <?= $librosSinStock ?> · Otros: <?= $otrosSinStock ?></small>
            </div>
        </div>

        <h2>Gestión</h2>

        <div class="cards-3">
            <a href="/admin/pedidos" class="card-link">
                <div class="card">
                    <h3>Pedidos</h3>
                    <p>Ver y gestionar pedidos</p>
                </div>
            </a>

            <a href="/admin/usuarios" class="card-link">
                <div class="card">
                    <h3>Usuarios</h3>
                    <p>Listado y control de cuentas</p>
                </div>
            </a>

            <a href="/admin/stock" class="card-link">
                <div class="card">
                    <h3>Stock</h3>
                    <p>Gestión de libros y otros productos</p>
                </div>
            </a>
        </div>

    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
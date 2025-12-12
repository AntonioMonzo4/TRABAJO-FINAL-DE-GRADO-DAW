<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';

require_once __DIR__ . '/../../MODEL/conexion.php';
$pdo = conexion::conexionBBDD();

$totalPedidos = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalIngresos = $pdo->query("SELECT SUM(precio_total) FROM orders")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$sinStock = $pdo->query("SELECT COUNT(*) FROM books WHERE stock <= 0")->fetchColumn();


/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">

        <div class="cards-4">
            <div class="card">
                <h3>Pedidos</h3>
                <p><?= $totalPedidos ?></p>
            </div>
            <div class="card">
                <h3>Ingresos</h3>
                <p><?= number_format($totalIngresos ?? 0, 2) ?> €</p>
            </div>
            <div class="card">
                <h3>Usuarios</h3>
                <p><?= $totalUsuarios ?></p>
            </div>
            <div class="card">
                <h3>Libros sin stock</h3>
                <p><?= $sinStock ?></p>
            </div>
        </div>

        <h1>Panel de administración</h1>

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
                    <p>Listado de usuarios registrados</p>
                </div>
            </a>

            <a href="/admin/stock" class="card-link">
                <div class="card">
                    <h3>Stock</h3>
                    <p>Gestión de productos</p>
                </div>
            </a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$pedidos = $pdo->query("
  SELECT o.order_id, o.fecha_pedido, o.precio_total, o.metodo_pago,
         u.nombre, u.email
  FROM orders o
  JOIN users u ON o.user_id = u.user_id
  ORDER BY o.fecha_pedido DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Pedidos', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">
        <h1>Pedidos</h1>

        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Pago</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td>#<?= $p['order_id'] ?></td>
                        <td><?= $p['fecha_pedido'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= number_format($p['precio_total'], 2) ?> €</td>
                        <td><?= $p['metodo_pago'] ?></td>
                        <td>
                            <a class="btn btn-secondary"
                                href="/admin/pedido?id=<?= $p['order_id'] ?>">
                                Ver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
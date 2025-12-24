<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$id = (int)($_GET['id'] ?? 0);

$pedido = $pdo->prepare("
  SELECT o.*, u.nombre, u.email
  FROM orders o
  JOIN users u ON o.user_id = u.user_id
  WHERE o.order_id = :id
");
$pedido->execute([':id' => $id]);
$pedido = $pedido->fetch(PDO::FETCH_ASSOC);

$itemsPedido = $pdo->prepare("
  SELECT *
  FROM order_items
  WHERE order_id = :id
");
$itemsPedido->execute([':id' => $id]);
$itemsPedido = $itemsPedido->fetchAll(PDO::FETCH_ASSOC);

/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Pedidos', 'url' => '/admin/pedidos'],
    ['label' => 'Detalle', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">
        <h1>Pedido #<?= $pedido['order_id'] ?></h1>

        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?> (<?= $pedido['email'] ?>)</p>
        <p><strong>Fecha:</strong> <?= $pedido['fecha_pedido'] ?></p>
        <p><strong>Método de pago:</strong> <?= $pedido['metodo_pago'] ?></p>

        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto ID</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemsPedido as $i): ?>
                    <tr>
                        <td><?= $i['product_id'] ?></td>
                        <td><?= ($i['product_type'] == 1 ? 'Libro' : 'Producto') ?></td>
                        <td><?= $i['cantidad'] ?></td>
                        <td><?= number_format($i['precio_unitario'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="carrito-total">
            <strong>Total:</strong> <?= number_format($pedido['precio_total'], 2) ?> €
        </p>

    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
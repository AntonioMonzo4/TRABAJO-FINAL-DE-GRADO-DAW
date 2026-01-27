<?php
require_once __DIR__ . '/../CONTROLLER/AuthGuard.php';

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../MODEL/conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /login");
    exit;
}

$pdo = conexion::conexionBBDD();
$uid = $_SESSION['usuario']['id'];

$pedidosStmt = $pdo->prepare("
  SELECT *
  FROM orders
  WHERE user_id = :id
  ORDER BY fecha_pedido DESC
");
$pedidosStmt->execute([':id' => $uid]);
$pedidos = $pedidosStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="page">
    <section class="container">
        <h1>Mis pedidos</h1>

        <?php if (!$pedidos): ?>
            <p>No has realizado pedidos todavía.</p>
        <?php else: ?>

            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Pago</th>
                        <th>Estado</th>
                        <th>Detalle pago</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td>#<?= (int)$p['order_id'] ?></td>
                            <td><?= htmlspecialchars($p['fecha_pedido'] ?? '-') ?></td>
                            <td><?= number_format((float)$p['precio_total'], 2) ?> €</td>
                            <td><?= htmlspecialchars($p['metodo_pago'] ?? ($p['pago_tipo'] ?? '-')) ?></td>
                            <td><?= htmlspecialchars($p['estado'] ?? 'pendiente') ?></td>
                            <td><?= htmlspecialchars($p['pago_detalle'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
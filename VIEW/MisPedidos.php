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

$pedidos = $pdo->prepare("
  SELECT * FROM orders
  WHERE user_id = :id
  ORDER BY fecha_pedido DESC
");
$pedidos->execute([':id' => $uid]);
$pedidos = $pedidos->fetchAll(PDO::FETCH_ASSOC);
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
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td>#<?= $p['order_id'] ?></td>
                            <td><?= $p['fecha_pedido'] ?></td>
                            <td><?= number_format($p['precio_total'], 2) ?> €</td>
                            <td><?= $p['metodo_pago'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
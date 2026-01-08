<?php
require_once __DIR__ . '/header.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<main class="page">
  <section class="container">
    <h1>Mis pedidos</h1>

    <?php if (empty($pedidos)): ?>
      <p>No tienes pedidos todavía.</p>
      <a class="btn btn-primary" href="/tienda">Ir a la tienda</a>
    <?php else: ?>
      <div style="display:grid;gap:12px;">
        <?php foreach ($pedidos as $p): ?>
          <article class="card" style="padding:16px;border-radius:12px;">
            <p><strong>Pedido #<?= (int)$p['order_id'] ?></strong></p>
            <p>Total: <?= number_format((float)$p['precio_total'], 2) ?> €</p>
            <p>Método: <?= htmlspecialchars($p['metodo_pago'] ?? '-') ?></p>
            <p>Fecha: <?= htmlspecialchars($p['created_at'] ?? '-') ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div style="margin-top:14px;">
      <a class="btn btn-secondary" href="/perfil">Volver a mi perfil</a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

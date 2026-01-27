<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);
?>

<main class="page">
  <section class="container">
    <h1>Admin - Pedidos</h1>

    <?php if ($flash && !empty($flash['msg'])): ?>
      <div style="margin:10px 0;padding:12px;border-radius:10px; background: <?= ($flash['type'] ?? '') === 'error' ? '#ffe8e8' : '#e9f8ef' ?>;">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin: 12px 0;">
      <a class="btn btn-secondary" href="/admin">Volver al panel</a>
      <a class="btn btn-secondary" href="/admin/stock">Ver productos</a>
    </div>

    <div style="overflow:auto;">
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">ID</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Usuario</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Fecha</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Total</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Pago</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Detalle</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Estado</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $p): ?>
          <tr>
            <form method="post" action="/admin/pedidos/estado">
              <input type="hidden" name="order_id" value="<?= (int)$p['order_id'] ?>">

              <td style="padding:8px;border-bottom:1px solid #eee;">#<?= (int)$p['order_id'] ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= (int)$p['user_id'] ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($p['fecha_pedido'] ?? '-') ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= number_format((float)$p['precio_total'], 2) ?> €</td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($p['metodo_pago'] ?? '-') ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($p['pago_detalle'] ?? '-') ?></td>

              <td style="padding:8px;border-bottom:1px solid #eee;">
                <?php $est = $p['estado'] ?? 'pendiente'; ?>
                <select name="estado">
                  <option value="pendiente" <?= $est==='pendiente' ? 'selected' : '' ?>>Pendiente</option>
                  <option value="en_camino" <?= $est==='en_camino' ? 'selected' : '' ?>>En camino</option>
                  <option value="finalizado" <?= $est==='finalizado' ? 'selected' : '' ?>>Finalizado</option>
                </select>
              </td>

              <td style="padding:8px;border-bottom:1px solid #eee;">
                <button class="btn btn-primary" type="submit">Guardar</button>
              </td>
            </form>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

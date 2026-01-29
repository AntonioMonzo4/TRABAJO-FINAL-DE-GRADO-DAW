<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);
?>

<main class="page admin-page">
  <section class="container">
    <h1>Admin - Pedidos</h1>

    <?php if ($flash && !empty($flash['msg'])): ?>
      <div class="admin-flash <?= ($flash['type'] ?? '') === 'error' ? 'is-error' : 'is-ok' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div class="admin-actions">
      <a class="btn btn-secondary" href="/admin">Volver al panel</a>
      <a class="btn btn-secondary" href="/admin/stock">Ver productos</a>
      <a class="btn btn-secondary" href="/admin/usuarios">Usuarios</a>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Pago</th>
            <th>Detalle</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>

        <tbody>
        <?php foreach ($pedidos as $p): ?>
          <tr>
            <form method="post" action="/admin/pedidos/estado">
              <input type="hidden" name="order_id" value="<?= (int)$p['order_id'] ?>">

              <td data-label="ID">#<?= (int)$p['order_id'] ?></td>
              <td data-label="Usuario"><?= (int)$p['user_id'] ?></td>
              <td data-label="Fecha"><?= htmlspecialchars($p['fecha_pedido'] ?? '-') ?></td>
              <td data-label="Total"><?= number_format((float)$p['precio_total'], 2) ?> €</td>
              <td data-label="Pago"><?= htmlspecialchars($p['metodo_pago'] ?? '-') ?></td>
              <td data-label="Detalle"><?= htmlspecialchars($p['pago_detalle'] ?? '-') ?></td>

              <td data-label="Estado">
                <?php $est = $p['estado'] ?? 'pendiente'; ?>
                <select name="estado" class="admin-input">
                  <option value="pendiente"  <?= $est==='pendiente' ? 'selected' : '' ?>>Pendiente</option>
                  <option value="en_camino"  <?= $est==='en_camino' ? 'selected' : '' ?>>En camino</option>
                  <option value="finalizado" <?= $est==='finalizado' ? 'selected' : '' ?>>Finalizado</option>
                </select>
              </td>

              <td data-label="Acción">
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

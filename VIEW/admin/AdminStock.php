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
    <h1>Admin - Productos</h1>

    <?php if ($flash && !empty($flash['msg'])): ?>
      <div style="margin:10px 0;padding:12px;border-radius:10px; background: <?= ($flash['type'] ?? '') === 'error' ? '#ffe8e8' : '#e9f8ef' ?>;">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin: 12px 0;">
      <a class="btn btn-secondary" href="/admin">Volver al panel</a>
      <a class="btn btn-secondary" href="/admin/pedidos">Ver pedidos</a>
    </div>

    <h2>Libros</h2>
    <div style="overflow:auto;">
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">ID</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Título</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Autor</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Precio</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Stock</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($libros as $l): ?>
          <tr>
            <form method="post" action="/admin/stock/guardar">
              <input type="hidden" name="tipo" value="book">
              <input type="hidden" name="id" value="<?= (int)$l['book_id'] ?>">

              <td style="padding:8px;border-bottom:1px solid #eee;">#<?= (int)$l['book_id'] ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($l['titulo'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($l['autor'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;">
                <input name="precio" type="number" step="0.01" min="0" value="<?= (float)$l['precio'] ?>" style="width:110px;">
              </td>
              <td style="padding:8px;border-bottom:1px solid #eee;">
                <input name="stock" type="number" min="0" value="<?= (int)$l['stock'] ?>" style="width:90px;">
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

    <h2 style="margin-top:26px;">Otros productos</h2>
    <div style="overflow:auto;">
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">ID</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Nombre</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Precio</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Stock</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #ddd;">Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($otros as $o): ?>
          <tr>
            <form method="post" action="/admin/stock/guardar">
              <input type="hidden" name="tipo" value="other">
              <input type="hidden" name="id" value="<?= (int)$o['product_id'] ?>">

              <td style="padding:8px;border-bottom:1px solid #eee;">#<?= (int)$o['product_id'] ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;"><?= htmlspecialchars($o['nombre'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #eee;">
                <input name="precio" type="number" step="0.01" min="0" value="<?= (float)$o['precio'] ?>" style="width:110px;">
              </td>
              <td style="padding:8px;border-bottom:1px solid #eee;">
                <input name="stock" type="number" min="0" value="<?= (int)$o['stock'] ?>" style="width:90px;">
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

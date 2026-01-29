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
    <h1>Admin - Productos</h1>

    <?php if ($flash && !empty($flash['msg'])): ?>
      <div class="admin-flash <?= ($flash['type'] ?? '') === 'error' ? 'is-error' : 'is-ok' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div class="admin-actions">
      <a class="btn btn-secondary" href="/admin">Volver al panel</a>
      <a class="btn btn-secondary" href="/admin/pedidos">Ver pedidos</a>
      <a class="btn btn-secondary" href="/admin/usuarios">Usuarios</a>
    </div>

    <h2>Libros</h2>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($libros as $l): ?>
          <tr>
            <form method="post" action="/admin/stock/guardar">
              <input type="hidden" name="tipo" value="book">
              <input type="hidden" name="id" value="<?= (int)$l['book_id'] ?>">

              <td data-label="ID">#<?= (int)$l['book_id'] ?></td>
              <td data-label="Título"><?= htmlspecialchars($l['titulo'] ?? '') ?></td>
              <td data-label="Autor"><?= htmlspecialchars($l['autor'] ?? '') ?></td>

              <td data-label="Precio">
                <input name="precio" type="number" step="0.01" min="1" value="<?= (float)$l['precio'] ?>" class="admin-input admin-input--sm">
              </td>

              <td data-label="Stock">
                <input name="stock" type="number" min="0" value="<?= (int)$l['stock'] ?>" class="admin-input admin-input--xs">
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

    <h2 style="margin-top:26px;">Otros productos</h2>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($otros as $o): ?>
          <tr>
            <form method="post" action="/admin/stock/guardar">
              <input type="hidden" name="tipo" value="other">
              <input type="hidden" name="id" value="<?= (int)$o['product_id'] ?>">

              <td data-label="ID">#<?= (int)$o['product_id'] ?></td>
              <td data-label="Nombre"><?= htmlspecialchars($o['nombre'] ?? '') ?></td>

              <td data-label="Precio">
                <input name="precio" type="number" step="0.01" min="1" value="<?= (float)$o['precio'] ?>" class="admin-input admin-input--sm">
              </td>

              <td data-label="Stock">
                <input name="stock" type="number" min="0" value="<?= (int)$o['stock'] ?>" class="admin-input admin-input--xs">
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

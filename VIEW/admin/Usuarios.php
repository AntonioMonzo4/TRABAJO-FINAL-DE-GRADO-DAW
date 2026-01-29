<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

$usuarios = $pdo->query("
  SELECT user_id, nombre, email, rol, fecha_registro
  FROM users
  ORDER BY fecha_registro DESC
")->fetchAll(PDO::FETCH_ASSOC);

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Usuarios', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

$miId = (int)($_SESSION['usuario']['user_id'] ?? $_SESSION['usuario']['id'] ?? 0);
?>

<main class="page admin-page">
  <section class="container">
    <h1>Admin - Usuarios</h1>

    <?php if ($flash && !empty($flash['msg'])): ?>
      <div class="admin-flash <?= ($flash['type'] ?? '') === 'error' ? 'is-error' : 'is-ok' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <div class="admin-actions">
      <a class="btn btn-secondary" href="/admin">Volver al panel</a>
      <a class="btn btn-secondary" href="/admin/stock">Productos</a>
      <a class="btn btn-secondary" href="/admin/pedidos">Pedidos</a>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Registro</th>
            <th>Guardar</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
          <tr>
            <td data-label="ID"><?= (int)$u['user_id'] ?></td>
            <td data-label="Nombre"><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
            <td data-label="Email"><?= htmlspecialchars($u['email'] ?? '') ?></td>

            <td data-label="Rol">
              <form method="post" action="/admin/usuarios/guardar" class="admin-inline-form">
                <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
                <select name="rol" class="admin-input">
                  <option value="cliente" <?= (($u['rol'] ?? '') === 'cliente') ? 'selected' : '' ?>>Cliente</option>
                  <option value="admin"   <?= (($u['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </td>

            <td data-label="Registro"><?= htmlspecialchars($u['fecha_registro'] ?? '-') ?></td>

            <td data-label="Guardar">
                <button class="btn btn-primary" type="submit">Guardar</button>
              </form>
            </td>

            <td data-label="Eliminar">
              <?php if ((int)$u['user_id'] !== $miId): ?>
                <form method="post" action="/admin/usuarios/eliminar" onsubmit="return confirm('¿Eliminar este usuario?');">
                  <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
                  <button class="btn btn-secondary" type="submit">Eliminar</button>
                </form>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

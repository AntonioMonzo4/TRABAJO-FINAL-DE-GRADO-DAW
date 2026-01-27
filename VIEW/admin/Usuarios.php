<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

// usuarios: tu PK es user_id
$usuarios = $pdo->query("
  SELECT user_id, nombre, email, rol, fecha_registro
  FROM users
  ORDER BY fecha_registro DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Migas
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Usuarios', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';

// Flash
if (session_status() === PHP_SESSION_NONE) session_start();
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

$miId = (int)($_SESSION['usuario']['user_id'] ?? $_SESSION['usuario']['id'] ?? 0);
?>

<main class="page">
    <section class="container">
        <h1>Admin - Usuarios</h1>

        <?php if ($flash && !empty($flash['msg'])): ?>
            <div style="margin:10px 0;padding:12px;border-radius:10px; background: <?= ($flash['type'] ?? '') === 'error' ? '#ffe8e8' : '#e9f8ef' ?>;">
                <?= htmlspecialchars($flash['msg']) ?>
            </div>
        <?php endif; ?>

        <div style="overflow:auto;">
            <table class="tabla-carrito">
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
                            <td><?= (int)$u['user_id'] ?></td>
                            <td><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>

                            <td>
                                <form method="post" action="/admin/usuarios/guardar" style="display:flex; gap:10px; align-items:center;">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
                                    <select name="rol">
                                        <option value="cliente" <?= (($u['rol'] ?? '') === 'cliente') ? 'selected' : '' ?>>Cliente</option>
                                        <option value="admin" <?= (($u['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    </select>
                            </td>

                            <td><?= htmlspecialchars($u['fecha_registro'] ?? '-') ?></td>

                            <td>
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                </form>
                            </td>

                            <td>
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
<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

// Traer usuarios (fallback de columnas para evitar pantalla en blanco)
try {
    // Si tu tabla tiene user_id / fecha_registro
    $usuarios = $pdo->query("
      SELECT user_id AS id, nombre, email, rol, fecha_registro AS created_at
      FROM users
      ORDER BY fecha_registro DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e1) {
    try {
        // Esquema típico: id / created_at
        $usuarios = $pdo->query("
          SELECT id, nombre, email, rol, created_at
          FROM users
          ORDER BY created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e2) {
        // Si algo falla, mostramos el error (solo admin) en vez de pantalla blanca
        echo "<div style='padding:16px;background:#ffe8e8;border-radius:10px;margin:16px;'>
                Error cargando usuarios: " . htmlspecialchars($e2->getMessage()) . "
              </div>";
        require_once __DIR__ . '/../footer.php';
        exit;
    }
}

// Migas
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Usuarios', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';

// Flash (opcional)
if (session_status() === PHP_SESSION_NONE) session_start();
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

$miId = (int)($_SESSION['usuario']['id'] ?? 0);
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
                            <td><?= (int)$u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                            <td>
                                <form method="post" action="/admin/usuarios/guardar" style="display:flex; gap:10px; align-items:center;">
                                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                    <select name="rol">
                                        <option value="cliente" <?= (($u['rol'] ?? '') === 'cliente') ? 'selected' : '' ?>>Cliente</option>
                                        <option value="admin" <?= (($u['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    </select>
                            </td>
                            <td><?= htmlspecialchars($u['created_at'] ?? '-') ?></td>
                            <td>
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                </form>
                            </td>
                            <td>
                                <?php if ((int)$u['id'] !== $miId): ?>
                                    <form method="post" action="/admin/usuarios/eliminar" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
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
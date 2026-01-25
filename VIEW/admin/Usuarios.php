<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();

// Intento 1: esquema que estabas usando (user_id / fecha_registro)
try {
    $usuarios = $pdo->query("
      SELECT user_id, nombre, email, rol, fecha_registro
      FROM users
      ORDER BY fecha_registro DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Intento 2: esquema típico (id / created_at)
    $usuarios = $pdo->query("
      SELECT id AS user_id, nombre, email, rol, created_at AS fecha_registro
      FROM users
      ORDER BY created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Usuarios', 'url' => null]
];
require __DIR__ . '/../partials/breadcrumb.php';
?>

<main class="page">
    <section class="container">
        <h1>Usuarios</h1>

        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= (int)($u['user_id'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($u['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($u['rol'] ?? '') ?></td>
                        <td><?= htmlspecialchars($u['fecha_registro'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
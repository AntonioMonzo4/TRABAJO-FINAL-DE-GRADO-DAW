<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$usuarios = $pdo->query("
  SELECT user_id, nombre, email, rol, fecha_registro
  FROM users
  ORDER BY fecha_registro DESC
")->fetchAll(PDO::FETCH_ASSOC);

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
                        <td><?= $u['user_id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['rol'] ?></td>
                        <td><?= $u['fecha_registro'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
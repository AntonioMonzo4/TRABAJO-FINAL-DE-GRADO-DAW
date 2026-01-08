<?php
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$u = $_SESSION['usuario'] ?? null;
?>

<main class="page">
    <section class="container">
        <h1>Mi perfil</h1>

        <?php if (!$u): ?>
            <p>No has iniciado sesión.</p>
            <a class="btn btn-primary" href="/login">Ir a login</a>
        <?php else: ?>
            <div class="card" style="padding:16px;border-radius:12px;">
                <p><strong>Nombre:</strong> <?= htmlspecialchars(($u['nombre'] ?? '') . ' ' . ($u['apellidos'] ?? '')) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($u['email'] ?? '') ?></p>
                <p><strong>Rol:</strong> <?= htmlspecialchars($u['rol'] ?? '') ?></p>
            </div>

            <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
                <a class="btn btn-secondary" href="/mis-pedidos">Mis pedidos</a>
                <a class="btn btn-outline" href="/logout">Cerrar sesión</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
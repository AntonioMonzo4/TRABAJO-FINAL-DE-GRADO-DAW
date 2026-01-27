<?php
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Login', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';

/* Mensajes */
$loginError = $_SESSION['login_error'] ?? null;
if ($loginError) unset($_SESSION['login_error']);

$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);
?>

<main class="page">
<section class="container auth">

<h1>Iniciar sesión</h1>

<?php if ($loginError): ?>
    <div class="alert alert-error" style="margin: 10px 0; padding: 12px; border-radius: 10px; background:#ffe8e8;">
        <?= htmlspecialchars($loginError) ?>
    </div>
<?php endif; ?>

<?php if ($flash && is_array($flash) && !empty($flash['msg'])): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?>"
         style="margin: 10px 0; padding: 12px; border-radius: 10px; background:#eef6ff;">
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
<?php endif; ?>

<form method="POST" action="/login" class="form">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <button class="btn btn-primary" type="submit">Entrar</button>
</form>

</section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

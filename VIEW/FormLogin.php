<?php
// NO session_start aquí (ya está en header.php)
require_once __DIR__ . '/header.php';

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Login', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
<section class="container auth">

<h1>Iniciar sesión</h1>

<form method="post" action="/login" class="form">

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <button class="btn btn-primary" type="submit">
        Entrar
    </button>
</form>

</section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

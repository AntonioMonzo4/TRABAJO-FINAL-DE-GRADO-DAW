<?php
// VIEW/FormLogin.php
session_start();
$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>

<?php require_once __DIR__ . '/header.php'; ?>

<main class="contenedor formulario-auth">
    <h1>Iniciar sesión</h1>

    <?php if ($error): ?>
        <div class="alerta alerta-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="post" class="formulario">
        <div class="campo">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="btn btn-primario">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></p>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

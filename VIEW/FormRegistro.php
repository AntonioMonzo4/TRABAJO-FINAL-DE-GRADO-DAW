<?php
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Migas de pan (si las usas)
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Registro', 'url' => null],
];
if (file_exists(__DIR__ . '/partials/breadcrumb.php')) {
    require_once __DIR__ . '/partials/breadcrumb.php';
}

// Flash simple (si lo usas)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<main class="page">
    <section class="container auth">

        <h1>Crear cuenta</h1>

        <?php if ($flash): ?>
            <div class="alert <?= htmlspecialchars($flash['type'] ?? 'info') ?>">
                <?= htmlspecialchars($flash['msg'] ?? '') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/register" class="form" autocomplete="on">

            <div class="grid-2">
                <div>
                    <label>Nombre *</label>
                    <input
                        type="text" name="nombre" required maxlength="100"
                        pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,100}"
                        title="Solo letras y espacios (2-100)">
                </div>

                <div>
                    <label>Apellidos</label>
                    <input
                        type="text" name="apellidos" maxlength="150"
                        pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]{0,150}"
                        title="Solo letras y espacios">
                </div>
            </div>

            <label>Email *</label>
            <input type="email" name="email" required maxlength="150">

            <div class="grid-2">
                <div>
                    <label>Teléfono</label>
                    <input
                        type="text" name="telefono" maxlength="20"
                        pattern="[0-9+\-\s]{6,20}"
                        title="Solo números, espacios, + y - (6-20)">
                </div>

                <div>
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento">
                </div>
            </div>

            <label>Género</label>
            <select name="genero">
                <option value="">-- Selecciona --</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
                <option value="No especifica">No especifica</option>
                <option value="Otro">Otro</option>
            </select>

            <div class="grid-2">
                <div>
                    <label>Contraseña *</label>
                    <input
                        type="password" name="password" required minlength="10" maxlength="72"
                        pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{10,72}"
                        title="Mínimo 10 caracteres, con mayúscula, minúscula, número y símbolo">
                </div>

                <div>
                    <label>Repetir contraseña *</label>
                    <input type="password" name="password2" required minlength="10" maxlength="72">
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Registrarse</button>

            <p class="muted">¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>

        </form>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
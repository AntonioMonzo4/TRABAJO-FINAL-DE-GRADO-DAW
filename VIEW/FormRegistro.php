<?php
session_start();
$errores = $_SESSION['register_errors'] ?? [];
$old = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_errors'], $_SESSION['register_old']);
require_once __DIR__ . '/header.php';

$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Registro', 'url' => null],
];
require __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container auth">
        <h1>Registro</h1>

        <?php if ($errores): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="form" method="post" action="/register">
            <div class="grid-2">
                <div>
                    <label>Nombre*</label>
                    <input name="nombre" required value="<?= htmlspecialchars($old['nombre'] ?? '') ?>">
                </div>
                <div>
                    <label>Apellidos</label>
                    <input name="apellidos" value="<?= htmlspecialchars($old['apellidos'] ?? '') ?>">
                </div>
            </div>

            <label>Email*</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            <div class="grid-2">
                <div>
                    <label>Teléfono</label>
                    <input name="telefono" value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
                </div>
                <div>
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($old['fecha_nacimiento'] ?? '') ?>">
                </div>
            </div>

            <label>Género</label>
            <select name="genero">
                <option value="">-- Selecciona --</option>
                <?php foreach (['Hombre', 'Mujer', 'No especifica', 'Otro'] as $g): ?>
                    <option value="<?= $g ?>" <?= (($old['genero'] ?? '') === $g) ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>

            <div class="grid-2">
                <div>
                    <label>Contraseña*</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <label>Repetir contraseña*</label>
                    <input type="password" name="password2" required>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Crear cuenta</button>
            <p class="muted">¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
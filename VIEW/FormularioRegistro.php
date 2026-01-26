<?php
// VIEW/FormularioRegistro.php
session_start();
$errores = $_SESSION['register_errors'] ?? [];
$old     = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_errors'], $_SESSION['register_old']);
?>

<?php require_once __DIR__ . '/header.php'; ?>

<main class="page">
    <section class="container formulario-auth">

        <h1>Registro de usuario</h1>

        <?php if (!empty($errores)): ?>
            <div class="alerta alerta-error">
                <ul>
                    <?php foreach ($errores as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/register" method="post" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre*</label>
                <input type="text" name="nombre" id="nombre"
                    value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input type="text" name="apellidos" id="apellidos"
                    value="<?= htmlspecialchars($old['apellidos'] ?? '') ?>">
            </div>

            <div class="campo">
                <label for="email">Email*</label>
                <input type="email" name="email" id="email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                    value="<?= htmlspecialchars($old['fecha_nacimiento'] ?? '') ?>">
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono"
                    value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
            </div>

            <div class="campo">
                <label for="genero">Género</label>
                <select name="genero" id="genero">
                    <option value="">-- Selecciona --</option>
                    <option value="Hombre" <?= (($old['genero'] ?? '') === 'Hombre') ? 'selected' : '' ?>>Hombre</option>
                    <option value="Mujer" <?= (($old['genero'] ?? '') === 'Mujer') ? 'selected' : '' ?>>Mujer</option>
                    <option value="No especifica" <?= (($old['genero'] ?? '') === 'No especifica') ? 'selected' : '' ?>>No especifica</option>
                    <option value="Otro" <?= (($old['genero'] ?? '') === 'Otro') ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>

            <div class="campo">
                <label for="password">Contraseña*</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="campo">
                <label for="password2">Repetir contraseña*</label>
                <input type="password" name="password2" id="password2" required>
            </div>

            <!-- OJO: aquí estaba el “btn-primario” -->
            <button type="submit" class="btn btn-primary">Crear cuenta</button>
        </form>

        <p style="text-align:center;margin-top:14px;">
            ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
        </p>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
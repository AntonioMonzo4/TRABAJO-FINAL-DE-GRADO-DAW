<?php
require_once __DIR__ . '/header.php';

/* Si ya está logueado, fuera */
if (isset($_SESSION['usuario'])) {
    header("Location: /home");
    exit;
}

/* Migas de pan */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Registro', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
    <section class="container auth">

        <h1>Crear cuenta</h1>

        <form method="post" action="/register" class="form">

            <div class="grid-2">
                <div>
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required>
                </div>

                <div>
                    <label>Apellidos</label>
                    <input type="text" name="apellidos">
                </div>
            </div>

            <label>Email *</label>
            <input type="email" name="email" required>

            <div class="grid-2">
                <div>
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
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
                    <input type="password" name="password" required>
                </div>

                <div>
                    <label>Repetir contraseña *</label>
                    <input type="password" name="password2" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Registrarse
            </button>

            <p class="muted">
                ¿Ya tienes cuenta?
                <a href="/login">Inicia sesión</a>
            </p>

        </form>

    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
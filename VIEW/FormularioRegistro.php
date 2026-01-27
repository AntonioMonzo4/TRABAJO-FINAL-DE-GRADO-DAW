<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$errores = $_SESSION['register_errors'] ?? [];
$old     = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_errors'], $_SESSION['register_old']);

require_once __DIR__ . '/header.php';
?>

<main class="page">
    <section class="container auth-2col">

        <!-- MITAD IZQUIERDA -->
        <div class="auth-left">
            <div class="formulario-auth">
                <h1>Registro</h1>

                <?php if (!empty($errores)): ?>
                    <div class="alerta alerta-error">
                        <ul>
                            <?php foreach ($errores as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/register" method="post" class="formulario" novalidate>

                    <div class="campo">
                        <label for="nombre">Nombre*</label>
                        <input
                            type="text" name="nombre" id="nombre" required
                            value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                            pattern="^(?=.{2,60}$)[A-Za-zÀ-ÖØ-öø-ÿÑñ]+(?:[ '\-][A-Za-zÀ-ÖØ-öø-ÿÑñ]+)*$"
                            title="2–60 caracteres. Letras con acentos, espacios, apóstrofe o guion."
                            autocomplete="given-name">
                    </div>

                    <div class="campo">
                        <label for="apellidos">Apellidos</label>
                        <input
                            type="text" name="apellidos" id="apellidos"
                            value="<?= htmlspecialchars($old['apellidos'] ?? '') ?>"
                            pattern="^(?=.{2,80}$)[A-Za-zÀ-ÖØ-öø-ÿÑñ]+(?:[ '\-][A-Za-zÀ-ÖØ-öø-ÿÑñ]+)*$"
                            title="2–80 caracteres. Letras con acentos, espacios, apóstrofe o guion."
                            autocomplete="family-name">
                    </div>

                    <div class="campo">
                        <label for="email">Email*</label>
                        <input
                            type="email" name="email" id="email" required
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            autocomplete="email">
                    </div>

                    <div class="campo">
                        <label for="telefono">Teléfono</label>
                        <input
                            type="tel" name="telefono" id="telefono"
                            value="<?= htmlspecialchars($old['telefono'] ?? '') ?>"
                            inputmode="tel"
                            pattern="^(?:\+34[\s-]?)?(?:6|7|8|9)\d{8}$"
                            title="Teléfono ES válido: 9 dígitos. Puede empezar por +34."
                            autocomplete="tel">
                    </div>

                    <div class="campo">
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input
                            type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                            value="<?= htmlspecialchars($old['fecha_nacimiento'] ?? '') ?>"
                            min="1900-01-01"
                            max="<?= date('Y-m-d') ?>"
                            autocomplete="bday">
                    </div>

                    <div class="campo">
                        <label for="genero">Género</label>
                        <select name="genero" id="genero">
                            <option value="">-- Selecciona --</option>
                            <?php
                            $g = $old['genero'] ?? '';
                            foreach (['Hombre', 'Mujer', 'No especifica', 'Otro'] as $opt) {
                                $sel = ($g === $opt) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($opt) . "\" $sel>" . htmlspecialchars($opt) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="password">Contraseña*</label>
                        <input
                            type="password" name="password" id="password" required
                            pattern="^(?=.{10,64}$)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).*$"
                            title="10–64 caracteres con mayúscula, minúscula, número y símbolo."
                            autocomplete="new-password">
                    </div>

                    <div class="campo">
                        <label for="password2">Repetir contraseña*</label>
                        <input
                            type="password" name="password2" id="password2" required
                            autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary">Crear cuenta</button>
                </form>

                <p class="auth-footer">
                    ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
                </p>
            </div>
        </div>

        

    </section>
</main>

<script>
    // password2 = password
    (function() {
        const p1 = document.getElementById('password');
        const p2 = document.getElementById('password2');
        if (!p1 || !p2) return;

        function checkMatch() {
            if (p2.value && p1.value !== p2.value) p2.setCustomValidity('Las contraseñas no coinciden');
            else p2.setCustomValidity('');
        }
        p1.addEventListener('input', checkMatch);
        p2.addEventListener('input', checkMatch);
    })();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
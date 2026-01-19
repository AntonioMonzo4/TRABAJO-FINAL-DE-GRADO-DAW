<?php
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login");
    exit;
}

$u = $_SESSION['usuario'];

// Flash
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

/* Migas */
$items = [
    ['label' => 'Inicio', 'url' => '/home'],
    ['label' => 'Mi perfil', 'url' => null],
];
require_once __DIR__ . '/partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">
    <h1>Mi perfil</h1>

    <?php if ($flash && is_array($flash) && !empty($flash['msg'])): ?>
      <div style="margin:10px 0;padding:12px;border-radius:10px;background:#eef6ff;">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/perfil/actualizar" class="form" style="max-width:700px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div>
          <label>Nombre</label>
          <input type="text" name="nombre" value="<?= htmlspecialchars($u['nombre'] ?? '') ?>" required>
        </div>

        <div>
          <label>Apellidos</label>
          <input type="text" name="apellidos" value="<?= htmlspecialchars($u['apellidos'] ?? '') ?>">
        </div>

        <div>
          <label>Email</label>
          <input type="email" name="email" value="<?= htmlspecialchars($u['email'] ?? '') ?>" required>
        </div>

        <div>
          <label>Teléfono</label>
          <input type="text" name="telefono" value="<?= htmlspecialchars($u['telefono'] ?? '') ?>">
        </div>

        <div>
          <label>Género</label>
          <select name="genero">
            <?php $g = $u['genero'] ?? ''; ?>
            <option value="" <?= $g === '' ? 'selected' : '' ?>>--</option>
            <option value="H" <?= $g === 'H' ? 'selected' : '' ?>>Hombre</option>
            <option value="M" <?= $g === 'M' ? 'selected' : '' ?>>Mujer</option>
            <option value="O" <?= $g === 'O' ? 'selected' : '' ?>>Otro</option>
          </select>
        </div>

        <div>
          <label>Fecha de nacimiento</label>
          <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($u['fecha_nacimiento'] ?? '') ?>">
        </div>
      </div>

      <hr style="margin:18px 0;">

      <h3>Cambiar contraseña (opcional)</h3>
      <p class="muted" style="margin-top:0;">Si no quieres cambiarla, deja estos campos vacíos.</p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:520px;">
        <div>
          <label>Nueva contraseña</label>
          <input type="password" name="password_nueva" autocomplete="new-password">
        </div>
        <div>
          <label>Repite la nueva contraseña</label>
          <input type="password" name="password_nueva_2" autocomplete="new-password">
        </div>
      </div>

      <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-primary" type="submit">Guardar cambios</button>
        <a class="btn btn-secondary" href="/mis-pedidos">Mis pedidos</a>
        <a class="btn btn-outline" href="/logout">Cerrar sesión</a>
      </div>
    </form>

    <!-- Nota: NO mostramos el rol ni permitimos editarlo -->
  </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

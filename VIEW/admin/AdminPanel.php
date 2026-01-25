<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';
?>

<main class="page">
  <section class="container">
    <h1>Panel Administrador</h1>

    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:14px;">
      <a class="btn btn-primary" href="/admin/stock">Gestionar productos</a>
      <a class="btn btn-secondary" href="/admin/pedidos">Gestionar pedidos</a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

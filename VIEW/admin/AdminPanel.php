<?php
// Admin Panel - VIEW/admin/AdminPanel.php
// Muestra el panel de administración con opciones para gestionar productos, pedidos y usuarios.
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
AdminGuard::check();

require_once __DIR__ . '/../header.php';

$items = [
  ['label' => 'Inicio', 'url' => '/home'],
  ['label' => 'Admin', 'url' => null]
];

require_once __DIR__ . '/../partials/breadcrumb.php';
?>

<main class="page">
  <section class="container">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
      <div>
        <h1 style="margin-bottom:6px;">Panel Administrador</h1>
        <p class="muted" style="margin:0;">
          Gestión completa de la tienda
        </p>
      </div>

      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn btn-secondary" href="/home"><i class="fa-solid fa-house"></i> Ir a la web</a>
        <a class="btn btn-secondary" href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
      </div>
    </div>

    <!-- LOGO / IMAGEN -->
    <div style="margin:20px 0; display:flex; justify-content:center;">
      <img src="/VIEW/img/logo_principal.png" alt="Círculos de Atenea" style="max-width:200px; opacity:0.9;">
    </div>

    <!-- TARJETAS -->
    <div style="margin-top:18px; display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:14px;">

      <!-- Stock -->
      <a href="/admin/stock" style="text-decoration:none; color:inherit;">
        <div style="border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:16px; background:#fff;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.04);">
              <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
              <div style="font-weight:700;">Productos / Stock</div>
              <div class="muted" style="font-size:.95rem;">Editar precio y stock</div>
            </div>
          </div>
        </div>
      </a>

      <!-- Pedidos -->
      <a href="/admin/pedidos" style="text-decoration:none; color:inherit;">
        <div style="border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:16px; background:#fff;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.04);">
              <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
              <div style="font-weight:700;">Pedidos</div>
              <div class="muted" style="font-size:.95rem;">Cambiar estado</div>
            </div>
          </div>
        </div>
      </a>

      <!-- Usuarios -->
      <a href="/admin/usuarios" style="text-decoration:none; color:inherit;">
        <div style="border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:16px; background:#fff;">
          <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.04);">
              <i class="fa-solid fa-users"></i>
            </div>
            <div>
              <div style="font-weight:700;">Usuarios</div>
              <div class="muted" style="font-size:.95rem;">Gestionar cuentas</div>
            </div>
          </div>
        </div>
      </a>

    </div>

  </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
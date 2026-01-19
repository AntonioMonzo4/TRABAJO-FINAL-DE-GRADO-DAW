<?php
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
?>

<main class="page">
  <section class="container">
    <h1>Pedido realizado</h1>
    <p>Tu pedido se ha registrado correctamente.</p>

    <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
      <a class="btn btn-primary" href="/mis-pedidos">Ver mis pedidos</a>
      <a class="btn btn-secondary" href="/tienda">Volver a la tienda</a>
    </div>
  </section>
</main>

<!-- Vaciar carrito del navegador -->
<script>
    try {
        localStorage.removeItem('carrito_circulos_atenea');
    } catch (e) {
        console.warn('No se pudo limpiar el carrito:', e);
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>

<?php if (!empty($_SESSION['flash'])): ?>
  <!-- VIEW/partials/flash.php -->
   <!-- Mensaje flash -->
<div class="flash <?= $_SESSION['flash']['type'] ?>">
  <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<?php
if (!isset($items) || !is_array($items)) {
  return;
}
?>
<!-- VIEW/partials/breadcrumb.php -->
 <!-- Breadcrumb de navegación -->
<nav class="breadcrumb">
  <ol>
    <?php foreach ($items as $item): ?>
      <li>
        <?php if (!empty($item['url'])): ?>
          <a href="<?= htmlspecialchars($item['url']) ?>">
            <?= htmlspecialchars($item['label']) ?>
          </a>
        <?php else: ?>
          <span><?= htmlspecialchars($item['label']) ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>

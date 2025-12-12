<?php
// $items = [['label'=>'Inicio','url'=>'/home'], ['label'=>'Tienda','url'=>'/tienda'], ['label'=>'Libros','url'=>null]]
?>
<nav class="breadcrumb" aria-label="Migas de pan">
  <ol>
    <?php foreach ($items as $i => $it): ?>
      <li>
        <?php if (!empty($it['url']) && $i < count($items)-1): ?>
          <a href="<?= htmlspecialchars($it['url']) ?>"><?= htmlspecialchars($it['label']) ?></a>
        <?php else: ?>
          <span aria-current="page"><?= htmlspecialchars($it['label']) ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>

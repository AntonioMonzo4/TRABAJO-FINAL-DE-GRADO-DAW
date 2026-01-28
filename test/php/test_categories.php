<?php
// test/php/test_categories.php
$pdo = getPDO();

// Todas las categorías que uses como "genero_literario" deberían existir en categories.nombre
$missing = $pdo->query("
    SELECT DISTINCT b.genero_literario AS genero
    FROM books b
    LEFT JOIN categories c ON c.nombre = b.genero_literario
    WHERE b.genero_literario IS NOT NULL
      AND b.genero_literario <> ''
      AND c.category_id IS NULL
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

assertTrue(count($missing) === 0, "Faltan categorías para géneros: " . implode(', ', array_map(fn($r) => $r['genero'], $missing)));

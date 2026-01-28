<?php
// test/php/test_stock.php
$pdo = getPDO();

$neg = $pdo->query("SELECT book_id, stock FROM books WHERE stock < 0 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
assertTrue($neg === false, "Hay stock negativo en book_id=" . ($neg['book_id'] ?? '') . " stock=" . ($neg['stock'] ?? ''));

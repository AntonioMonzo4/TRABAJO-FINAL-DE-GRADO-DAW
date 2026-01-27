<?php
// test/php/test_stock.php

require_once __DIR__ . '/../../MODEL/conexion.php';
$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'password');


$neg = $pdo->query("SELECT book_id, stock FROM books WHERE stock < 0 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
assertTrue($neg === false, "Hay stock negativo en book_id=" . ($neg['book_id'] ?? '') . " stock=" . ($neg['stock'] ?? ''));

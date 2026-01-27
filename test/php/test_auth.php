<?php
// test/php/test_auth.php

require_once __DIR__ . '/../../MODEL/conexion.php';

function assertTrue($condition, $message = '') {
	if (!$condition) {
		throw new Exception("Assertion failed: " . $message);
	}
}

$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'user', 'password');

// 1) comprobar que hay columna password_hash y que se usa hash
$row = $pdo->query("SELECT password_hash FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
assertTrue($row !== false, "No hay usuarios en tabla users para probar");
$hash = $row['password_hash'] ?? '';
assertTrue($hash !== '', "password_hash vacío");

// típico hash empieza por $2y$ (bcrypt) o $argon2
assertTrue(strpos($hash, '$2y$') === 0 || strpos($hash, '$argon2') === 0, "password_hash no parece hash válido");

// 2) comprobar duplicados email (no debería haber)
$dup = $pdo->query("SELECT email, COUNT(*) c FROM users GROUP BY email HAVING c > 1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
assertTrue($dup === false, "Hay emails duplicados: " . ($dup['email'] ?? ''));

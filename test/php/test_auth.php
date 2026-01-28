
<?php
// test/php/test_auth.php
$pdo = getPDO();

// Debe existir al menos un usuario
$row = $pdo->query("SELECT password_hash FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
assertTrue($row !== false, "No hay usuarios en tabla users para probar");

$hash = (string)($row['password_hash'] ?? '');
assertTrue($hash !== '', "password_hash vacío");

// bcrypt ($2y$) o argon2 ($argon2...)
assertTrue(
	str_starts_with($hash, '$2y$') || str_starts_with($hash, '$argon2'),
	"password_hash no parece hash válido: " . substr($hash, 0, 10)
);

// No debería haber emails duplicados
$dup = $pdo->query("
    SELECT email, COUNT(*) AS c
    FROM users
    GROUP BY email
    HAVING c > 1
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

assertTrue($dup === false, "Hay emails duplicados: " . ($dup['email'] ?? ''));

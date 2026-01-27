<?php
// test/php/TestRunner.php
require_once __DIR__ . '/bootstrap.php';

$tests = glob(__DIR__ . '/test_*.php');

$ok = 0; $fail = 0;
foreach ($tests as $file) {
    try {
        require $file;
        echo "✅ " . basename($file) . PHP_EOL;
        $ok++;
    } catch (Throwable $e) {
        echo "❌ " . basename($file) . " -> " . $e->getMessage() . PHP_EOL;
        $fail++;
    }
}
echo PHP_EOL . "RESULT: OK=$ok FAIL=$fail" . PHP_EOL;

exit($fail > 0 ? 1 : 0);

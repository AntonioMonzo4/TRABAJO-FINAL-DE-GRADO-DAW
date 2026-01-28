<?php
// test/php/bootstrap.php

require_once __DIR__ . '/../../MODEL/conexion.php';

function getPDO(): PDO {
    return conexion::conexionBBDD();
}

function assertTrue($cond, string $msg = 'Assertion failed'): void {
    if (!$cond) throw new Exception($msg);
}

function assertEq($a, $b, string $msg = 'Not equal'): void {
    if ($a !== $b) {
        throw new Exception($msg . " | got=" . var_export($a, true) . " expected=" . var_export($b, true));
    }
}

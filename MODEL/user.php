<?php
// MODEL/User.php

require_once __DIR__ . '/conexion.php';

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexion::conexionBBDD();
    }

    /** Obtener usuario por email */
    public function getByEmail(string $email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Obtener usuario por ID */
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Crear usuario */
    public function crear(array $data)
    {
        $sql = "INSERT INTO users
        (nombre, apellidos, email, fecha_nacimiento, telefono, genero, password_hash, rol)
        VALUES
        (:nombre, :apellidos, :email, :fecha_nacimiento, :telefono, :genero, :password_hash, :rol)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nombre'           => $data['nombre'],
            ':apellidos'        => $data['apellidos'] ?? null,
            ':email'            => $data['email'],
            ':fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            ':telefono'         => $data['telefono'] ?? null,
            ':genero'           => $data['genero'] ?? null,
            ':password_hash'    => $data['password_hash'],
            ':rol'              => $data['rol'] ?? 'cliente'
        ]);
    }

    /** Comprobar si existe el email */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (bool)$stmt->fetch();
    }
}

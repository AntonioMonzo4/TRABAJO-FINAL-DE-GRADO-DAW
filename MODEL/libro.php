<?php
// MODEL/Libro.php

require_once __DIR__ . '/conexion.php';

// Clase para manejar operaciones relacionadas con libros en la base de datos
class Libro
{
    // Conexión PDO
    private $pdo;

    // Constructor para inicializar la conexión PDO
    public function __construct()
    {
        $this->pdo = conexion::conexionBBDD();
    }

    // Función para obtener todos los libros
    public function obtenerTodos()
    {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY titulo ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para obtener un libro por su ID
    public function obtenerPorId(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE book_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Función para obtener X libros aleatorios (destacados)
    public function obtenerAleatorios(int $cantidad = 8)
    {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY RAND() LIMIT $cantidad");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para crear un nuevo libro
    public function crear(array $data)
    {
        $sql = "INSERT INTO books
        (titulo, descripcion, autor, precio, imagen, genero_literario, stock)
        VALUES
        (:titulo, :descripcion, :autor, :precio, :imagen, :genero_literario, :stock)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':titulo'           => $data['titulo'],
            ':descripcion'      => $data['descripcion'] ?? null,
            ':autor'            => $data['autor'] ?? null,
            ':precio'           => $data['precio'],
            ':imagen'           => $data['imagen'] ?? null,
            ':genero_literario' => $data['genero_literario'] ?? null,
            ':stock'            => $data['stock'] ?? 0
        ]);
    }

    // Función para actualizar un libro existente
    public function actualizar(int $id, array $data)
    {
        $sql = "UPDATE books SET
            titulo = :titulo,
            descripcion = :descripcion,
            autor = :autor,
            precio = :precio,
            imagen = :imagen,
            genero_literario = :genero_literario,
            stock = :stock
        WHERE book_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':titulo'           => $data['titulo'],
            ':descripcion'      => $data['descripcion'] ?? null,
            ':autor'            => $data['autor'] ?? null,
            ':precio'           => $data['precio'],
            ':imagen'           => $data['imagen'] ?? null,
            ':genero_literario' => $data['genero_literario'] ?? null,
            ':stock'            => $data['stock'] ?? 0,
            ':id'               => $id
        ]);
    }

    // Función para eliminar un libro por su ID
    public function eliminar(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE book_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

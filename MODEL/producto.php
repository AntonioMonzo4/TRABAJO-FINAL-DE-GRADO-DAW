<?php
// MODEL/Producto.php

require_once __DIR__ . '/conexion.php';

class Producto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexion::conexionBBDD();
    }

    /** Obtener todos los productos */
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM other_products ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Obtener producto por ID */
    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM other_products WHERE product_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Crear producto */
    public function crear(array $data)
    {
        $sql = "INSERT INTO other_products 
        (category_id, nombre, descripcion, precio, imagen, stock)
        VALUES
        (:category_id, :nombre, :descripcion, :precio, :imagen, :stock)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio'      => $data['precio'],
            ':imagen'      => $data['imagen'] ?? null,
            ':stock'       => $data['stock'] ?? 0
        ]);
    }

    /** Actualizar producto */
    public function actualizar(int $id, array $data)
    {
        $sql = "UPDATE other_products SET
            category_id = :category_id,
            nombre      = :nombre,
            descripcion = :descripcion,
            precio      = :precio,
            imagen      = :imagen,
            stock       = :stock
        WHERE product_id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio'      => $data['precio'],
            ':imagen'      => $data['imagen'] ?? null,
            ':stock'       => $data['stock'] ?? 0,
            ':id'          => $id
        ]);
    }

    /** Eliminar */
    public function borrar(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM other_products WHERE product_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

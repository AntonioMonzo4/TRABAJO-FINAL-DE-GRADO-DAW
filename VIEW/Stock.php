<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$libros = $pdo->query("SELECT book_id, titulo, precio, stock FROM books ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
$otros  = $pdo->query("SELECT product_id, nombre, precio, stock FROM other_products ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="page">
    <section class="container">
        <h1>Gestión de stock</h1>

        <h2>Libros</h2>
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($libros as $l): ?>
                    <tr>
                        <form method="post" action="/admin/stock/book">
                            <td><?= htmlspecialchars($l['titulo']) ?></td>
                            <td><input type="number" step="0.01" name="precio" value="<?= (float)$l['precio'] ?>"></td>
                            <td><input type="number" name="stock" value="<?= (int)$l['stock'] ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?= (int)$l['book_id'] ?>">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="margin-top:30px;">Otros productos</h2>
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($otros as $p): ?>
                    <tr>
                        <form method="post" action="/admin/stock/other">
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><input type="number" step="0.01" name="precio" value="<?= (float)$p['precio'] ?>"></td>
                            <td><input type="number" name="stock" value="<?= (int)$p['stock'] ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?= (int)$p['product_id'] ?>">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>

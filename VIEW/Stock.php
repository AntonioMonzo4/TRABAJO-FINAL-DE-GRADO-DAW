<?php
require_once __DIR__ . '/../../CONTROLLER/AdminGuard.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../MODEL/conexion.php';

$pdo = conexion::conexionBBDD();
$libros = $pdo->query("SELECT * FROM books ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="page">
    <section class="container">
        <h1>Gestión de stock</h1>

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
                        <form method="post" action="/admin/stock/edit">
                            <td><?= htmlspecialchars($l['titulo']) ?></td>
                            <td>
                                <input type="number" step="0.01" name="precio" value="<?= $l['precio'] ?>">
                            </td>
                            <td>
                                <input type="number" name="stock" value="<?= $l['stock'] ?>">
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= $l['book_id'] ?>">
                                <button class="btn btn-primary">Guardar</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . '/../footer.php'; ?>
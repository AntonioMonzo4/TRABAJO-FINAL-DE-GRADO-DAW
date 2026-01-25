<?php
require_once __DIR__ . '/../CONTROLLER/AuthGuard.php';
require_once __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Flash (por si llegas aquí con un error)
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

/**
 * Si llegamos desde "Proceder al pago", vendrá POST cart_json desde localStorage.
 * Lo convertimos a $_SESSION['carrito'] para que PedidoController::crear() lo use.
 */
if ($method === 'POST' && isset($_POST['cart_json'])) {
    $raw = (string)$_POST['cart_json'];
    $decoded = json_decode($raw, true);

    if (is_array($decoded)) {
        $_SESSION['carrito'] = [];
        foreach ($decoded as $item) {
            if (!is_array($item)) continue;

            $id = isset($item['id']) ? (string)$item['id'] : '';
            $tipo = isset($item['tipo']) ? (string)$item['tipo'] : ''; // 'book' o 'other'
            $nombre = isset($item['nombre']) ? (string)$item['nombre'] : '';
            $autor = isset($item['autor']) ? (string)$item['autor'] : null;
            $precio = isset($item['precio']) ? (float)$item['precio'] : 0.0;
            $imagen = isset($item['imagen']) ? (string)$item['imagen'] : '';
            $cantidad = isset($item['cantidad']) ? (int)$item['cantidad'] : 1;

            if ($id === '' || ($tipo !== 'book' && $tipo !== 'other')) continue;
            if ($cantidad < 1) $cantidad = 1;

            // Normalizamos a lo que usa PedidoController (type/id/cantidad/precio)
            $_SESSION['carrito'][] = [
                'type' => $tipo,
                'id' => $id,
                'nombre' => $nombre,
                'autor' => $autor,
                'precio' => $precio,
                'imagen' => $imagen,
                'cantidad' => $cantidad,
            ];
        }
    }
}

// Si no hay carrito en sesión, no hay nada que confirmar
$carritoSesion = $_SESSION['carrito'] ?? [];
if (!$carritoSesion) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'No hay productos para tramitar el pago.'];
    header("Location: /carrito");
    exit;
}

// Total para mostrar
$total = 0.0;
foreach ($carritoSesion as $i) {
    $total += ((float)($i['precio'] ?? 0)) * ((int)($i['cantidad'] ?? 1));
}
?>

<main class="page">
    <section class="container">
        <h1>Checkout</h1>

        <?php if ($flash && !empty($flash['msg'])): ?>
            <div style="margin:10px 0;padding:12px;border-radius:10px; background: <?= ($flash['type'] ?? '') === 'error' ? '#ffe8e8' : '#e9f8ef' ?>;">
                <?= htmlspecialchars($flash['msg']) ?>
            </div>
        <?php endif; ?>

        <p><strong>Total a pagar:</strong> <?= number_format($total, 2) ?> €</p>

        <form method="post" action="/pedido/crear">

            <label for="pago_tipo">Método de pago</label>
            <select id="pago_tipo" name="pago_tipo">
                <option value="tarjeta">Tarjeta</option>
                <option value="paypal">PayPal</option>
                <option value="transferencia">Transferencia</option>
            </select>

            <div id="pago-tarjeta" style="margin-top:12px;">
                <label>Titular</label>
                <input type="text" name="card_name" placeholder="Nombre y apellidos">

                <label>Número de tarjeta</label>
                <input type="text" name="card_number" inputmode="numeric" placeholder="4111 1111 1111 1111">

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <div>
                        <label>Caducidad (MM/AA)</label>
                        <input type="text" name="card_exp" placeholder="12/29" style="width:120px;">
                    </div>
                    <div>
                        <label>CVV</label>
                        <input type="password" name="card_cvv" placeholder="123" style="width:90px;">
                    </div>
                </div>

                <p class="muted" style="margin-top:8px;">
                    Simulación: no se realiza ningún cargo real. No guardamos el CVV ni el número completo.
                </p>
            </div>

            <div id="pago-paypal" style="margin-top:12px; display:none;">
                <label>Email de PayPal</label>
                <input type="email" name="paypal_email" placeholder="tuemail@ejemplo.com">
            </div>

            <div id="pago-transferencia" style="margin-top:12px; display:none;">
                <p class="muted">Simulación: el pedido quedará “pendiente”.</p>
                <label>Referencia de transferencia (opcional)</label>
                <input type="text" name="transfer_ref" placeholder="REF-12345">
            </div>

            <div style="margin-top:18px; display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Confirmar pedido</button>
                <a href="/carrito" class="btn btn-secondary">Volver al carrito</a>
            </div>
        </form>
    </section>
</main>

<script>
    const sel = document.getElementById('pago_tipo');
    const tarjeta = document.getElementById('pago-tarjeta');
    const paypal = document.getElementById('pago-paypal');
    const transf = document.getElementById('pago-transferencia');

    function updatePagoUI() {
        const v = sel.value;
        tarjeta.style.display = v === 'tarjeta' ? 'block' : 'none';
        paypal.style.display = v === 'paypal' ? 'block' : 'none';
        transf.style.display = v === 'transferencia' ? 'block' : 'none';
    }

    sel.addEventListener('change', updatePagoUI);
    updatePagoUI();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
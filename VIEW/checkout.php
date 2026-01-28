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
        <div class="checkout">
            <div class="checkout__header">
                <h1>Checkout</h1>
            </div>

            <?php if ($flash && !empty($flash['msg'])): ?>
                <div class="alert <?= ($flash['type'] ?? '') === 'error' ? 'alert--error' : 'alert--ok' ?>">
                    <?= htmlspecialchars($flash['msg']) ?>
                </div>
            <?php endif; ?>

            <div class="checkout__grid">
                <!-- Columna principal -->
                <div class="checkout__card">
                    <h3>Pago</h3>

                    <form id="form-checkout" method="post" action="/pedido/crear" novalidate>

                        <div class="field">
                            <label for="pago_tipo">Método de pago</label>
                            <select id="pago_tipo" name="pago_tipo" class="input">
                                <option value="tarjeta" selected>Tarjeta</option>
                                <option value="paypal">PayPal</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>

                        <!-- TARJETA -->
                        <div id="pago-tarjeta" class="paybox">
                            <div class="field">
                                <label for="card_name">Titular</label>
                                <input
                                    id="card_name"
                                    class="input"
                                    type="text"
                                    name="card_name"
                                    placeholder="Nombre y apellidos"
                                    autocomplete="cc-name"
                                    inputmode="text"
                                    data-required="1"
                                    data-pattern="^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+(?:[ ][A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+)+$">
                                <small class="hint">Solo letras y espacios. Debe incluir nombre y apellido.</small>
                            </div>

                            <div class="field">
                                <label for="card_number">Número de tarjeta</label>
                                <input
                                    id="card_number"
                                    class="input"
                                    type="text"
                                    name="card_number"
                                    placeholder="4111 1111 1111 1111"
                                    autocomplete="cc-number"
                                    inputmode="numeric"
                                    data-required="1"
                                    data-pattern="^[0-9 ]{13,23}$">
                                <small class="hint">13–19 dígitos (puede incluir espacios).</small>
                            </div>

                            <div class="row">
                                <div class="field">
                                    <label for="card_exp">Caducidad (MM/AA)</label>
                                    <input
                                        id="card_exp"
                                        class="input"
                                        type="text"
                                        name="card_exp"
                                        placeholder="12/29"
                                        autocomplete="cc-exp"
                                        inputmode="numeric"
                                        data-required="1"
                                        data-pattern="^(0[1-9]|1[0-2])\/([0-9]{2})$">
                                    <small class="hint">Formato MM/AA.</small>
                                </div>

                                <div class="field">
                                    <label for="card_cvv">CVV</label>
                                    <input
                                        id="card_cvv"
                                        class="input"
                                        type="password"
                                        name="card_cvv"
                                        placeholder="123"
                                        autocomplete="cc-csc"
                                        inputmode="numeric"
                                        maxlength="4"
                                        data-required="1"
                                        data-pattern="^[0-9]{3,4}$">
                                    <small class="hint">3 o 4 dígitos.</small>
                                </div>
                            </div>
                        </div>

                        <!-- PAYPAL -->
                        <div id="pago-paypal" class="paybox" style="display:none;">
                            <div class="field">
                                <label for="paypal_email">Email de PayPal</label>
                                <input
                                    id="paypal_email"
                                    class="input"
                                    type="email"
                                    name="paypal_email"
                                    placeholder="tuemail@ejemplo.com"
                                    autocomplete="email"
                                    data-required="1"
                                    data-pattern="^[^\s@]+@[^\s@]+\.[^\s@]{2,}$">
                                <small class="hint">Introduce un email válido.</small>
                            </div>
                        </div>

                        <!-- TRANSFERENCIA -->
                        <div id="pago-transferencia" class="paybox" style="display:none;">
                            <div class="field">
                                <label for="transfer_ref">Código / Referencia de transferencia</label>
                                <input
                                    id="transfer_ref"
                                    class="input"
                                    type="text"
                                    name="transfer_ref"
                                    placeholder="REF-12345"
                                    data-required="1"
                                    data-pattern="^[A-Za-z0-9-]{4,30}$">
                                <small class="hint">Obligatorio. Letras, números y guiones (4–30).</small>
                            </div>
                        </div>

                        <div id="checkout-error" class="alert alert--error" style="display:none;"></div>

                        <button class="btn btn-primary" type="submit">Confirmar pedido</button>
                    </form>
                </div>

                <!-- Resumen -->
                <aside class="checkout__summary">
                    <h3>Resumen</h3>
                    <div class="sumrow">
                        <span>Total a pagar</span>
                        <strong><?= number_format($total, 2) ?> €</strong>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<script>
    (function() {
        const form = document.getElementById('form-checkout');
        const sel = document.getElementById('pago_tipo');
        const err = document.getElementById('checkout-error');

        const boxes = {
            tarjeta: document.getElementById('pago-tarjeta'),
            paypal: document.getElementById('pago-paypal'),
            transferencia: document.getElementById('pago-transferencia')
        };

        function setBox(mode) {
            // Mostrar/ocultar
            Object.keys(boxes).forEach(k => {
                boxes[k].style.display = (k === mode) ? '' : 'none';
            });

            // Deshabilitar inputs de secciones ocultas para que NO validen
            Object.keys(boxes).forEach(k => {
                boxes[k].querySelectorAll('input, select, textarea').forEach(el => {
                    el.disabled = (k !== mode);
                });
            });

            // Activar required/pattern solo en la sección activa (usando data-*)
            const active = boxes[mode];
            active.querySelectorAll('input').forEach(el => {
                const req = el.getAttribute('data-required') === '1';
                const pat = el.getAttribute('data-pattern');
                if (req) el.setAttribute('required', 'required');
                else el.removeAttribute('required');
                if (pat) el.setAttribute('pattern', pat);
                else el.removeAttribute('pattern');
            });

            err.style.display = 'none';
            err.textContent = '';
        }

        // Validación adicional: caducidad no puede estar en el pasado
        function isExpiryValid(mmYY) {
            const m = mmYY.match(/^(0[1-9]|1[0-2])\/([0-9]{2})$/);
            if (!m) return false;
            const mm = parseInt(m[1], 10);
            const yy = parseInt(m[2], 10);
            const now = new Date();
            const curYY = now.getFullYear() % 100;
            const curMM = now.getMonth() + 1;
            if (yy < curYY) return false;
            if (yy === curYY && mm < curMM) return false;
            return true;
        }

        function showError(msg) {
            err.textContent = msg;
            err.style.display = '';
            err.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        sel.addEventListener('change', () => setBox(sel.value));

        form.addEventListener('submit', (e) => {
            err.style.display = 'none';
            err.textContent = '';

            // HTML5 pattern/required
            if (!form.checkValidity()) {
                e.preventDefault();
                showError('Revisa los campos del método de pago. Hay datos inválidos o incompletos.');
                return;
            }

            // Extra checks
            if (sel.value === 'tarjeta') {
                const exp = document.getElementById('card_exp').value.trim();
                if (!isExpiryValid(exp)) {
                    e.preventDefault();
                    showError('La caducidad de la tarjeta no es válida o está vencida.');
                    return;
                }

                const name = document.getElementById('card_name').value.trim();
                // refuerzo: nombre + apellido y solo letras/espacios (ya lo hace pattern, esto es extra)
                if (!/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+(?:[ ][A-Za-zÁÉÍÓÚÜÑáéíóúüñ]+)+$/.test(name)) {
                    e.preventDefault();
                    showError('El titular debe contener solo letras y espacios, e incluir nombre y apellido.');
                    return;
                }

                // Tarjeta: permitir espacios pero comprobar longitud real 13-19
                const num = document.getElementById('card_number').value.replace(/\s+/g, '');
                if (num.length < 13 || num.length > 19) {
                    e.preventDefault();
                    showError('El número de tarjeta debe tener entre 13 y 19 dígitos.');
                    return;
                }
            }

            if (sel.value === 'transferencia') {
                const ref = document.getElementById('transfer_ref').value.trim();
                if (!/^[A-Za-z0-9-]{4,30}$/.test(ref)) {
                    e.preventDefault();
                    showError('La referencia de transferencia es obligatoria y debe tener 4–30 caracteres (letras/números/guión).');
                    return;
                }
            }
        });

        // Inicial
        setBox(sel.value);
    })();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
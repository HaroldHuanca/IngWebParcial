<?php
session_start();
include 'includes/conexion.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = intval($_SESSION['user_id']);
$mensaje = "";
$tipo_mensaje = "";

// Obtener órdenes pendientes del usuario
$sql_orders = "SELECT * FROM orders WHERE user_id = ? AND payment_status = 'pending'";
$stmt = $con->prepare($sql_orders);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_orders = $stmt->get_result();
$ordenes_pendientes = [];

while ($order = $result_orders->fetch_assoc()) {
    $ordenes_pendientes[] = $order;
}

// Procesar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_pago'])) {
    $order_id = intval($_POST['order_id'] ?? 0);
    $metodo_pago = trim($_POST['metodo_pago'] ?? '');
    
    // Validar que el order_id pertenezca al usuario
    $sql_check = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
    $stmt = $con->prepare($sql_check);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result_check = $stmt->get_result();
    
    if ($result_check->num_rows === 0) {
        $mensaje = "Pedido no encontrado o no tienes permiso para pagarlo.";
        $tipo_mensaje = "error";
    } else {
        // Obtener información del pedido
        $sql_order = "SELECT total_amount FROM orders WHERE order_id = ?";
        $stmt_order = $con->prepare($sql_order);
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();
        $result_order = $stmt_order->get_result();
        $order_data = $result_order->fetch_assoc();
        $total_amount = $order_data['total_amount'];
        
        // Validar método de pago
        if ($metodo_pago === 'paypal') {
            $paypal_email = trim($_POST['paypal_email'] ?? '');
            
            // Validar que sea numérico (simulación de PayPal)
            if (empty($paypal_email) || !is_numeric($paypal_email)) {
                $mensaje = "Por favor ingresa un valor numérico válido para PayPal.";
                $tipo_mensaje = "error";
            } else {
                // Procesar pago PayPal (simulado)
                $sql_update = "UPDATE orders SET payment_status = 'completed', status = 'processing' WHERE order_id = ?";
                $stmt = $con->prepare($sql_update);
                $stmt->bind_param("i", $order_id);
                
                if ($stmt->execute()) {
                    // Registrar transacción de pago
                    $payment_reference = "PAYPAL_" . $order_id . "_" . time();
                    $sql_transaction = "INSERT INTO payment_transactions (order_id, user_id, payment_method, amount, status, payment_reference, notes) 
                                       VALUES (?, ?, 'paypal', ?, 'completed', ?, ?)";
                    $stmt_trans = $con->prepare($sql_transaction);
                    $notes = "Pago simulado en sandbox. Billetera: " . htmlspecialchars($paypal_email);
                    $stmt_trans->bind_param("iidss", $order_id, $user_id, $total_amount, $payment_reference, $notes);
                    $stmt_trans->execute();
                    
                    $mensaje = "¡Pago realizado exitosamente con PayPal! Tu pedido está siendo procesado.";
                    $tipo_mensaje = "success";
                    
                    // Redirigir después de 2 segundos
                    header("refresh:2;url=miPerfil.php");
                } else {
                    $mensaje = "Error al procesar el pago. Por favor intenta de nuevo.";
                    $tipo_mensaje = "error";
                }
            }
        } elseif ($metodo_pago === 'tarjeta') {
            $numero_tarjeta = trim($_POST['numero_tarjeta'] ?? '');
            $cvv = trim($_POST['cvv'] ?? '');
            
            // Validar que sean numéricos
            if (empty($numero_tarjeta) || !is_numeric($numero_tarjeta)) {
                $mensaje = "Por favor ingresa un número de tarjeta válido (solo números).";
                $tipo_mensaje = "error";
            } elseif (empty($cvv) || !is_numeric($cvv)) {
                $mensaje = "Por favor ingresa un CVV válido (solo números).";
                $tipo_mensaje = "error";
            } else {
                // Procesar pago con tarjeta (simulado)
                $sql_update = "UPDATE orders SET payment_status = 'completed', status = 'processing' WHERE order_id = ?";
                $stmt = $con->prepare($sql_update);
                $stmt->bind_param("i", $order_id);
                
                if ($stmt->execute()) {
                    // Registrar transacción de pago
                    $payment_reference = "VISA_" . $order_id . "_" . time();
                    $sql_transaction = "INSERT INTO payment_transactions (order_id, user_id, payment_method, amount, status, payment_reference, notes) 
                                       VALUES (?, ?, 'tarjeta_visa', ?, 'completed', ?, ?)";
                    $stmt_trans = $con->prepare($sql_transaction);
                    $ultimos_digitos = substr($numero_tarjeta, -4);
                    $notes = "Pago simulado en sandbox. Tarjeta terminada en: " . htmlspecialchars($ultimos_digitos);
                    $stmt_trans->bind_param("iidss", $order_id, $user_id, $total_amount, $payment_reference, $notes);
                    $stmt_trans->execute();
                    
                    $mensaje = "¡Pago realizado exitosamente con Tarjeta Visa! Tu pedido está siendo procesado.";
                    $tipo_mensaje = "success";
                    
                    // Redirigir después de 2 segundos
                    header("refresh:2;url=miPerfil.php");
                } else {
                    $mensaje = "Error al procesar el pago. Por favor intenta de nuevo.";
                    $tipo_mensaje = "error";
                }
            }
        } else {
            $mensaje = "Método de pago no válido.";
            $tipo_mensaje = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container my-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4" style="color: var(--texto-principal);">Procesamiento de Pagos</h2>

                <?php if ($mensaje): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: '<?php echo $tipo_mensaje === "success" ? "success" : "error"; ?>',
                                title: '<?php echo $tipo_mensaje === "success" ? "¡Éxito!" : "¡Error!"; ?>',
                                text: '<?php echo htmlspecialchars($mensaje); ?>',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#89B0AE',
                            });
                        });
                    </script>
                <?php endif; ?>

                <?php if (empty($ordenes_pendientes)): ?>
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        No tienes pedidos pendientes de pago. <a href="miPerfil.php">Volver a mis pedidos</a>
                    </div>
                <?php else: ?>
                    <!-- Seleccionar Pedido -->
                    <div class="card mb-4" style="border-color: var(--fondo-4);">
                        <div class="card-header" style="background-color: var(--fondo-3); color: var(--dark-text);">
                            <h5 class="mb-0">Selecciona un Pedido para Pagar</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($ordenes_pendientes as $order): ?>
                                    <button type="button" class="list-group-item list-group-item-action" 
                                            data-order-id="<?php echo htmlspecialchars($order['order_id']); ?>"
                                            data-order-amount="<?php echo htmlspecialchars($order['total_amount']); ?>"
                                            onclick="seleccionarPedido(this)">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Pedido #<?php echo htmlspecialchars($order['order_id']); ?></h6>
                                                <small class="text-muted">Fecha: <?php echo htmlspecialchars(date('d/m/Y', strtotime($order['order_date']))); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <strong style="color: var(--texto-principal);">S/ <?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></strong>
                                                <br>
                                                <small class="badge bg-warning">Pendiente</small>
                                            </div>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Pago -->
                    <form method="POST" id="formPago">
                        <input type="hidden" name="procesar_pago" value="1">
                        <input type="hidden" name="order_id" id="order_id" value="">

                        <!-- Información del Pedido Seleccionado -->
                        <div class="card mb-4" style="border-color: var(--fondo-4);" id="infoPedido" style="display: none;">
                            <div class="card-header" style="background-color: var(--fondo-3); color: var(--dark-text);">
                                <h5 class="mb-0">Información del Pedido</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Número de Pedido:</strong> #<span id="pedidoNum"></span></p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Monto Total:</strong> S/ <span id="pedidoMonto"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seleccionar Método de Pago -->
                        <div class="card mb-4" style="border-color: var(--fondo-4);">
                            <div class="card-header" style="background-color: var(--fondo-3); color: var(--dark-text);">
                                <h5 class="mb-0">Método de Pago</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="metodo_pago" id="paypal" value="paypal" onchange="mostrarFormularioPago()">
                                    <label class="form-check-label" for="paypal">
                                        <i class="bi bi-paypal me-2"></i>PayPal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta" onchange="mostrarFormularioPago()">
                                    <label class="form-check-label" for="tarjeta">
                                        <i class="bi bi-credit-card me-2"></i>Tarjeta Visa
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario PayPal -->
                        <div id="formPayPal" style="display: none;">
                            <div class="card mb-4" style="border-color: var(--fondo-4);">
                                <div class="card-header" style="background-color: var(--fondo-3); color: var(--dark-text);">
                                    <h5 class="mb-0">Datos de PayPal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="paypal_email" class="form-label">Billetera Virtual (Sandbox)</label>
                                        <input type="text" class="form-control" id="paypal_email" name="paypal_email" 
                                               placeholder="Ingresa un valor numérico" 
                                               pattern="[0-9]+" 
                                               title="Solo se aceptan números">
                                        <small class="text-muted">Ingresa solo números para simular el pago en sandbox</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario Tarjeta -->
                        <div id="formTarjeta" style="display: none;">
                            <div class="card mb-4" style="border-color: var(--fondo-4);">
                                <div class="card-header" style="background-color: var(--fondo-3); color: var(--dark-text);">
                                    <h5 class="mb-0">Datos de la Tarjeta Visa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="numero_tarjeta" class="form-label">Número de Tarjeta (Sandbox)</label>
                                        <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" 
                                               placeholder="Ingresa solo números" 
                                               pattern="[0-9]+" 
                                               title="Solo se aceptan números">
                                        <small class="text-muted">Ingresa solo números para simular el pago en sandbox</small>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                                <input type="month" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cvv" class="form-label">CVV (Sandbox)</label>
                                                <input type="text" class="form-control" id="cvv" name="cvv" 
                                                       placeholder="Ingresa solo números" 
                                                       pattern="[0-9]{3,4}" 
                                                       title="Solo se aceptan números (3-4 dígitos)">
                                                <small class="text-muted">Ingresa solo números para simular el pago en sandbox</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn" style="background-color: var(--fondo-4); color: var(--light-text); flex: 1;" id="btnPagar" disabled>
                                <i class="bi bi-credit-card me-2"></i>Procesar Pago
                            </button>
                            <a href="miPerfil.php" class="btn btn-outline-secondary" style="flex: 1;">
                                <i class="bi bi-arrow-left me-2"></i>Volver
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        function seleccionarPedido(elemento) {
            // Remover clase activa de todos los elementos
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Agregar clase activa al elemento seleccionado
            elemento.classList.add('active');
            
            // Obtener datos del pedido
            const orderId = elemento.dataset.orderId;
            const orderAmount = elemento.dataset.orderAmount;
            
            // Actualizar formulario
            document.getElementById('order_id').value = orderId;
            document.getElementById('pedidoNum').textContent = orderId;
            document.getElementById('pedidoMonto').textContent = parseFloat(orderAmount).toFixed(2);
            
            // Mostrar información del pedido
            document.getElementById('infoPedido').style.display = 'block';
            
            // Limpiar selección de método de pago
            document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Ocultar formularios de pago
            document.getElementById('formPayPal').style.display = 'none';
            document.getElementById('formTarjeta').style.display = 'none';
            
            // Deshabilitar botón de pago
            document.getElementById('btnPagar').disabled = true;
        }

        function mostrarFormularioPago() {
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
            
            // Ocultar ambos formularios
            document.getElementById('formPayPal').style.display = 'none';
            document.getElementById('formTarjeta').style.display = 'none';
            
            // Mostrar el formulario correspondiente
            if (metodoPago === 'paypal') {
                document.getElementById('formPayPal').style.display = 'block';
            } else if (metodoPago === 'tarjeta') {
                document.getElementById('formTarjeta').style.display = 'block';
            }
            
            // Habilitar botón de pago
            document.getElementById('btnPagar').disabled = false;
        }

        // Validar que solo se ingresen números en los campos
        document.getElementById('paypal_email').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        document.getElementById('cvv').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validar formulario antes de enviar
        document.getElementById('formPago').addEventListener('submit', function(e) {
            const orderId = document.getElementById('order_id').value;
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked');
            
            if (!orderId) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona un Pedido',
                    text: 'Por favor selecciona un pedido para continuar.',
                    confirmButtonColor: '#89B0AE'
                });
                return false;
            }
            
            if (!metodoPago) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona un Método de Pago',
                    text: 'Por favor selecciona un método de pago válido.',
                    confirmButtonColor: '#89B0AE'
                });
                return false;
            }
            
            // Validar campos según el método de pago seleccionado
            if (metodoPago.value === 'paypal') {
                const paypalEmail = document.getElementById('paypal_email').value.trim();
                if (!paypalEmail || !paypalEmail.match(/^[0-9]+$/)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos Inválidos',
                        text: 'Por favor ingresa un valor numérico válido para PayPal.',
                        confirmButtonColor: '#89B0AE'
                    });
                    return false;
                }
            } else if (metodoPago.value === 'tarjeta') {
                const numeroTarjeta = document.getElementById('numero_tarjeta').value.trim();
                const fechaVencimiento = document.getElementById('fecha_vencimiento').value.trim();
                const cvv = document.getElementById('cvv').value.trim();
                
                if (!numeroTarjeta || !numeroTarjeta.match(/^[0-9]+$/)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos Inválidos',
                        text: 'Por favor ingresa un número de tarjeta válido (solo números).',
                        confirmButtonColor: '#89B0AE'
                    });
                    return false;
                }
                
                if (!fechaVencimiento) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos Inválidos',
                        text: 'Por favor ingresa la fecha de vencimiento de la tarjeta.',
                        confirmButtonColor: '#89B0AE'
                    });
                    return false;
                }
                
                if (!cvv || !cvv.match(/^[0-9]{3,4}$/)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos Inválidos',
                        text: 'Por favor ingresa un CVV válido (3-4 dígitos numéricos).',
                        confirmButtonColor: '#89B0AE'
                    });
                    return false;
                }
            }
        });
    </script>
</body>
</html>

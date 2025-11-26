<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/conexion.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Obtener el order_id de la URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    header('Location: miPerfil.php');
    exit();
}

$order_id = intval($_GET['order_id']);

// Obtener información del pedido
$sql_order = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $con->prepare($sql_order);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result_order = $stmt->get_result();

if (!$result_order || $result_order->num_rows === 0) {
    header('Location: miPerfil.php');
    exit();
}

$order = $result_order->fetch_assoc();

// Obtener items del pedido
$sql_items = "SELECT oi.*, b.title, b.image_extension 
              FROM order_items oi
              JOIN books b ON oi.book_id = b.book_id
              WHERE oi.order_id = ?";
$stmt = $con->prepare($sql_items);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result_items = $stmt->get_result();
$items = [];
$total_items = 0;

if ($result_items && $result_items->num_rows > 0) {
    while ($item = $result_items->fetch_assoc()) {
        $items[] = $item;
        $total_items += $item['quantity'];
    }
}

// Obtener autor de cada libro
$authors = [];
foreach ($items as $item) {
    $book_id = intval($item['book_id']);
    $sql_author = "SELECT CONCAT(a.first_name, ' ', a.last_name) AS author_name
                   FROM authors a
                   JOIN book_authors ba ON a.author_id = ba.author_id
                   WHERE ba.book_id = ?";
    $stmt = $con->prepare($sql_author);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result_author = $stmt->get_result();
    $authors[$book_id] = ($result_author && $result_author->num_rows > 0)
        ? $result_author->fetch_assoc()['author_name']
        : "Autor desconocido";
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>
    <!-- Detalle del Pedido -->
    <main class="container my-5">
        <section class="featured-login py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-4">Detalle del Pedido #<?php echo $order_id; ?></h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="miPerfil.php" class="btn btn-secondary">Volver a mis pedidos</a>
            </div>
        </div>

        <!-- Información del Pedido -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Información del Pedido</h5>
                    <div class="mb-2">
                        <strong>Número de Pedido:</strong> #<?php echo htmlspecialchars($order_id); ?>
                    </div>
                    <div class="mb-2">
                        <strong>Fecha:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($order['order_date']))); ?>
                    </div>
                    <div class="mb-2">
                        <strong>Estado:</strong>
                        <span class="badge bg-<?php 
                            echo ($order['status'] === 'pending') ? 'warning' : 
                                 (($order['status'] === 'completed') ? 'success' : 
                                  (($order['status'] === 'cancelled') ? 'danger' : 'info'));
                        ?>">
                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>Estado de Pago:</strong>
                        <span class="badge bg-<?php 
                            echo ($order['payment_status'] === 'pending') ? 'warning' : 
                                 (($order['payment_status'] === 'completed') ? 'success' : 
                                  (($order['payment_status'] === 'failed') ? 'danger' : 'info'));
                        ?>">
                            <?php echo htmlspecialchars(ucfirst($order['payment_status'])); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Dirección de Envío</h5>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                </div>
            </div>
        </div>
        </section>

        <!-- Tabla de Items -->
        <section class="featured-products py-5 mt-3">
            <h5 class="mb-3">Libros en este Pedido</h5>
            <div class="table-responsive carrito-tabla">
                <table class="table align-middle" id="orderTable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Precio Unitario</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <!-- Item del pedido -->
                            <tr class="order-item">
                                <td class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars("books/" . $item['image_extension']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="80" class="me-3">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                        <div class="text-muted">Autor: <?php echo htmlspecialchars($authors[intval($item['book_id'])]); ?></div>
                                    </div>
                                </td>
                                <td class="text-center">S/ <?php echo htmlspecialchars(number_format($item['price_at_time'], 2)); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td class="text-center">S/ <?php echo htmlspecialchars(number_format($item['quantity'] * $item['price_at_time'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resumen del Pedido -->
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <div class="card p-3">
                        <h5>Resumen del Pedido</h5>
                        <div class="d-flex justify-content-between my-2">
                            <div>Subtotal</div>
                            <div>S/ <span><?php echo htmlspecialchars(number_format($order['total_amount'] - $order['shipping_cost'], 2)); ?></span></div>
                        </div>
                        <div class="d-flex justify-content-between my-2">
                            <div>Envío</div>
                            <div>S/ <span><?php echo htmlspecialchars(number_format($order['shipping_cost'], 2)); ?></span></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <div>Total</div>
                            <div>S/ <span><?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></span></div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Total de artículos: <?php echo htmlspecialchars($total_items); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Botones de Acción -->
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="miPerfil.php" class="btn btn-secondary">Volver a mis pedidos</a>
                <a href="Catalogo.php" class="btn featured-btn">Continuar comprando</a>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>

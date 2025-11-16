<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>
    <!-- Carrito -->
    <main class="container my-5">
        <h2 class="mb-4">Tu carrito</h2>
        <section class="featured-products py-5">
            <div class="table-responsive carrito-tabla">
                <table class="table align-middle" id="cartTable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_SESSION['cart'])) {
                            $cart = $_SESSION['cart'];
                        } else {
                            $cart = array();
                        }
                        $total = 0;
                        foreach ($cart as $book_id => $item):

                            // Obtener el título del libro
                            $sqlTitle = "SELECT title FROM books WHERE book_id = $book_id;";
                            echo $sqlTitle;
                            $resultTitle = $con->query($sqlTitle);
                            $title = ($resultTitle && $resultTitle->num_rows > 0)
                                ? $resultTitle->fetch_assoc()['title']
                                : "Título desconocido";
                            // Obtener la direccion de la portada del libro
                            $sqlTitle = "SELECT cover_image_url FROM books WHERE book_id = $book_id;";
                            $resultCover = $con->query($sqlTitle);
                            $cover = ($resultCover && $resultCover->num_rows > 0)
                                ? $resultCover->fetch_assoc()['cover_image_url']
                                : "Imagen desconocido";
                            // Obtener el nombre completo del autor
                            $sqlAuthor = "SELECT CONCAT(a.first_name, ' ', a.last_name) AS author_name
                                            FROM authors a
                                            JOIN book_authors ba ON a.author_id = ba.author_id
                                            WHERE ba.book_id = $book_id";
                            $resultAuthor = $con->query($sqlAuthor);
                            $author = ($resultAuthor && $resultAuthor->num_rows > 0)
                                ? $resultAuthor->fetch_assoc()['author_name']
                                : "Autor desconocido";

                            // Datos del carrito
                            $quantity = $item['quantity'];
                            $price = $item['price_at_time'];
                            $subtotal = $quantity * $price;
                            $total += $subtotal;
                        ?>
                            <!-- Item del carrito -->
                            <tr class="cart-item" data-price="<?php echo $price; ?>">
                                <td class="d-flex align-items-center">
                                    <img src="<?php echo $cover; ?>" alt="<?php echo htmlspecialchars($title); ?>" width="80" class="me-3">
                                    <div>
                                        <strong><?php echo htmlspecialchars($title); ?></strong>
                                        <div class="text-muted">Autor: <?php echo htmlspecialchars($author); ?></div>
                                    </div>
                                </td>
                                <td class="text-center">S/ <span class="item-price"><?php echo number_format($price, 2); ?></span></td>
                                <td class="text-center">
                                    <input type="number" class="form-control quantity-input" value="<?php echo $quantity; ?>" min="1" style="width:90px;margin:0 auto;">
                                </td>
                                <td class="text-center">S/ <span class="item-subtotal"><?php echo number_format($subtotal, 2); ?></span></td>
                                <td class="text-end">
                                    <a href="carrito.php?eliminar=<?php echo $book_id; ?>" class="featured-btn-eliminar">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Datos para el pedido</h5>
                    <form id="orderForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección de envío</label>
                            <input type="text" id="address" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" id="phone" class="form-control" required>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="card p-3">
                        <h5>Resumen del pedido</h5>
                        <div class="d-flex justify-content-between my-2">
                            <div>Subtotal</div>
                            <div>S/ <span id="subtotalValue"><?php echo $total; ?></span></div>
                        </div>
                        <div class="d-flex justify-content-between my-2">
                            <div>Envío</div>
                            <div>S/ <span id="shippingValue">10.00</span></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <div>Total</div>
                            <div>S/ <span id="totalValue"><?php echo ($total + 10); ?></span></div>
                        </div>
                        <div class="mt-3 text-end">
                            <button id="checkoutBtn" class="btn featured-btn">Realizar pedido</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php if (isset($_SESSION['eliminado']) && $_SESSION['eliminado']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto Eliminado!',
                    text: 'Se han quitado los libros del carrito exitosamente.',
                    confirmButtonText: 'Sigue personalizando tu carrito',
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });

                // Limpiar la bandera de sesión
                <?php unset($_SESSION['eliminado']); ?>
            });
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['eliminar']) && $_GET['eliminar']):
                $book_id = $_GET['eliminar'] ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas eliminar este producto del carrito?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige a la página PHP
                        window.location.href = "eliminar.php?book_id=<?php echo $book_id; ?> ";
                    } else {
                        // Si presiona "No"
                        Swal.fire({
                            title: 'Operación cancelada',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });

            });
        </script>
    <?php endif; ?>
</body>

</html>
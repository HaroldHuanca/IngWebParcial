<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/conexion.php';

if(isset($_GET['book_id']) && isset($_GET['quantity'])) {
    $book_id = intval($_GET['book_id']);
    $quantity = intval($_GET['quantity']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (isset($_SESSION['cart'][$book_id])) {
        // Actualizar la cantidad en la sesión
        $_SESSION['cart'][$book_id]['quantity'] = $quantity;

        if ($user_id) {
            // Actualizar la cantidad en la base de datos
            $sql = "UPDATE cart_items ci
                    JOIN shopping_carts sc ON ci.cart_id = sc.cart_id
                    SET ci.quantity = $quantity
                    WHERE sc.user_id = $user_id AND ci.book_id = $book_id;";
            $con->query($sql);
        }
    }
    // Redirigir de vuelta al carrito
    header('Location: carrito.php');
    exit();
}
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
                            $book_id = intval($book_id);

                            // Obtener el título del libro
                            $sqlTitle = "SELECT title FROM books WHERE book_id = ?;";
                            $stmtTitle = $con->prepare($sqlTitle);
                            $stmtTitle->bind_param("i", $book_id);
                            $stmtTitle->execute();
                            $resultTitle = $stmtTitle->get_result();
                            $title = ($resultTitle && $resultTitle->num_rows > 0)
                                ? $resultTitle->fetch_assoc()['title']
                                : "Título desconocido";
                            // Obtener la direccion de la portada del libro
                            $sqlCover = "SELECT image_extension FROM books WHERE book_id = ?;";
                            $stmtCover = $con->prepare($sqlCover);
                            $stmtCover->bind_param("i", $book_id);
                            $stmtCover->execute();
                            $resultCover = $stmtCover->get_result();
                            $cover = ($resultCover && $resultCover->num_rows > 0)
                                ? "books/" . $book_id . '.' . $resultCover->fetch_assoc()['image_extension']
                                : "Imagen desconocido";
                            // Obtener el nombre completo del autor
                            $sqlAuthor = "SELECT CONCAT(a.first_name, ' ', a.last_name) AS author_name
                                            FROM authors a
                                            JOIN book_authors ba ON a.author_id = ba.author_id
                                            WHERE ba.book_id = ?";
                            $stmtAuthor = $con->prepare($sqlAuthor);
                            $stmtAuthor->bind_param("i", $book_id);
                            $stmtAuthor->execute();
                            $resultAuthor = $stmtAuthor->get_result();
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
                            <tr class="cart-item" data-book-id="<?php echo $book_id; ?>" data-price="<?php echo $price; ?>">
                                <td class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($cover); ?>" alt="<?php echo htmlspecialchars($title); ?>" width="80" class="me-3">
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

                </div>
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
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
                            <?php
                            if ($total <= 0) {
                                // Carrito vacío → enviar al catálogo
                                $url = 'Catalogo.php?compra=true';
                            } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
                                // Usuario logueado → pasar a pedido
                                $url = 'pedido.php?total_amount=' . ($total + 10);
                            } else {
                                // Usuario no logueado → mandar al carrito con mensaje
                                $url = 'carrito.php?mensaje=true';
                            }
                            ?>
                            <div class="mt-3 text-end">
                                <a href="<?= $url ?>" id="checkoutBtn" class="btn featured-btn">
                                    Realizar pedido
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <!-- Mensaje cuando el carrito está vacío -->
        <div id="noCartItems" class="text-center py-5" style="display: none;">
            <i class="bi bi-cart-x fs-1 text-muted"></i>
            <h3 class="mt-3">Tu carrito está vacío</h3>
            <p class="text-muted">Agrega libros desde nuestro catálogo para continuar con tu compra.</p>
            <a href="Catalogo.php" class="featured-btn mt-3">Explorar Catálogo</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cartTableBody = document.querySelector("#cartTable tbody");
            const noCartMessage = document.getElementById("noCartItems");
            const cartTable = document.querySelector(".carrito-tabla");

            function updateCartView() {
                const hasItems = cartTableBody.querySelectorAll(".cart-item").length > 0;

                if (hasItems) {
                    cartTable.style.display = "block";
                    noCartMessage.style.display = "none";
                } else {
                    cartTable.style.display = "none";
                    noCartMessage.style.display = "block";
                }
            }

            // Cuando se elimina un item por el botón "Eliminar"
            document.querySelectorAll(".featured-btn-eliminar").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    // No prevenimos la navegación porque tu botón elimina vía GET (PHP)
                    // Pero igualmente actualizamos la vista antes de recargar
                    const row = this.closest(".cart-item");
                    row.remove();
                    updateCartView();
                });
            });

            // Verificar estado inicial al cargar la página
            updateCartView();
        });
    </script>
    <script>
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const row = this.closest('.cart-item');
                const bookId = row.getAttribute('data-book-id');
                const quantity = this.value;

                // Redirigir con GET
                window.location.href = `carrito.php?book_id=${bookId}&quantity=${quantity}`;
            });
        });
    </script>
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
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({
                    title: 'Importante',
                    text: "Para completar tu pedido, por favor crea una cuenta.\nEsto nos permite brindarte un mejor seguimiento y seguridad en tus compras.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, crea una cuenta',
                    cancelButtonText: 'No, cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige a la página PHP
                        window.location.href = "registro.php";
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
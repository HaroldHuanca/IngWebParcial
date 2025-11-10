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
                                    <button class="featured-btn-eliminar remove-item" data-id="<?php echo $book_id; ?>">Eliminar</button>
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
                            <div>S/ <span id="subtotalValue">219.90</span></div>
                        </div>
                        <div class="d-flex justify-content-between my-2">
                            <div>Envío</div>
                            <div>S/ <span id="shippingValue">10.00</span></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <div>Total</div>
                            <div>S/ <span id="totalValue">229.90</span></div>
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

    <script>
        // Actualiza subtotales y totales
        function updateTotals() {
            const rows = document.querySelectorAll('.cart-item');
            let subtotal = 0;
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                const price = parseFloat(row.dataset.price) || 0;
                const qtyInput = row.querySelector('.quantity-input');
                const qty = Math.max(1, parseInt(qtyInput.value, 10) || 1);
                const itemSubtotal = price * qty;
                row.querySelector('.item-subtotal').textContent = itemSubtotal.toFixed(2);
                subtotal += itemSubtotal;
            });

            const shipping = subtotal > 0 ? 10.00 : 0.00; // tarifa fija de ejemplo
            const total = subtotal + shipping;

            document.getElementById('subtotalValue').textContent = subtotal.toFixed(2);
            document.getElementById('shippingValue').textContent = shipping.toFixed(2);
            document.getElementById('totalValue').textContent = total.toFixed(2);
        }

        // Inicializa controladores
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', () => {
                if (parseInt(input.value, 10) < 1 || isNaN(parseInt(input.value, 10))) input.value = 1;
                updateTotals();
            });
        });

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const row = e.target.closest('tr');
                row.style.display = 'none';
                updateTotals();
            });
        });

        document.getElementById('checkoutBtn').addEventListener('click', () => {
            const subtotal = parseFloat(document.getElementById('subtotalValue').textContent) || 0;
            if (subtotal <= 0) {
                alert('Tu carrito está vacío. Agrega productos antes de realizar el pedido.');
                return;
            }

            const form = document.getElementById('orderForm');
            if (!form.reportValidity()) return;

            const name = document.getElementById('name').value;
            const address = document.getElementById('address').value;
            const phone = document.getElementById('phone').value;
            const total = document.getElementById('totalValue').textContent;

            // En una app real aquí enviaríamos los datos al servidor.
            alert(`Pedido realizado\n\nCliente: ${name}\nDirección: ${address}\nTel: ${phone}\nTotal: S/ ${total}`);
        });

        // Calcular totales al cargar
        updateTotals();
    </script>

</body>

</html>
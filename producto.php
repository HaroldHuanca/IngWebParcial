<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion.php'; ?>

<?php
// Verificar si se recibió el ID por la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = intval($_GET['id']);
    
    // Consultar los datos del libro
    $stmt = $con->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "<div class='container my-5'><h3>Libro no encontrado.</h3></div>";
        include 'includes/footer.php';
        exit;
    }
} else {
    echo "<div class='container my-5'><h3>Parámetro inválido.</h3></div>";
    include 'includes/footer.php';
    exit;
}
?>

    <!-- Producto -->
    <main class="container my-5 bg-fondo-1">
        <div class="row">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($book['cover_image_url']); ?>" alt="Portada del libro <?php echo htmlspecialchars($book['title']); ?>" class="img-fluid">
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="mt-3"><?php echo htmlspecialchars($book['title']); ?></h2>
                <p class="text-muted">Autor: <?php echo htmlspecialchars($book['author']); ?></p>

                <div class="mb-3">
                    <span class="h4 fw-bold">S/ <?php echo number_format($book['price'], 2); ?></span>
                    <small class="text-muted d-block">
                        <?php echo ($book['stock'] > 0) ? 'En stock' : 'Agotado'; ?>
                    </small>
                </div>

                <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>

                <form id="addToCartForm" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="quantity" class="col-form-label">Cantidad</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?php echo $book['stock']; ?>" style="width:100px">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="button" id="addToCartBtn" class="btn featured-btn"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Añadir al carrito</button>
                    </div>
                </form>

                <hr class="my-4">

                <h6>Detalles del libro</h6>
                <ul>
                    <li>Formato: <?php echo htmlspecialchars($book['format']); ?></li>
                    <li>Páginas: <?php echo htmlspecialchars($book['page_count']); ?></li>
                    <li>Idioma: <?php echo htmlspecialchars($book['language']); ?></li>
                </ul>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Manejo simple de agregar al carrito 
        document.getElementById('addToCartBtn').addEventListener('click', function () {
            const qty = parseInt(document.getElementById('quantity').value, 10) || 1;
            alert('Añadido ' + qty + ' unidad(es) al carrito.');
        });
    </script>

</body>
</html>

<?php
session_start();
?>
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
                <div class="product-img">
                    <img src="<?php echo htmlspecialchars($book['cover_image_url']); ?>" alt="Portada del libro <?php echo htmlspecialchars($book['title']); ?>" class="img-fluid">
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="mt-3"><?php echo htmlspecialchars($book['title']); ?></h2>
                <?php
                    $sql = "SELECT a.first_name, a.last_name 
                            FROM authors a
                            JOIN book_authors ba ON a.author_id = ba.author_id
                            WHERE ba.book_id = {$book['book_id']}";
                    $result = $con->query($sql);
                    $author = $result->fetch_assoc();
                ?>
                <p class="text-muted">Autor: <?php echo htmlspecialchars($author['first_name']." ".$author['last_name']); ?></p>

                <div class="mb-3">
                    <span class="h4 fw-bold">S/ <?php echo number_format($book['price'], 2); ?></span>
                    <small class="text-muted d-block">
                        <?php echo ($book['stock'] > 0) ? 'En stock' : 'Agotado'; ?>
                    </small>
                </div>

                <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>

                <div class="d-flex gap-2 align-items-center mt-4">
                    <a href="añadir.php?id=<?php echo $book['book_id']; ?>&precio=<?php echo $book['price']; ?>&envio=producto.php?id=<?php echo $book['book_id']; ?>" class="btn featured-btn">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i> Añadir al carrito
                    </a>
                    <?php
                    if (isset($_SESSION['user_id'])):
                        $user_id = $_SESSION['user_id'];
                        $book_id = $book['book_id'];
                        $sqlFav = "SELECT * from favorites where user_id = ? and book_id = ?;";
                        $stmtFav = $con->prepare($sqlFav);
                        $stmtFav->bind_param("ii", $user_id, $book_id);
                        $stmtFav->execute();
                        $resultFav = $stmtFav->get_result();
                    ?>
                        <a href="alternar.php?user=<?php echo $user_id; ?>&book=<?php echo $book_id ?>&eliminar=<?php echo ($resultFav->num_rows > 0);?>&envio='producto.php?id=<?php echo $book_id; ?>'" class="btn" style="border: 2px solid var(--texto-principal); background-color: transparent; padding: 0.5rem 1rem;">
                            <i class="bi bi-star<?php echo ($resultFav->num_rows > 0 ? '-fill text-warning' : ''); ?> fs-4"></i>
                        </a>
                    <?php endif; ?>
                </div>

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

    <?php if (isset($_SESSION['show_alert']) && $_SESSION['show_alert']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto añadido!',
                    text: 'El libro se ha añadido al carrito correctamente',
                    confirmButtonText: 'Continuar comprando',
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });

                // Limpiar la bandera de sesión
                <?php unset($_SESSION['show_alert']); ?>
            });
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg']): 
            $msg = isset($_GET['msg']) ? $_GET['msg'] : '';?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cambio Producido!',
                    text: '<?php echo htmlspecialchars($msg); ?>',
                    confirmButtonText: 'Continuar comprando',
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });

                // Limpiar la bandera de sesión
            });
        </script>
    <?php endif; ?>

</body>
</html>

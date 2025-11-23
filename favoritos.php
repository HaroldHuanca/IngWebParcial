<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/conexion.php';
$user_id = intval($_SESSION['user_id']);
$result = null;

if(isset($_GET['confirmar_limpiar']) && $_GET['confirmar_limpiar']){
    $sql = "DELETE FROM favorites WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Obtener la lista de favoritos
$sql = "SELECT b.* 
        FROM books b, favorites f 
        WHERE f.user_id = ? 
        AND b.book_id = f.book_id 
        ORDER BY b.created_at DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Libros Favoritos</h2>
            <?php
                if (!empty($favorites)):
                    ?>      
            <div class="d-flex gap-2">
                <a href="favoritos.php?limpiar=true" class="featured-btn" id="clearFavorites">
                    <i class="bi bi-trash3"></i> Limpiar favoritos
                </a>
            </div>
            <?php endif; ?>
        </div>
        <section class="featured-products py-5">
            <!-- Grid de libros favoritos -->
            <div class="products-grid">
                <!-- Ejemplo de libro favorito -->
                
                <?php
                if (!empty($favorites)):
                    foreach($favorites as $book):
                ?>
                        <div class="product-card">
                            <a href="producto.php?id=<?php echo $book['book_id']; ?>" style="text-decoration: none; color: inherit;">
                                <img src="<?php echo htmlspecialchars($book['cover_image_url']); ?>" alt="Portada de '<?php echo htmlspecialchars($book['title']); ?>'">
                                <div class="card-content">
                                    <h3 class="mb-2"><?php echo htmlspecialchars($book['title']); ?></h3>
                                    <p class="mb-3">
                                        <?php
                                        echo !empty($book['description'])
                                            ? htmlspecialchars(substr($book['description'], 0, 120)) . '...'
                                            : 'Sin descripción disponible.';
                                        ?>
                                    </p>
                                </div>
                            </a>
                            <div class="card-footer d-flex justify-content-between align-items-center p-3">
                                    <?php
                                    if (isset($_SESSION['user_id'])):
                                        $user_id = intval($_SESSION['user_id']);
                                        $book_id = intval($book['book_id']);
                                        $sqlFav = "SELECT * from favorites where user_id = ? and book_id = ?;";
                                        $stmtFav = $con->prepare($sqlFav);
                                        $stmtFav->bind_param("ii", $user_id, $book_id);
                                        $stmtFav->execute();
                                        $resultFav = $stmtFav->get_result();
                                    ?>
                                        <a href="favoritos.php?userDel=<?php echo $user_id; ?>&bookDel=<?php echo $book_id ?>" 
                                        class="buy-btn d-flex align-items-center gap-1">
                                            <i class="bi bi-star<?php echo ($resultFav->num_rows > 0 ? '-fill text-warning' : ''); ?> fs-3"></i>Quitar
                                        </a>
                                    <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                endif;
                    ?>
            </div>
        </section>
        <!-- Mensaje cuando no hay favoritos -->
        <div id="noFavorites" class="text-center py-5" style="display: none;">
            <i class="bi bi-star fs-1 text-muted"></i>
            <h3 class="mt-3">No tienes libros favoritos</h3>
            <p class="text-muted">Explora nuestro catálogo y marca los libros que te gusten</p>
            <a href="Catalogo.php" class="featured-btn mt-3">Explorar Catálogo</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Mostrar mensaje cuando no hay favoritos
        const productGrid = document.querySelector('.products-grid');
        const noFavoritesMessage = document.getElementById('noFavorites');

        function updateFavoritesView() {
            if (productGrid.children.length === 0) {
                productGrid.style.display = 'none';
                noFavoritesMessage.style.display = 'block';
            } else {
                productGrid.style.display = 'grid';
                noFavoritesMessage.style.display = 'none';
            }
        }

        // Eliminar libro de favoritos
        document.querySelectorAll('.featured-btn-eliminar').forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-card');
                productCard.remove();
                updateFavoritesView();
            });
        });

        // Verificar estado inicial
        updateFavoritesView();
    </script>
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
    <?php if (isset($_GET['bookDel']) && $_GET['bookDel']&&isset($_GET['userDel']) && $_GET['userDel']): 
            $bookDel = $_GET['bookDel'];
            $userDel = $_GET['userDel'];?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({
                    title: '¿Deseas eliminar este libro de tus favoritos?',
                    text: "Se eliminara el libro de tus favoritos.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige a la página PHP
                        window.location.href = "alternar.php?user=<?php echo $userDel; ?>&book=<?php echo $bookDel; ?>&eliminar=1&envio='favoritos.php'";
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
    <?php if (isset($_GET['limpiar']) && $_GET['limpiar']):?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({
                    title: '¿Deseas eliminar todos los libro de tus favoritos?',
                    text: "Se eliminaran todos lo libros de tus favoritos.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirige a la página PHP
                        window.location.href = "favoritos.php?confirmar_limpiar=1";
                    } else {
                        // Si presiona "No"
                        Swal.fire({
                            title: 'Operación cancelada',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        window.location.href = "favoritos.php?confirmar_limpiar=0";
                    }
                });

            });
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['confirmar_limpiar']) && $_GET['confirmar_limpiar']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cambio Producido!',
                    text: 'Tus favoritos han sido limpiados.',
                    confirmButtonText: 'Continuar comprando',
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });
                window.location.href = "favoritos.php";
                // Limpiar la bandera de sesión
            });
        </script>
    <?php endif; ?>
</body>

</html>
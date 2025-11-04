<?php
session_start();
include 'includes/conexion.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Libros Favoritos</h2>
            <div class="d-flex gap-2">
                <button class="featured-btn" id="clearFavorites">
                    <i class="bi bi-trash3"></i> Limpiar favoritos
                </button>
            </div>
        </div>
        <section class="featured-products py-5">
            <!-- Grid de libros favoritos -->
            <div class="products-grid">
                <!-- Ejemplo de libro favorito -->
                <?php
                $sql = "SELECT b.* 
                            FROM books b, favorites f 
                            WHERE f.user_id = '{$_SESSION['user_id']}' 
                            AND b.book_id = f.book_id 
                            ORDER BY b.created_at DESC";

                $result = $con->query($sql);

                if ($result && $result->num_rows > 0):
                    while ($book = $result->fetch_assoc()):
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
                        </div>
                    <?php
                    endwhile;
                endif;
                    ?>
            </div>
        </section>
        <!-- Mensaje cuando no hay favoritos -->
        <div id="noFavorites" class="text-center py-5" style="display: none;">
            <i class="bi bi-star fs-1 text-muted"></i>
            <h3 class="mt-3">No tienes libros favoritos</h3>
            <p class="text-muted">Explora nuestro catálogo y marca los libros que te gusten</p>
            <a href="producto.php" class="featured-btn mt-3">Explorar Catálogo</a>
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

        // Limpiar todos los favoritos
        document.getElementById('clearFavorites').addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas eliminar todos tus favoritos?')) {
                productGrid.innerHTML = '';
                updateFavoritesView();
            }
        });

        // Verificar estado inicial
        updateFavoritesView();
    </script>
</body>

</html>
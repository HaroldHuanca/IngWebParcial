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

        <!-- Grid de libros favoritos -->
        <div class="products-grid">
            <!-- Ejemplo de libro favorito -->
            <div class="product-card">
                <img src="img/book1.jpg" alt="Portada de 'El Resplandor'">
                <div class="card-content">
                    <h3 class="mb-2">El Resplandor</h3>
                    <p class="mb-3">Una obra maestra del terror de Stephen King sobre la locura y lo sobrenatural en un hotel aislado.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price fs-5 fw-bold">S./29.99</span>
                        <div class="d-flex gap-2">
                            <button class="buy-btn"><i class="bi bi-cart-plus"></i></button>
                            <button class="featured-btn-eliminar"><i class="bi bi-star-fill"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Otro ejemplo de libro favorito -->
            <div class="product-card">
                <img src="img/book4.webp" alt="Portada de 'Dune'">
                <div class="card-content">
                    <h3 class="mb-2">Dune</h3>
                    <p class="mb-3">Una épica saga de ciencia ficción en el planeta desértico Arrakis, clave en la política galáctica.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price fs-5 fw-bold">S./35.00</span>
                        <div class="d-flex gap-2">
                            <button class="buy-btn"><i class="bi bi-cart-plus"></i></button>
                            <button class="featured-btn-eliminar"><i class="bi bi-star-fill"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje cuando no hay favoritos -->
        <div id="noFavorites" class="text-center py-5" style="display: none;">
            <i class="bi bi-star fs-1 text-muted"></i>
            <h3 class="mt-3">No tienes libros favoritos</h3>
            <p class="text-muted">Explora nuestro catálogo y marca los libros que te gusten</p>
            <a href="producto.html" class="featured-btn mt-3">Explorar Catálogo</a>
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
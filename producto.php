<?php include 'includes/header.php'; ?>

    <!-- Producto -->
    <main class="container my-5 bg-fondo-1">
        <div class="row">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="product-card">
                    <img src="img/book1.jpg" alt="Portada del libro" class="img-fluid">
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="mt-3">Título del libro: El misterio del bosque</h2>
                <p class="text-muted">Autor: Juan Pérez</p>

                <div class="mb-3">
                    <span class="h4 fw-bold">S/ 59.90</span>
                    <small class="text-muted d-block">En stock</small>
                </div>

                <p>Descripción breve: Un fascinante relato de suspenso y aventuras que te mantendrá al borde del asiento. Perfecto para lectores que buscan emoción y atmósferas intensas.</p>

                <form id="addToCartForm" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="quantity" class="col-form-label">Cantidad</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" style="width:100px">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="button" id="addToCartBtn" class="btn featured-btn"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Añadir al carrito</button>
                    </div>
                </form>

                <hr class="my-4">

                <h6>Detalles del libro</h6>
                <ul>
                    <li>Formato: Tapa blanda</li>
                    <li>Páginas: 320</li>
                    <li>Idioma: Español</li>
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

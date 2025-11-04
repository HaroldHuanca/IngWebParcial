<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>
<body>
    <?php include 'includes/conexion.php'; ?>
    <?php include 'includes/header.php'; ?>

    <main>
        <section class="featured-author mt-5 pt-5 bg-fondo-1">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h4 class="featured-subtitle">AUTOR DEL MES</h4>
                        <h1 class="featured-title">Junji Ito</h1>
                        <p class="featured-text">
                            Sumérgete en el oscuro y fascinante mundo de Junji Ito, maestro del horror psicológico. Descubre sus obras maestras que te helarán la sangre.
                        </p>
                        <a href="autor_junji_ito.php" class="featured-btn">Explorar su obra</a>
                    </div>

                    <div class="col-lg-6">
                        <div class="featured-image-container position-relative" style="padding-top: 2%;">
                            <div class="background-shape"></div>
                            <img src="img/LPimg.webp" alt="Ilustración de Junji Ito" class="featured-image img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="featured-products py-5">
            <div class="container">
                <div class="products-header mb-4 d-flex justify-content-between align-items-center">
                    <h2>Novedades y Destacados</h2>
                    <a href="Catalogo.php" class="signup-btn">Ver Todos los Libros</a>
                </div>

                <div class="products-grid">

                    <?php
                    $sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT 8";
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
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="price fs-5 fw-bold">
                                                S./<?php echo number_format($book['price'], 2); ?>
                                            </span>
                                            <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i>Añadir</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="text-center text-muted">No hay libros disponibles en este momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="testimonial-section py-5">
            <div class="container">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="testimonial-slide d-flex flex-column flex-md-row align-items-center">
                                <img src="img/publi1.png" class="testimonial-img rounded-3 img-fluid" alt="Envíos por Sudamérica">
                                <div class="testimonial-text ms-md-4 text-center text-md-start">
                                    <h6 class="subtitle">NUESTRA COBERTURA</h6>
                                    <p class="quote">Realizamos <strong>envíos a toda Sudamérica</strong> con entrega rápida y segura. Tu libro favorito llega estés donde estés.</p>
                                    <p class="author">Equipo de Logística, <span>BookPort</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="testimonial-slide d-flex flex-column flex-md-row align-items-center">
                                <img src="img/publi2.png" class="testimonial-img rounded-3 img-fluid" alt="Promoción 1">
                                <div class="testimonial-text ms-md-4 text-center text-md-start">
                                    <h6 class="subtitle">PROMOCIÓN DESTACADA</h6>
                                    <p class="quote">Obtén <strong>20% de descuento</strong> en libros de ficción seleccionados durante todo el mes.</p>
                                    <p class="author">Ofertas Especiales, <span>BookPort</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="testimonial-slide d-flex flex-column flex-md-row align-items-center">
                                <img src="img/publi3.png" class="testimonial-img rounded-3 img-fluid" alt="Promoción 2">
                                <div class="testimonial-text ms-md-4 text-center text-md-start">
                                    <h6 class="subtitle">LECTURA Y CAFÉ</h6>
                                    <p class="quote">Por cada compra mayor a S/80 te regalamos un café en alianza con <strong>Café Cultura</strong>.</p>
                                    <p class="author">Experiencias Literarias, <span>Bookport</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </section>
    </main>
</body>

<?php include 'includes/footer.php'; ?>
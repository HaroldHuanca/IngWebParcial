<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>

<?php include 'includes/header.php'; ?>

    <!-- CONTENIDO -->
    <main>
    <div class="container-fluid px-4 py-5">
      <div class="row">
        <!-- SIDEBAR IZQUIERDA -->
        <aside class="col-lg-3 col-md-4 mb-4">
          <div class="p-3 sidebar-principal rounded shadow-sm">
            <h4 class="fw-bold mb-4">Filtros de Búsqueda</h4>

            <!-- Barra de Búsqueda Interna -->
            <div class="mb-4">
              <label for="sidebar-search" class="form-label fw-semibold">Buscar en el catálogo</label>
              <div class="input-group">
                <input type="text" class="form-control" id="sidebar-search" placeholder="Nombre del libro...">
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
              </div>
            </div>

            <!-- Filtro por Categorías con Acordeón -->
            <div class="accordion mb-4" id="accordionCategorias">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategorias" aria-expanded="true" aria-controls="collapseCategorias">
                    Categorías
                  </button>
                </h2>
                <div id="collapseCategorias" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionCategorias">
                  <div class="accordion-body">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="accion">
                      <label class="form-check-label" for="accion">Acción</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="aventura">
                      <label class="form-check-label" for="aventura">Aventura</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="historia">
                      <label class="form-check-label" for="historia">Historia</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="ingenieria">
                      <label class="form-check-label" for="ingenieria">Ingeniería</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="fantasia">
                      <label class="form-check-label" for="fantasia">Fantasía</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="ciencia-ficcion">
                      <label class="form-check-label" for="ciencia-ficcion">Ciencia Ficción</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filtro por Autores -->
            <div class="mb-4">
              <label for="selectAutor" class="form-label fw-semibold">Autores</label>
              <select class="form-select" id="selectAutor">
                <option selected>Todos los autores</option>
                <option value="1">Jane Austen</option>
                <option value="2">Brandon Sanderson</option>
                <option value="3">George Orwell</option>
                <option value="4">Mary Shelley</option>
                <option value="5">Gabriel García Márquez</option>
              </select>
            </div>

            <!-- Rango de Precios -->
            <div class="mb-4">
              <label for="priceRange" class="form-label fw-semibold">Rango de Precio</label>
              <input type="range" class="form-range" min="0" max="100" step="5" id="priceRange">
              <div class="d-flex justify-content-between">
                <span class="small">S./0</span>
                <span class="small">S./100</span>
              </div>
            </div>

            <!-- Ordenar Por -->
            <div class="mb-4">
              <label for="ordenarPor" class="form-label fw-semibold">Ordenar por</label>
              <select class="form-select" id="ordenarPor">
                <option selected>Relevancia</option>
                <option value="1">Precio: de menor a mayor</option>
                <option value="2">Precio: de mayor a menor</option>
                <option value="3">Más vendidos</option>
                <option value="4">Alfabéticamente, A-Z</option>
              </select>
            </div>

            <!-- Botón de Aplicar Filtros -->
            <div class="d-grid">
              <button class="btn btn-primary">Aplicar Filtros</button>
            </div>
          </div>
        </aside>

        <!-- SECCIÓN CENTRAL -->

        <section class="col-lg-8 col-md-6 catalogo-section">
          <div class="products-grid">
            <div class="product-card">
              <img src="img/book3.webp" alt="Portada de '1984'">
              <div class="card-content">
                <h3 class="mb-2">1984</h3>
                <p class="mb-3">Distopía clásica sobre el totalitarismo y la manipulación de la verdad.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./22.00</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i>Comprar</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book2.jpg" alt="Portada de 'Cien años de soledad'">
              <div class="card-content">
                <h3 class="mb-2">Cien Años de Soledad</h3>
                <p class="mb-3">La épica historia de la familia Buendía en el mítico pueblo de Macondo.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./25.50</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i>Comprar</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book5.webp" alt="Portada de 'El Principito'">
              <div class="card-content">
                <h3 class="mb-2">El Principito</h3>
                <p class="mb-3">Un cuento filosófico sobre la amistad y la inocencia.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./18.75</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i>Comprar</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book6.webp" alt="Portada de 'Matar a un Ruiseñor'">
              <div class="card-content">
                <h3 class="mb-2">Matar a un Ruiseñor</h3>
                <p class="mb-3">La conmovedora historia de Scout Finch y su padre Atticus en el sur de Estados Unidos, abordando el racismo y la justicia.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./28.00</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i> Añadir</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book7.webp" alt="Portada de 'El Señor de los Anillos'">
              <div class="card-content">
                <h3 class="mb-2">El Señor de los Anillos</h3>
                <p class="mb-3">La épica aventura de Frodo Bolsón y la Comunidad del Anillo para destruir el Anillo Único.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./45.00</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i> Añadir</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book8.jpg" alt="Portada de 'Frankenstein'">
              <div class="card-content">
                <h3 class="mb-2">Frankenstein</h3>
                <p class="mb-3">La historia del doctor Victor Frankenstein y su ambiciosa creación, explorando la ética científica y la soledad.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./21.50</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i> Añadir</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book9.webp" alt="Portada de 'Orgullo y Prejuicio'">
              <div class="card-content">
                <h3 class="mb-2">Orgullo y Prejuicio</h3>
                <p class="mb-3">La novela clásica de Jane Austen sobre las complejas relaciones sociales y románticas de la familia Bennet.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./19.99</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i> Añadir</button>
                </div>
              </div>
            </div>

            <div class="product-card">
              <img src="img/book10.jpg" alt="Portada de 'Crimen y Castigo'">
              <div class="card-content">
                <h3 class="mb-2">Crimen y Castigo</h3>
                <p class="mb-3">La profunda exploración psicológica de Raskolnikov, un estudiante atormentado por su filosofía y sus acciones.</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="price fs-5 fw-bold">S./27.00</span>
                  <button class="buy-btn"><i class="bi bi-cart-plus me-1"></i> Añadir</button>
                </div>
              </div>
            </div>
          </div>
        </section>
  </main>

  <?php include 'includes/footer.php'; ?>

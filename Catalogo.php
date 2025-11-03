<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
  <?php include 'includes/conexion.php'; ?>
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

        <section class="col-lg-9 col-md-8 catalogo-section
        ">
          <div class="products-grid">
            <?php
            $sql = "SELECT * FROM books ORDER BY created_at DESC";
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
        </section>
  </main>

  <?php include 'includes/footer.php'; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
session_start();

?>
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
          <form method="GET" action="Catalogo.php">
            <div class="p-3 sidebar-principal rounded shadow-sm">
              <h4 class="fw-bold mb-4">Filtros de Búsqueda</h4>

              <!-- Barra de Búsqueda Interna -->
              <div class="mb-4">

                <div class="input-group">
                  <input name="filtro_nombre" type="text" class="form-control" id="sidebar-search"
                    placeholder="Nombre del libro..."
                    value="<?php echo isset($_GET['filtro_nombre']) ? htmlspecialchars($_GET['filtro_nombre']) : ''; ?>">
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
                      <?php
                      $sql = "SELECT * FROM categories";
                      $result = $con->query($sql);
                      while ($category = $result->fetch_assoc()):
                      ?>
                        <div class="form-check mb-2">
                          <?php
                          $selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];
                          $checked = in_array($category['category_id'], $selectedCategories) ? 'checked' : '';
                          ?>
                          <div class="form-check mb-2">
                            <input name="categories[]" value="<?php echo $category['category_id'] ?>"
                              class="form-check-input" type="checkbox" id="cat_<?php echo $category['category_id']; ?>" <?php echo $checked; ?>>
                            <label class="form-check-label" for="cat_<?php echo $category['category_id']; ?>">
                              <?php echo $category['name']; ?>
                            </label>
                          </div>

                        </div>
                      <?php endwhile; ?>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Filtro por Autores -->
              <div class="mb-4">
                <label for="selectAutor" class="form-label fw-semibold">Autores</label>
                <select name="filtro_autor" class="form-select" id="selectAutor">
                  <option value="0">Todos los autores</option>
                  <?php
                  $sql = "SELECT * FROM authors";
                  $result = $con->query($sql);
                  while ($author = $result->fetch_assoc()):
                  ?>
                    <?php
                    $selectedAutor = isset($_GET['filtro_autor']) ? $_GET['filtro_autor'] : '0';
                    $selected = ($author['author_id'] == $selectedAutor) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $author['author_id']; ?>" <?php echo $selected; ?>>
                      <?php echo $author['first_name'] . " " . $author['last_name']; ?>
                    </option>

                  <?php endwhile; ?>
                </select>
              </div>

              <!-- Rango de Precios -->
              <div class="mb-4">
                <label for="priceRange" class="form-label fw-semibold">Rango de Precio</label>
                <?php $filtro_precio = isset($_GET['filtro_precio']) ? $_GET['filtro_precio'] : '0'; ?>
                <select name="filtro_precio" class="form-select" id="filtro_precio">
                  <option value="0" <?php echo ($filtro_precio == '0') ? 'selected' : ''; ?>>Todos los precios</option>
                  <option value="1" <?php echo ($filtro_precio == '1') ? 'selected' : ''; ?>>Menor a s/.20</option>
                  <option value="2" <?php echo ($filtro_precio == '2') ? 'selected' : ''; ?>>Entre s/.20 y s/.40</option>
                  <option value="3" <?php echo ($filtro_precio == '3') ? 'selected' : ''; ?>>Entre s/.40 y s/.60</option>
                  <option value="4" <?php echo ($filtro_precio == '4') ? 'selected' : ''; ?>>Mas de s/.80</option>
                </select>
              </div>

              <!-- Ordenar Por -->
              <div class="mb-4">
                <label for="ordenarPor" class="form-label fw-semibold">Ordenar por</label>
                <?php $filtro_orden = isset($_GET['filtro_orden']) ? $_GET['filtro_orden'] : '0'; ?>
                <select name="filtro_orden" class="form-select" id="filtro_orden">
                  <option value="0" <?php echo ($filtro_orden == '0') ? 'selected' : ''; ?>>Relevancia</option>
                  <option value="1" <?php echo ($filtro_orden == '1') ? 'selected' : ''; ?>>Precio: de menor a mayor</option>
                  <option value="2" <?php echo ($filtro_orden == '2') ? 'selected' : ''; ?>>Precio: de mayor a menor</option>
                  <option value="3" <?php echo ($filtro_orden == '3') ? 'selected' : ''; ?>>Alfabéticamente, A-Z</option>
                  <option value="4" <?php echo ($filtro_orden == '4') ? 'selected' : ''; ?>>Alfabéticamente, Z-A</option>
                </select>

              </div>

              <!-- Botón de Aplicar Filtros -->
              <div class="d-grid">
                <button name="filtro" class="btn btn-primary" type="submit">Aplicar Filtros</button>
              </div>
            </div>
          </form>
        </aside>

        <!-- SECCIÓN CENTRAL -->

        <section class="col-lg-9 col-md-8 catalogo-section
        ">
          <div class="products-grid">
            <?php
            $sql = "
  SELECT DISTINCT b.*
  FROM books b
  JOIN book_categories bc ON b.book_id = bc.book_id
  JOIN book_authors ba ON b.book_id = ba.book_id
  WHERE 1=1
";

            // Filtro por nombre
            if (!empty($_GET['filtro_nombre'])) {
              $nombre = $con->real_escape_string($_GET['filtro_nombre']);
              $sql .= " AND b.title LIKE '%$nombre%'";
            }

            // Filtro por categorías
            if (!empty($_GET['categories'])) {
              $categorias = array_map('intval', $_GET['categories']); // sanitiza
              $sql .= " AND bc.category_id IN (" . implode(',', $categorias) . ")";
            }

            // Filtro por autor
            if (!empty($_GET['filtro_autor'])) {
              $autor_id = intval($_GET['filtro_autor']);
              if ($autor_id > 0) {
                $sql .= " AND ba.author_id = $autor_id";
              }
            }

            if (!empty($_GET['filtro_precio']) && $_GET['filtro_precio'] != '0') {
              switch ($_GET['filtro_precio']) {
                case '1':
                  $sql .= " AND b.price < 20";
                  break;
                case '2':
                  $sql .= " AND b.price BETWEEN 20 AND 40";
                  break;
                case '3':
                  $sql .= " AND b.price BETWEEN 40 AND 60";
                  break;
                case '4':
                  $sql .= " AND b.price > 80";
                  break;
              }
            }

            // --- Ordenamiento ---
            if (!empty($_GET['filtro_orden']) && $_GET['filtro_orden'] != '0') {
              switch ($_GET['filtro_orden']) {
                case '1':
                  $sql .= " ORDER BY b.price ASC";
                  break;
                case '2':
                  $sql .= " ORDER BY b.price DESC";
                  break;
                case '3':
                  $sql .= " ORDER BY b.title ASC";
                  break;
                case '4':
                  $sql .= " ORDER BY b.title DESC";
                  break;
              }
            } else {
              // Por defecto, podrías ordenar por título o relevancia
              $sql .= " ORDER BY b.book_id DESC";
            }

            //echo "<pre>$sql</pre>";
            // Opcional: otros filtros (autor, precio, etc.) pueden agregarse aquí

            // Ejecutar
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
                        <a href="añadir.php?id=<?php echo $book['book_id']; ?>&envio='Catalogo.php'" class="buy-btn text-decoration-none">
                          <i class="bi bi-cart-plus me-1"></i>Añadir
                        </a>
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
</body>
</html>
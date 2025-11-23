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
                    placeholder="Nombre del libro o autor"
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

              <!-- Botones de acción -->
              <div class="d-grid gap-2">
                <!-- Botón de aplicar filtros -->
                <button name="filtro" class="btn btn-primary" type="submit">
                  <i class="bi bi-funnel me-1"></i> Aplicar Filtros
                </button>

                <!-- Botón de borrar filtros -->
                <a href="Catalogo.php" class="btn btn-primary">
                  <i class="bi bi-x-circle me-1"></i> Borrar Filtros
                </a>
              </div>

            </div>
          </form>
        </aside>

        <!-- SECCIÓN CENTRAL -->

        <section class="col-lg-9 col-md-8 catalogo-section
        ">
          <div class="products-grid2">
            <?php
            $sql = "
  SELECT DISTINCT b.*
  FROM books b
  JOIN book_categories bc ON b.book_id = bc.book_id
  JOIN book_authors ba ON b.book_id = ba.book_id
  JOIN authors a ON ba.author_id = a.author_id
  WHERE 1=1
";
            $filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';
            if (isset($filtro) && $filtro != "") {
              $filtro_escaped = '%' . $con->real_escape_string($filtro) . '%';
              $sql .= "
      AND (
        b.title LIKE '$filtro_escaped'
        OR a.first_name LIKE '$filtro_escaped'
        OR a.last_name LIKE '$filtro_escaped'
        OR CONCAT(a.first_name, ' ', a.last_name) LIKE '$filtro_escaped'
        OR CONCAT(a.last_name, ' ', a.first_name) LIKE '$filtro_escaped'
      )
    ";
            } else {
              // Filtro por nombre
              if (!empty($_GET['filtro_nombre'])) {
                $nombre = trim($_GET['filtro_nombre']);
                $nombre_escaped = '%' . $con->real_escape_string($nombre) . '%';
                $sql .= "
      AND (
        b.title LIKE '$nombre_escaped'
        OR a.first_name LIKE '$nombre_escaped'
        OR a.last_name LIKE '$nombre_escaped'
        OR CONCAT(a.first_name, ' ', a.last_name) LIKE '$nombre_escaped'
        OR CONCAT(a.last_name, ' ', a.first_name) LIKE '$nombre_escaped'
      )
    ";
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
                      <?php
                      $sql = "SELECT a.first_name, a.last_name 
                                                    FROM authors a
                                                    JOIN book_authors ba ON a.author_id = ba.author_id
                                                    WHERE ba.book_id = {$book['book_id']}";
                      $resultadazo = $con->query($sql);
                      $author = $resultadazo->fetch_assoc();
                      ?>
                      <h5 class="mb-2"><?php echo htmlspecialchars($author['first_name'] . " " . $author['last_name']); ?></h5>
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
                        <a href="añadir.php?id=<?php echo $book['book_id']; ?>&precio=<?php echo $book['price']; ?>&envio='Catalogo.php'" class="buy-btn text-decoration-none">
                          <i class="bi bi-cart-plus me-1"></i>Añadir
                        </a>
                        <?php
                          if(isset($_SESSION['user_id'])):
                            $user_id=$_SESSION['user_id'];
                            $book_id=$book['book_id'];
                            $sqlFav = "SELECT * from favorites where user_id = $user_id and book_id = $book_id;";
                            $resultFav = $con->query($sqlFav);
                          ?>
                          <a href="alternar.php?user=<?php echo $user_id;?>&book=<?php echo $book_id?>&eliminar=<?php echo ($resultFav->num_rows > 0);?>&envio='Catalogo.php'" class="buy-btn">
                            <i class="bi bi-star<?php echo ($resultFav->num_rows > 0 ? '-fill text-warning' : ''); ?> fs-5"></i>
                          </a>
                          <?php endif;?>
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
  <?php if (isset($_GET['compra']) && $_GET['compra']): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'info',
                    title: '¡Carrito Vacío!',
                    text: 'Añade libros a tu carrito para poder comprarlos.',
                    confirmButtonText: 'Sigue personalizando tu carrito',
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg']): 
            $msg = $_GET['msg'];?>
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
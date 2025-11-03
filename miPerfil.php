<?php 
    session_start();
    include 'includes/conexion.php';
    if(!isset($_SESSION['usuario'])) {
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
        <?php echo "Bienvenido, " . htmlspecialchars($_SESSION['user_id']) . "!"; ?>
        <div class="row">
            <!-- Información del perfil -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm" style="background-color: var(--fondo-2);">
                    <div class="card-body text-center">
                        <img src="img/default-avatar.png" alt="Foto de perfil" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h3 class="card-title mb-3">Juan Pérez</h3>
                        <p class="text-muted">Miembro desde Octubre 2025</p>
                        <button class="featured-btn mb-2 w-100">
                            <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                        </button>
                    </div>
                </div>

                <!-- Detalles del usuario -->
                <div class="card mt-4 shadow-sm" style="background-color: var(--fondo-2);">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Información Personal</h5>
                        <div class="mb-3">
                            <label class="text-muted d-block">Email</label>
                            <div>juan.perez@email.com</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted d-block">Teléfono</label>
                            <div>+51 987 654 321</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted d-block">Dirección</label>
                            <div>Av. Ejercito 123, Arequipa</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección derecha con pestañas -->
            <div class="col-lg-8">
                <div class="card shadow-sm" style="background-color: var(--fondo-2);">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#favorites" type="button">
                                    <i class="bi bi-star-fill me-2"></i>Favoritos Recientes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#orders" type="button">
                                    <i class="bi bi-box-seam me-2"></i>Mis Pedidos
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="profileTabsContent">
                            <!-- Pestaña de Favoritos -->
                            <div class="tab-pane fade show active" id="favorites">
                                <div class="row g-4">
                                    <!-- Libro favorito 1 -->
                                     <?php
                                        $sql = "SELECT b.* 
                                                    FROM books b, favorites f 
                                                    WHERE f.user_id = '{$_SESSION['user_id']}' 
                                                    AND b.book_id = f.book_id 
                                                    ORDER BY b.created_at DESC limit 2";

                                        $result = $con->query($sql);

                                        if ($result && $result->num_rows > 0):
                                            while ($book = $result->fetch_assoc()):
                                    ?>
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="row g-0">
                                                <div class="col-4">
                                                    <img src="<?php echo htmlspecialchars($book['cover_image_url']); ?>" class="img-fluid rounded-start" alt="Portada de '<?php echo htmlspecialchars($book['title']); ?>'" style="height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                                        <p class="mb-3">
                                                        <?php
                                                        echo !empty($book['description'])
                                                            ? htmlspecialchars(substr($book['description'], 0, 120)) . '...'
                                                            : 'Sin descripción disponible.';
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endwhile;
                                    endif;
                                    ?>
                                    <div class="col-12 text-center mt-3">
                                        <a href="favoritos.php" class="featured-btn">Ver todos mis favoritos</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Pestaña de Pedidos -->
                            <div class="tab-pane fade" id="orders">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Pedido #</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#001</td>
                                                <td>15/10/2025</td>
                                                <td><span class="badge bg-success">Entregado</span></td>
                                                <td>S/64.99</td>
                                                <td><button class="btn btn-sm btn-outline-primary">Ver detalles</button></td>
                                            </tr>
                                            <tr>
                                                <td>#002</td>
                                                <td>17/10/2025</td>
                                                <td><span class="badge bg-warning">En camino</span></td>
                                                <td>S/35.00</td>
                                                <td><button class="btn btn-sm btn-outline-primary">Ver detalles</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
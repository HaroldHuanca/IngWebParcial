<?php
        //Si es admin lo enviamos al dashboard del admin
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            header('Location:admin/dashboard.php');
            exit();
        }
?>
<header>
    <nav class="navbar navbar-expand-lg bg-fondo-1 shadow-sm fixed-top">
        <div class="container-fluid EspacioHeader"><!--container fluid para mejorar la responsividad-->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/logo.webp" alt="Logo BookPort" class="img-fluid me-2" style="height: 40px;">
                <span class="fw-bold fs-4">BookPort</span> <!-- negrita y tamaño de fuente -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent"> <!-- alineacion con justify-content-between -->
                <!-- barra de búsqueda -->
                <form class="d-flex mx-lg-auto my-2 my-lg-0"
                    role="search"
                    style="max-width: 450px; width: 100%;"
                    action="Catalogo.php"
                    method="GET">

                    <input class="form-control me-2"
                        type="search"
                        name="filtro"
                        placeholder="Buscar libros por título o autor..."
                        aria-label="Search"
                        value="<?php echo isset($_GET['filtro']) ? htmlspecialchars($_GET['filtro']) : ''; ?>"
                        >

                    <button class="btn-buscar" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- navegacion principal -->
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center"> <!-- alineado verticalmente -->
                    <li class="nav-item">
                        <!-- usamos bootstrap Icons para home -->
                        <a class="nav-link active" aria-current="page" href="index.php" title="Inicio">
                            <i class="bi bi-house-door-fill fs-4"></i>Home <!-- icono mas moderno y grande -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Catalogo.php" title="Todos los Libros"> <!-- enlace a pagina de productos -->
                            <i class="bi bi-book-half fs-4 me-1"></i>Catalogo
                        </a>
                    </li>
                    <li class="nav-item">
                        <!-- se usa bootstrap Icons para el carrito -->
                        <a class="nav-link" href="carrito.php" title="Carrito de compras"> <!-- enlace a pagina de carrito -->
                            <i class="bi bi-cart-fill fs-4"></i>Carrito
                        </a>
                    </li>
                    <?php
                    if (isset($_SESSION['usuario'])):
                     ?>
                    <li class="nav-item">
                        <!-- usamos bootstrap Icons para favoritos -->
                        <a class="nav-link" href="favoritos.php" title="Favoritos"> <!-- enlace a pagina de favoritos -->
                            <i class="bi bi-star-fill fs-4"></i>Favoritos
                        </a>
                    </li>
                    <?php
                    // Verificar si hay órdenes pendientes de pago
                    if (isset($_SESSION['user_id'])) {
                        include 'conexion.php';
                        $user_id = intval($_SESSION['user_id']);
                        $sql_check = "SELECT COUNT(*) as pending_count FROM orders WHERE user_id = ? AND payment_status = 'pending'";
                        $stmt = $con->prepare($sql_check);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $pending_count = $row['pending_count'];
                        
                        if ($pending_count > 0):
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pagos.php" title="Pagos Pendientes">
                            <i class="bi bi-credit-card fs-4"></i>Pagos
                            <span class="badge bg-danger ms-1"><?php echo htmlspecialchars($pending_count); ?></span>
                        </a>
                    </li>
                    <?php
                        endif;
                    }
                    endif;
                    ?>
                    <?php
                    if (!isset($_SESSION['usuario'])):
                    ?>
                    <li class="nav_item">
                        <a class="nav-link" href="login.php" title="Login">
                            <i class="bi bi-person-circle fs-4 me-1"></i>Login
                        </a>
                    </li>
                    <?php
                    endif;
                    ?>
                    <?php
                        if(isset($_SESSION['usuario'])):
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Mi Cuenta">
                            <i class="bi bi-person-circle fs-4 me-1"></i>Mi cuenta
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="miPerfil.php">Mi perfil</a></li> <!-- enlace a perfil -->
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                             <!-- enlace a login -->
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li> <!-- enlace a logout -->
                        </ul>
                    </li>
                    <?php endif; ?>
                    <br>
                    <?php
                    if (!isset($_SESSION['usuario'])):
                    ?>
                    <li class="nav-item ms-3">
                        <a href="registro.php" class="featured-btn">Registrarse</a>
                    </li>
                    <?php
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
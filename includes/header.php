<!DOCTYPE html>
<html lang="es"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookPort - Tu Librería Online</title>

    <link rel="icon" href="img/favicon.ico" type="image/x-icon"> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="estilos.css">
</head>
<body>
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
                    <form class="d-flex mx-lg-auto my-2 my-lg-0" role="search" style="max-width: 450px; width: 100%;"> <!-- centrado en pantallas grandes y max-width para limitar tamaño -->
                        <input class="form-control me-2" type="search" placeholder="Buscar libros por título o autor..." aria-label="Search">
                        <button class="btn-buscar" type="submit">
                            <i class="bi bi-search"></i> <!-- icono de busqueda -->
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
                            <a class="nav-link" href="Catalogo.php" title="Todos los Productos"> <!-- enlace a pagina de productos -->
                                <i class="bi bi-book-half fs-4 me-1"></i>Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- se usa bootstrap Icons para el carrito -->
                            <a class="nav-link" href="carrito.php" title="Carrito de compras"> <!-- enlace a pagina de carrito -->
                                <i class="bi bi-cart-fill fs-4"></i>Carrito
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- usamos bootstrap Icons para favoritos -->
                            <a class="nav-link" href="favoritos.php" title="Favoritos"> <!-- enlace a pagina de favoritos -->
                                <i class="bi bi-star-fill fs-4"></i>Favoritos
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Mi Cuenta">
                                <i class="bi bi-person-circle fs-4 me-1"></i>Mi cuenta
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="miPerfil.php">Mi perfil</a></li> <!-- enlace a perfil -->
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="login.php">Iniciar Sesión</a></li> <!-- enlace a login -->
                                <li><a class="dropdown-item" href="login.php">Registrarse</a></li> <!-- enlace a registro -->
                            </ul>
                        </li>
                        <br>
                        <li class="nav-item ms-3">
                            <a href="login.php" class="featured-btn">Registrarse</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

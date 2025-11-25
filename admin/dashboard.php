<!DOCTYPE html>
<html lang="es">
<?php include '../includes/head2.php'; ?>
<body>
    <?php include '../includes/header2.php'; ?>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar col-md-3 col-lg-2">
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a></li>
                <li><a href="categorias.php"><i class="bi bi-tag"></i> Categorías</a></li>
                <li><a href="autores.php"><i class="bi bi-person-badge"></i> Autores</a></li>
                <li><a href="libros.php"><i class="bi bi-book"></i> Libros</a></li>
                <li><a href="pedidos.php"><i class="bi bi-bag"></i> Pedidos</a></li>
                <li><a href="pagos.php"><i class="bi bi-credit-card"></i> Pagos</a></li>
            </ul>
        </aside>

        <!-- Contenido Principal -->
        <main class="dashboard-content col-md-9 col-lg-10">
            <div class="container-fluid">
                <!-- Encabezado -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h2 mb-0">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </h1>
                        <p class="text-muted">Bienvenido al panel de administración de BookPort</p>
                    </div>
                </div>

                <!-- Selector de Paleta de Colores -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    <i class="bi bi-palette"></i> Personalizar Paleta de Colores
                                </h6>
                                <div id="paletaSelector" class="paleta-selector"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="stat-card">
                            <i class="bi bi-people fs-1"></i>
                            <div class="stat-number" id="total-usuarios">0</div>
                            <div class="stat-label">Usuarios Registrados</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="stat-card success">
                            <i class="bi bi-book fs-1"></i>
                            <div class="stat-number" id="total-libros">0</div>
                            <div class="stat-label">Libros en Catálogo</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="stat-card danger">
                            <i class="bi bi-bag fs-1"></i>
                            <div class="stat-number" id="total-pedidos">0</div>
                            <div class="stat-label">Pedidos Totales</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="stat-card warning">
                            <i class="bi bi-credit-card fs-1"></i>
                            <div class="stat-number" id="total-ingresos">S/ 0</div>
                            <div class="stat-label">Ingresos Totales</div>
                        </div>
                    </div>
                </div>

                <!-- Secciones de Acceso Rápido -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-people"></i> Gestión de Usuarios
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Administra los usuarios registrados en el sistema</p>
                                <a href="usuarios.php" class="btn btn-primary btn-sm">
                                    <i class="bi bi-arrow-right"></i> Ir a Usuarios
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-book"></i> Gestión de Libros
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Administra el catálogo de libros disponibles</p>
                                <a href="libros.php" class="btn btn-success btn-sm">
                                    <i class="bi bi-arrow-right"></i> Ir a Libros
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-bag"></i> Gestión de Pedidos
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Revisa y administra los pedidos de los clientes</p>
                                <a href="pedidos.php" class="btn btn-danger btn-sm">
                                    <i class="bi bi-arrow-right"></i> Ir a Pedidos
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-credit-card"></i> Gestión de Pagos
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Revisa las transacciones de pago realizadas</p>
                                <a href="pagos.php" class="btn btn-info btn-sm">
                                    <i class="bi bi-arrow-right"></i> Ir a Pagos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../includes/footer2.php'; ?>

    <script>
        // Cargar estadísticas
        document.addEventListener('DOMContentLoaded', function() {
            fetch('api/estadisticas.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-usuarios').textContent = data.usuarios || 0;
                    document.getElementById('total-libros').textContent = data.libros || 0;
                    document.getElementById('total-pedidos').textContent = data.pedidos || 0;
                    document.getElementById('total-ingresos').textContent = 'S/ ' + (data.ingresos || 0).toFixed(2);
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>

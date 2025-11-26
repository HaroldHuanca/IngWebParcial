<!DOCTYPE html>
<html lang="es">
<?php include '../includes/head2.php'; ?>
<body>
    <?php include '../includes/header2.php'; ?>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar col-md-3 col-lg-2">
            <ul class="sidebar-nav">
                <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a></li>
                <li><a href="categorias.php"><i class="bi bi-tag"></i> Categorías</a></li>
                <li><a href="autores.php" class="active"><i class="bi bi-person-badge"></i> Autores</a></li>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h2 mb-0"><i class="bi bi-person-badge"></i> Gestión de Autores</h1>
                                <p class="text-muted">Administra los autores de libros</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAutor">
                                <i class="bi bi-plus-circle"></i> Nuevo Autor
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarAutor" placeholder="Buscar autor...">
                        </div>
                    </div>
                </div>

                <!-- Tabla de Autores -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaAutores">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Biografía</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTabla">
                                    <!-- Se carga dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para Crear/Editar Autor -->
    <div class="modal fade" id="modalAutor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Nuevo Autor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAutor">
                    <div class="modal-body">
                        <input type="hidden" id="autorId">
                        
                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Biografía</label>
                            <textarea class="form-control" id="biography" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer2.php'; ?>

    <script>
        // Cargar autores
        function cargarAutores() {
            fetch('api/autores_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(autor => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${autor.author_id}</td>
                            <td>${autor.first_name}</td>
                            <td>${autor.last_name}</td>
                            <td>${autor.biography ? autor.biography.substring(0, 50) + '...' : '-'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-icon" onclick="editarAutor(${autor.author_id})" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar autores', 'error');
                });
        }

        // Editar autor
        function editarAutor(id) {
            fetch(`api/autores_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(autor => {
                    document.getElementById('autorId').value = autor.author_id;
                    document.getElementById('first_name').value = autor.first_name;
                    document.getElementById('last_name').value = autor.last_name;
                    document.getElementById('biography').value = autor.biography || '';
                    document.getElementById('tituloModal').textContent = 'Editar Autor';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalAutor'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar autor', 'error');
                });
        }

        // Guardar autor
        document.getElementById('formAutor').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('autorId').value;
            const datos = {
                id: id || null,
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                biography: document.getElementById('biography').value
            };

            fetch('api/autores_api.php?action=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta(id ? 'Autor actualizado' : 'Autor creado', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalAutor')).hide();
                    limpiarFormulario('formAutor');
                    cargarAutores();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar autor', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarAutor', 'tablaAutores');

        // Limpiar modal al cerrar
        document.getElementById('modalAutor').addEventListener('hidden.bs.modal', function() {
            limpiarFormulario('formAutor');
            document.getElementById('autorId').value = '';
            document.getElementById('tituloModal').textContent = 'Nuevo Autor';
        });

        // Cargar autores al iniciar
        cargarAutores();
    </script>
</body>
</html>

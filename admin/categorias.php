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
                <li><a href="categorias.php" class="active"><i class="bi bi-tag"></i> Categorías</a></li>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h2 mb-0"><i class="bi bi-tag"></i> Gestión de Categorías</h1>
                                <p class="text-muted">Administra las categorías de libros</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                                <i class="bi bi-plus-circle"></i> Nueva Categoría
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarCategoria" placeholder="Buscar categoría...">
                        </div>
                    </div>
                </div>

                <!-- Tabla de Categorías -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaCategorias">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Categoría Padre</th>
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

    <!-- Modal para Crear/Editar Categoría -->
    <div class="modal fade" id="modalCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCategoria">
                    <div class="modal-body">
                        <input type="hidden" id="categoriaId">
                        
                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Categoría Padre</label>
                            <select class="form-select" id="parent_category_id">
                                <option value="">Ninguna</option>
                            </select>
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
        // Cargar categorías
        function cargarCategorias() {
            fetch('api/categorias_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(categoria => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${categoria.category_id}</td>
                            <td>${categoria.name}</td>
                            <td>${categoria.description || '-'}</td>
                            <td>${categoria.parent_category_id || '-'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-icon" onclick="editarCategoria(${categoria.category_id})" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                    
                    cargarPadres(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar categorías', 'error');
                });
        }

        // Cargar categorías padre
        function cargarPadres(categorias) {
            const select = document.getElementById('parent_category_id');
            select.innerHTML = '<option value="">Ninguna</option>';
            
            categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.category_id;
                option.textContent = categoria.name;
                select.appendChild(option);
            });
        }

        // Editar categoría
        function editarCategoria(id) {
            fetch(`api/categorias_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(categoria => {
                    document.getElementById('categoriaId').value = categoria.category_id;
                    document.getElementById('name').value = categoria.name;
                    document.getElementById('description').value = categoria.description || '';
                    document.getElementById('parent_category_id').value = categoria.parent_category_id || '';
                    document.getElementById('tituloModal').textContent = 'Editar Categoría';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalCategoria'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar categoría', 'error');
                });
        }

        // Guardar categoría
        document.getElementById('formCategoria').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('categoriaId').value;
            const datos = {
                id: id || null,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                parent_category_id: document.getElementById('parent_category_id').value || null
            };

            fetch('api/categorias_api.php?action=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta(id ? 'Categoría actualizada' : 'Categoría creada', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalCategoria')).hide();
                    limpiarFormulario('formCategoria');
                    cargarCategorias();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar categoría', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarCategoria', 'tablaCategorias');

        // Limpiar modal al cerrar
        document.getElementById('modalCategoria').addEventListener('hidden.bs.modal', function() {
            limpiarFormulario('formCategoria');
            document.getElementById('categoriaId').value = '';
            document.getElementById('tituloModal').textContent = 'Nueva Categoría';
        });

        // Cargar categorías al iniciar
        cargarCategorias();
    </script>
</body>
</html>

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
                <li><a href="usuarios.php" class="active"><i class="bi bi-people"></i> Usuarios</a></li>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h2 mb-0"><i class="bi bi-people"></i> Gestión de Usuarios</h1>
                                <p class="text-muted">Administra los usuarios registrados en el sistema</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                                <i class="bi bi-plus-circle"></i> Nuevo Usuario
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar por nombre, email o usuario...">
                        </div>
                    </div>
                </div>

                <!-- Tabla de Usuarios -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaUsuarios">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Nombre Completo</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                        <th>Administrador</th>
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

    <!-- Modal para Crear/Editar Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUsuario">
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId">
                        
                        <div class="form-group">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="username" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="first_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="last_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="phone">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" id="address" rows="3"></textarea>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_admin">
                            <label class="form-check-label" for="is_admin">
                                Es Administrador
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Activo
                            </label>
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
        // Cargar usuarios
        function cargarUsuarios() {
            fetch('api/usuarios_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(usuario => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${usuario.user_id}</td>
                            <td>${usuario.username}</td>
                            <td>${usuario.email}</td>
                            <td>${usuario.first_name} ${usuario.last_name}</td>
                            <td>${usuario.phone || '-'}</td>
                            <td>
                                <span class="badge ${usuario.is_active ? 'badge-success' : 'badge-danger'}">
                                    ${Boolean(Number(usuario.is_active)) ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td>
                                <span class="badge ${usuario.is_admin ? 'badge-primary' : 'badge-secondary'}">
                                    ${Boolean(Number(usuario.is_admin)) ? 'Sí' : 'No'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-icon" onclick="editarUsuario(${usuario.user_id})" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar usuarios', 'error');
                });
        }

        // Editar usuario
        function editarUsuario(id) {
            fetch(`api/usuarios_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(usuario => {
                    document.getElementById('usuarioId').value = usuario.user_id;
                    document.getElementById('username').value = usuario.username;
                    document.getElementById('email').value = usuario.email;
                    document.getElementById('first_name').value = usuario.first_name;
                    document.getElementById('last_name').value = usuario.last_name;
                    document.getElementById('phone').value = usuario.phone || '';
                    document.getElementById('address').value = usuario.address || '';
                    document.getElementById('is_admin').checked = usuario.is_admin;
                    document.getElementById('is_active').checked = usuario.is_active;
                    document.getElementById('password').removeAttribute('required');
                    document.getElementById('tituloModal').textContent = 'Editar Usuario';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar usuario', 'error');
                });
        }

        // Guardar usuario
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('usuarioId').value;
            const datos = {
                id: id || null,
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                is_admin: document.getElementById('is_admin').checked,
                is_active: document.getElementById('is_active').checked
            };

            fetch('api/usuarios_api.php?action=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta(id ? 'Usuario actualizado' : 'Usuario creado', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
                    limpiarFormulario('formUsuario');
                    cargarUsuarios();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar usuario', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarUsuario', 'tablaUsuarios');

        // Limpiar modal al cerrar
        document.getElementById('modalUsuario').addEventListener('hidden.bs.modal', function() {
            limpiarFormulario('formUsuario');
            document.getElementById('usuarioId').value = '';
            document.getElementById('password').setAttribute('required', 'required');
            document.getElementById('tituloModal').textContent = 'Nuevo Usuario';
        });

        // Cargar usuarios al iniciar
        cargarUsuarios();
    </script>
</body>
</html>

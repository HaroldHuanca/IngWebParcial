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
                <li><a href="autores.php"><i class="bi bi-person-badge"></i> Autores</a></li>
                <li><a href="libros.php"><i class="bi bi-book"></i> Libros</a></li>
                <li><a href="pedidos.php"><i class="bi bi-bag"></i> Pedidos</a></li>
                <li><a href="pagos.php" class="active"><i class="bi bi-credit-card"></i> Pagos</a></li>
            </ul>
        </aside>

        <!-- Contenido Principal -->
        <main class="dashboard-content col-md-9 col-lg-10">
            <div class="container-fluid">
                <!-- Encabezado -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div>
                            <h1 class="h2 mb-0"><i class="bi bi-credit-card"></i> Gestión de Pagos</h1>
                            <p class="text-muted">Administra las transacciones de pago</p>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda y Filtros -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarPago" placeholder="Buscar por ID o usuario...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="filtroEstadoPago">
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="completed">Completado</option>
                            <option value="failed">Fallido</option>
                            <option value="refunded">Reembolsado</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla de Pagos -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaPagos">
                                <thead>
                                    <tr>
                                        <th>ID Transacción</th>
                                        <th>ID Pedido</th>
                                        <th>Usuario</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
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

    <!-- Modal para Ver/Editar Pago -->
    <div class="modal fade" id="modalPago" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Detalles de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPago">
                    <div class="modal-body">
                        <input type="hidden" id="pagoId">
                        
                        <div class="form-group mb-3">
                            <label class="form-label">ID Transacción</label>
                            <input type="text" class="form-control" id="transaction_id" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">ID Pedido</label>
                            <input type="text" class="form-control" id="order_id" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario_info" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Monto</label>
                            <input type="text" class="form-control" id="amount" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Método de Pago</label>
                            <input type="text" class="form-control" id="payment_method" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Referencia de Pago</label>
                            <input type="text" class="form-control" id="payment_reference" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="text" class="form-control" id="transaction_date" disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="status">
                                <option value="pending">Pendiente</option>
                                <option value="completed">Completado</option>
                                <option value="failed">Fallido</option>
                                <option value="refunded">Reembolsado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notas</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer2.php'; ?>

    <script>
        // Cargar pagos
        function cargarPagos() {
            fetch('api/pagos_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(pago => {
                        const fila = document.createElement('tr');
                        const estadoClase = {
                            'pending': 'badge-warning',
                            'completed': 'badge-success',
                            'failed': 'badge-danger',
                            'refunded': 'badge-info'
                        };
                        const estadoTexto = {
                            'pending': 'Pendiente',
                            'completed': 'Completado',
                            'failed': 'Fallido',
                            'refunded': 'Reembolsado'
                        };
                        
                        fila.innerHTML = `
                            <td>#${pago.transaction_id}</td>
                            <td>#${pago.order_id}</td>
                            <td>${pago.username}</td>
                            <td>S/ ${parseFloat(pago.amount).toFixed(2)}</td>
                            <td>${pago.payment_method}</td>
                            <td>
                                <span class="badge ${estadoClase[pago.status] || 'badge-secondary'}">
                                    ${estadoTexto[pago.status] || pago.status}
                                </span>
                            </td>
                            <td>${formatearFecha(pago.transaction_date)}</td>
                            <td>
                                <button class="btn btn-info btn-sm btn-icon" onclick="verPago(${pago.transaction_id})" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-icon" onclick="eliminarPago(${pago.transaction_id})" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar pagos', 'error');
                });
        }

        // Ver pago
        function verPago(id) {
            fetch(`api/pagos_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(pago => {
                    document.getElementById('pagoId').value = pago.transaction_id;
                    document.getElementById('transaction_id').value = '#' + pago.transaction_id;
                    document.getElementById('order_id').value = '#' + pago.order_id;
                    document.getElementById('usuario_info').value = pago.username + ' (' + pago.email + ')';
                    document.getElementById('amount').value = 'S/ ' + parseFloat(pago.amount).toFixed(2);
                    document.getElementById('payment_method').value = pago.payment_method;
                    document.getElementById('payment_reference').value = pago.payment_reference || '-';
                    document.getElementById('transaction_date').value = formatearFecha(pago.transaction_date);
                    document.getElementById('status').value = pago.status;
                    document.getElementById('notes').value = pago.notes || '';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalPago'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar pago', 'error');
                });
        }

        // Eliminar pago
        function eliminarPago(id) {
            confirmarEliminacion(() => {
                fetch('api/pagos_api.php?action=eliminar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Pago eliminado correctamente', 'success');
                        cargarPagos();
                    } else {
                        mostrarAlerta(data.message || 'Error al eliminar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al eliminar pago', 'error');
                });
            });
        }

        // Guardar cambios del pago
        document.getElementById('formPago').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('pagoId').value;
            const datos = {
                id: id,
                status: document.getElementById('status').value,
                notes: document.getElementById('notes').value
            };

            fetch('api/pagos_api.php?action=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Pago actualizado', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalPago')).hide();
                    cargarPagos();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar pago', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarPago', 'tablaPagos');

        // Filtro por estado
        document.getElementById('filtroEstadoPago').addEventListener('change', function() {
            const estado = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaPagos tbody tr');
            
            filas.forEach(fila => {
                if (!estado || fila.textContent.includes(estado)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });

        // Cargar pagos al iniciar
        cargarPagos();
    </script>
</body>
</html>

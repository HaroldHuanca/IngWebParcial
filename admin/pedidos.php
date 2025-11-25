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
                <li><a href="pedidos.php" class="active"><i class="bi bi-bag"></i> Pedidos</a></li>
                <li><a href="pagos.php"><i class="bi bi-credit-card"></i> Pagos</a></li>
            </ul>
        </aside>

        <!-- Contenido Principal -->
        <main class="dashboard-content col-md-9 col-lg-10">
            <div class="container-fluid">
                <!-- Encabezado -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div>
                            <h1 class="h2 mb-0"><i class="bi bi-bag"></i> Gestión de Pedidos</h1>
                            <p class="text-muted">Administra los pedidos de los clientes</p>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda y Filtros -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarPedido" placeholder="Buscar por ID o usuario...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="filtroEstado">
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="processing">En Proceso</option>
                            <option value="shipped">Enviado</option>
                            <option value="completed">Completado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla de Pedidos -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaPedidos">
                                <thead>
                                    <tr>
                                        <th>ID Pedido</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Pago</th>
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

    <!-- Modal para Ver/Editar Pedido -->
    <div class="modal fade" id="modalPedido" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Detalles del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPedido">
                    <div class="modal-body">
                        <input type="hidden" id="pedidoId">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">ID Pedido</label>
                                <input type="text" class="form-control" id="order_id" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha</label>
                                <input type="text" class="form-control" id="order_date" disabled>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario_info" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Dirección de Envío</label>
                            <textarea class="form-control" id="shipping_address" disabled rows="3"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Costo de Envío</label>
                                <input type="text" class="form-control" id="shipping_cost" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control" id="total_amount" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Estado del Pedido</label>
                                    <select class="form-select" id="status">
                                        <option value="pending">Pendiente</option>
                                        <option value="processing">En Proceso</option>
                                        <option value="shipped">Enviado</option>
                                        <option value="completed">Completado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Estado de Pago</label>
                                    <select class="form-select" id="payment_status">
                                        <option value="pending">Pendiente</option>
                                        <option value="completed">Completado</option>
                                        <option value="failed">Fallido</option>
                                        <option value="refunded">Reembolsado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="detallesItems" class="mt-4">
                            <h6>Artículos del Pedido</h6>
                            <div id="itemsList"></div>
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
        // Cargar pedidos
        function cargarPedidos() {
            fetch('api/pedidos_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(pedido => {
                        const fila = document.createElement('tr');
                        const estadoClase = {
                            'pending': 'badge-warning',
                            'processing': 'badge-info',
                            'shipped': 'badge-primary',
                            'completed': 'badge-success',
                            'cancelled': 'badge-danger'
                        };
                        const estadoTexto = {
                            'pending': 'Pendiente',
                            'processing': 'En Proceso',
                            'shipped': 'Enviado',
                            'completed': 'Completado',
                            'cancelled': 'Cancelado'
                        };
                        
                        fila.innerHTML = `
                            <td>#${pedido.order_id}</td>
                            <td>${pedido.username}</td>
                            <td>${formatearFecha(pedido.order_date)}</td>
                            <td>S/ ${parseFloat(pedido.total_amount).toFixed(2)}</td>
                            <td>
                                <span class="badge ${estadoClase[pedido.status] || 'badge-secondary'}">
                                    ${estadoTexto[pedido.status] || pedido.status}
                                </span>
                            </td>
                            <td>
                                <span class="badge ${pedido.payment_status === 'completed' ? 'badge-success' : 'badge-warning'}">
                                    ${pedido.payment_status === 'completed' ? 'Pagado' : 'Pendiente'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm btn-icon" onclick="verPedido(${pedido.order_id})" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-icon" onclick="eliminarPedido(${pedido.order_id})" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar pedidos', 'error');
                });
        }

        // Ver pedido
        function verPedido(id) {
            fetch(`api/pedidos_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(pedido => {
                    document.getElementById('pedidoId').value = pedido.order_id;
                    document.getElementById('order_id').value = '#' + pedido.order_id;
                    document.getElementById('order_date').value = formatearFecha(pedido.order_date);
                    document.getElementById('usuario_info').value = pedido.username + ' (' + pedido.email + ')';
                    document.getElementById('shipping_address').value = pedido.shipping_address;
                    document.getElementById('shipping_cost').value = 'S/ ' + parseFloat(pedido.shipping_cost).toFixed(2);
                    document.getElementById('total_amount').value = 'S/ ' + parseFloat(pedido.total_amount).toFixed(2);
                    document.getElementById('status').value = pedido.status;
                    document.getElementById('payment_status').value = pedido.payment_status;
                    
                    // Cargar items
                    fetch(`api/pedidos_api.php?action=items&id=${id}`)
                        .then(response => response.json())
                        .then(items => {
                            let itemsHTML = '<ul class="list-group">';
                            items.forEach(item => {
                                itemsHTML += `
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>${item.title}</span>
                                        <span>Qty: ${item.quantity} x S/ ${parseFloat(item.price_at_time).toFixed(2)}</span>
                                    </li>
                                `;
                            });
                            itemsHTML += '</ul>';
                            document.getElementById('itemsList').innerHTML = itemsHTML;
                        });
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalPedido'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar pedido', 'error');
                });
        }

        // Eliminar pedido
        function eliminarPedido(id) {
            confirmarEliminacion(() => {
                fetch('api/pedidos_api.php?action=eliminar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Pedido eliminado correctamente', 'success');
                        cargarPedidos();
                    } else {
                        mostrarAlerta(data.message || 'Error al eliminar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al eliminar pedido', 'error');
                });
            });
        }

        // Guardar cambios del pedido
        document.getElementById('formPedido').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('pedidoId').value;
            const datos = {
                id: id,
                status: document.getElementById('status').value,
                payment_status: document.getElementById('payment_status').value
            };

            fetch('api/pedidos_api.php?action=guardar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Pedido actualizado', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalPedido')).hide();
                    cargarPedidos();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar pedido', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarPedido', 'tablaPedidos');

        // Filtro por estado
        document.getElementById('filtroEstado').addEventListener('change', function() {
            const estado = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaPedidos tbody tr');
            
            filas.forEach(fila => {
                if (!estado || fila.textContent.includes(estado)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });

        // Cargar pedidos al iniciar
        cargarPedidos();
    </script>
</body>
</html>

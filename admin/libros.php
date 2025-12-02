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
                <li><a href="libros.php" class="active"><i class="bi bi-book"></i> Libros</a></li>
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
                                <h1 class="h2 mb-0"><i class="bi bi-book"></i> Gestión de Libros</h1>
                                <p class="text-muted">Administra el catálogo de libros disponibles</p>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLibro">
                                <i class="bi bi-plus-circle"></i> Nuevo Libro
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="buscarLibro" placeholder="Buscar por título, ISBN o autor...">
                        </div>
                    </div>
                </div>

                <!-- Tabla de Libros -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaLibros">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Título</th>
                                        <th>ISBN</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Editorial</th>
                                        <th>Destacado</th>
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

    <!-- Modal para Crear/Editar Libro -->
    <div class="modal fade" id="modalLibro" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Nuevo Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formLibro">
                    <div class="modal-body">
                        <input type="hidden" id="libroId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" id="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Autor</label>
                                    <select class="form-select" id="author_id" required>
                                        <option value="">Cargando autores...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" class="form-control" id="isbn" placeholder="978-0-123456-78-9">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Categoría</label>
                                    <select class="form-select" id="category_id" required>
                                        <option value="">Cargando categorías...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Precio (S/)</label>
                                    <input type="number" class="form-control" id="price" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Editorial</label>
                                    <input type="text" class="form-control" id="publisher">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Idioma</label>
                                    <input type="text" class="form-control" id="language" placeholder="Español">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fecha de Publicación</label>
                                    <input type="date" class="form-control" id="publication_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Páginas</label>
                                    <input type="number" class="form-control" id="page_count">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Formato</label>
                            <select class="form-select" id="format">
                                <option value="">Seleccionar</option>
                                <option value="Tapa Dura">Tapa Dura</option>
                                <option value="Tapa Blanda">Tapa Blanda</option>
                                <option value="eBook">eBook</option>
                                <option value="Audiobook">Audiobook</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Portada (imagen)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="currentImageContainer" style="display:none;">
                                        <label class="form-label">Imagen Actual</label>
                                        <img id="currentImage" src="" alt="Imagen actual" style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nueva Imagen</label>
                                    <input type="file" class="form-control" id="cover_image" accept=".png,.jpg,.jpeg,.webp">
                                    <div id="previewImageContainer" style="display:none; margin-top: 10px;">
                                        <img id="previewImage" src="" alt="Vista previa" style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured">
                            <label class="form-check-label" for="is_featured">
                                Destacado
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
        // Cargar categorías
        function cargarCategorias() {
            return fetch('api/categorias_api.php?action=listar')
                .then(response => response.json())
                .then(categorias => {
                    // Ordenar alfabéticamente
                    categorias.sort((a, b) => a.name.localeCompare(b.name));
                    
                    const select = document.getElementById('category_id');
                    select.innerHTML = '<option value="">Seleccionar Categoría</option>';
                    categorias.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.category_id;
                        option.textContent = cat.name;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar categorías:', error));
        }

        // Cargar autores
        function cargarAutores() {
            return fetch('api/autores_api.php?action=listar')
                .then(response => response.json())
                .then(autores => {
                    const select = document.getElementById('author_id');
                    select.innerHTML = '<option value="">Seleccionar Autor</option>';
                    autores.forEach(autor => {
                        const option = document.createElement('option');
                        option.value = autor.author_id;
                        option.textContent = `${autor.first_name} ${autor.last_name}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar autores:', error));
        }

        // Cargar libros
        function cargarLibros() {
            fetch('api/libros_api.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('cuerpoTabla');
                    tbody.innerHTML = '';
                    
                    data.forEach(libro => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${libro.book_id}</td>
                            <td>${libro.title}</td>
                            <td>${libro.isbn || '-'}</td>
                            <td>S/ ${parseFloat(libro.price).toFixed(2)}</td>
                            <td>
                                <span class="badge ${libro.stock > 0 ? 'badge-success' : 'badge-danger'}">
                                    ${libro.stock}
                                </span>
                            </td>
                            <td>${libro.publisher || '-'}</td>
                            <td>
                                <span class="badge ${libro.is_featured ? 'badge-primary' : 'badge-secondary'}">
                                    ${libro.is_featured ? 'Sí' : 'No'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-icon" onclick="editarLibro(${libro.book_id})" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar libros', 'error');
                });
        }

        // Editar libro
        function editarLibro(id) {
            fetch(`api/libros_api.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(libro => {
                    document.getElementById('libroId').value = libro.book_id;
                    document.getElementById('title').value = libro.title;
                    document.getElementById('isbn').value = libro.isbn || '';
                    document.getElementById('description').value = libro.description || '';
                    document.getElementById('price').value = libro.price;
                    document.getElementById('stock').value = libro.stock;
                    document.getElementById('publisher').value = libro.publisher || '';
                    document.getElementById('language').value = libro.language || '';
                    document.getElementById('publication_date').value = libro.publication_date || '';
                    document.getElementById('page_count').value = libro.page_count || '';
                    document.getElementById('format').value = libro.format || '';
                    document.getElementById('cover_image').value = '';
                    document.getElementById('is_featured').checked = libro.is_featured;
                    document.getElementById('author_id').value = libro.author_id || '';
                    document.getElementById('category_id').value = libro.category_id || '';
                    document.getElementById('tituloModal').textContent = 'Editar Libro';
                    
                    // Mostrar imagen actual si existe
                    const currentImageContainer = document.getElementById('currentImageContainer');
                    const currentImage = document.getElementById('currentImage');
                    const previewImageContainer = document.getElementById('previewImageContainer');
                    const previewImage = document.getElementById('previewImage');
                    
                    // Buscar imagen existente en las extensiones permitidas
                    const allowedExt = ['png', 'jpg', 'jpeg', 'webp'];
                    let imageFound = false;
                    
                    // Usar la extensión guardada si existe, sino intentar detectar
                    if (libro.image_extension) {
                        const imagePath = `../books/${libro.book_id}.${libro.image_extension}`;
                        currentImage.src = imagePath;
                        currentImageContainer.style.display = 'block';
                    } else {
                        // Fallback para libros antiguos sin extensión guardada
                        const checkImage = (ext) => {
                            const imagePath = `../books/${libro.book_id}.${ext}`;
                            const img = new Image();
                            img.onload = () => {
                                currentImage.src = imagePath;
                                currentImageContainer.style.display = 'block';
                                imageFound = true;
                            };
                            img.onerror = () => {
                                if (!imageFound && ext !== allowedExt[allowedExt.length - 1]) {
                                    checkImage(allowedExt[allowedExt.indexOf(ext) + 1]);
                                }
                            };
                            img.src = imagePath;
                        };
                        checkImage(allowedExt[0]);
                    }
                    
                    currentImageContainer.style.display = 'none';
                    previewImageContainer.style.display = 'none';
                    
                    const modal = new bootstrap.Modal(document.getElementById('modalLibro'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar libro', 'error');
                });
        }

        // Vista previa de imagen al seleccionar archivo
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const previewImageContainer = document.getElementById('previewImageContainer');
            const previewImage = document.getElementById('previewImage');
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewImage.src = event.target.result;
                    previewImageContainer.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewImageContainer.style.display = 'none';
            }
        });

        // Guardar libro
        document.getElementById('formLibro').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('libroId').value;
            const formData = new FormData();
            formData.append('id', id || '');
            formData.append('title', document.getElementById('title').value);
            formData.append('isbn', document.getElementById('isbn').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('stock', document.getElementById('stock').value);
            formData.append('publisher', document.getElementById('publisher').value);
            formData.append('language', document.getElementById('language').value);
            formData.append('publication_date', document.getElementById('publication_date').value);
            formData.append('page_count', document.getElementById('page_count').value);
            formData.append('format', document.getElementById('format').value);
            formData.append('is_featured', document.getElementById('is_featured').checked ? '1' : '0');
            formData.append('author_id', document.getElementById('author_id').value);
            formData.append('category_id', document.getElementById('category_id').value);

            const coverInput = document.getElementById('cover_image');
            if (coverInput.files && coverInput.files.length > 0) {
                formData.append('cover_image', coverInput.files[0]);
            }

            fetch('api/libros_api.php?action=guardar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta(id ? 'Libro actualizado' : 'Libro creado', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalLibro')).hide();
                    limpiarFormulario('formLibro');
                    cargarLibros();
                } else {
                    mostrarAlerta(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar libro', 'error');
            });
        });

        // Búsqueda
        buscarEnTabla('buscarLibro', 'tablaLibros');

        // Limpiar modal al cerrar
        document.getElementById('modalLibro').addEventListener('hidden.bs.modal', function() {
            limpiarFormulario('formLibro');
            document.getElementById('libroId').value = '';
            document.getElementById('tituloModal').textContent = 'Nuevo Libro';
            
            // Limpiar imágenes
            document.getElementById('currentImageContainer').style.display = 'none';
            document.getElementById('currentImage').src = '';
            document.getElementById('previewImageContainer').style.display = 'none';
            document.getElementById('previewImage').src = '';
        });

        // Cargar datos al iniciar
        Promise.all([cargarAutores(), cargarCategorias(), cargarLibros()]);
    </script>
</body>
</html>

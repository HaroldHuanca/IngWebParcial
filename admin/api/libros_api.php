<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

include '../../includes/conexion.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        listarLibros();
        break;
    case 'obtener':
        obtenerLibro();
        break;
    case 'guardar':
        guardarLibro();
        break;
    case 'eliminar':
        eliminarLibro();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarLibros() {
    global $con;
    $result = $con->query("SELECT * FROM books ORDER BY book_id DESC");
    $libros = [];
    
    while ($row = $result->fetch_assoc()) {
        $libros[] = $row;
    }
    
    echo json_encode($libros);
}

function obtenerLibro() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $stmt = $con->prepare("SELECT b.*, ba.author_id, bc.category_id FROM books b LEFT JOIN book_authors ba ON b.book_id = ba.book_id LEFT JOIN book_categories bc ON b.book_id = bc.book_id WHERE b.book_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $libro = $result->fetch_assoc();
    
    echo json_encode($libro ?: ['error' => 'Libro no encontrado']);
}

function guardarLibro() {
    global $con;
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : null;
    $title = $_POST['title'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $publisher = $_POST['publisher'] ?? '';
    $language = $_POST['language'] ?? '';
    $publication_date = $_POST['publication_date'] ?? null;
    $page_count = isset($_POST['page_count']) && $_POST['page_count'] !== '' ? intval($_POST['page_count']) : 0;
    $format = $_POST['format'] ?? '';
    $is_featured = isset($_POST['is_featured']) && $_POST['is_featured'] == '1' ? 1 : 0;
    $author_id = isset($_POST['author_id']) && $_POST['author_id'] !== '' ? intval($_POST['author_id']) : null;
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : null;
    
    if (empty($title) || $price <= 0 || $stock < 0 || empty($category_id)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos. Asegúrese de seleccionar una categoría.']);
        return;
    }
    
    if ($id) {
        // Actualizar
        $stmt = $con->prepare("UPDATE books SET title=?, isbn=?, description=?, price=?, stock=?, publisher=?, language=?, publication_date=?, page_count=?, format=?, is_featured=? WHERE book_id=?");
        $stmt->bind_param("sssdisssisis", $title, $isbn, $description, $price, $stock, $publisher, $language, $publication_date, $page_count, $format, $is_featured, $id);
    } else {
        // Crear
        $stmt = $con->prepare("INSERT INTO books (title, isbn, description, price, stock, publisher, language, publication_date, page_count, format, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdisssisi", $title, $isbn, $description, $price, $stock, $publisher, $language, $publication_date, $page_count, $format, $is_featured);
    }
    
    if ($stmt->execute()) {
        $book_id = $id ? $id : $con->insert_id;

        // Guardar autor
        if ($author_id) {
            // Verificar si ya existe relación
            $check = $con->query("SELECT * FROM book_authors WHERE book_id = $book_id");
            if ($check->num_rows > 0) {
                $con->query("UPDATE book_authors SET author_id = $author_id WHERE book_id = $book_id");
            } else {
                $con->query("INSERT INTO book_authors (book_id, author_id) VALUES ($book_id, $author_id)");
            }
        }

        // Guardar categoría
        if ($category_id) {
            // Verificar si ya existe relación
            $check = $con->query("SELECT * FROM book_categories WHERE book_id = $book_id");
            if ($check->num_rows > 0) {
                $con->query("UPDATE book_categories SET category_id = $category_id WHERE book_id = $book_id");
            } else {
                $con->query("INSERT INTO book_categories (book_id, category_id) VALUES ($book_id, $category_id)");
            }
        }

        // Manejo de imagen de portada
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['png', 'jpg', 'jpeg', 'webp'];
            $file_tmp = $_FILES['cover_image']['tmp_name'];
            $original_name = $_FILES['cover_image']['name'];
            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            // Log para debug
            error_log("Intento de subida: " . print_r($_FILES['cover_image'], true));
            error_log("Extension detectada: " . $ext);

            if (in_array($ext, $allowed_ext)) {
                $baseDir = dirname(__DIR__, 2) . '/books';
                if (!is_dir($baseDir)) {
                    mkdir($baseDir, 0777, true);
                }

                // Eliminar archivos anteriores del libro con cualquier extensión permitida
                foreach ($allowed_ext as $oldExt) {
                    $oldPath = $baseDir . '/' . $book_id . '.' . $oldExt;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $destPath = $baseDir . '/' . $book_id . '.' . $ext;
                error_log("Intentando mover archivo a: " . $destPath);
                
                if (move_uploaded_file($file_tmp, $destPath)) {
                    error_log("Archivo movido exitosamente");
                    // Actualizar extensión en base de datos
                    $stmtUpdate = $con->prepare("UPDATE books SET image_extension = ? WHERE book_id = ?");
                    $stmtUpdate->bind_param("si", $ext, $book_id);
                    $stmtUpdate->execute();
                } else {
                    $lastError = error_get_last();
                    $errorMsg = "Fallo al mover archivo. Error PHP: " . ($lastError['message'] ?? 'Desconocido');
                    error_log($errorMsg);
                    echo json_encode(['success' => false, 'message' => $errorMsg]);
                    return;
                }
            } else {
                $errorMsg = "Extensión no permitida: " . $ext;
                error_log($errorMsg);
                echo json_encode(['success' => false, 'message' => $errorMsg]);
                return;
            }
        } else {
             if (isset($_FILES['cover_image'])) {
                $errorMsg = "Error en subida: Código " . $_FILES['cover_image']['error'];
                error_log($errorMsg);
                echo json_encode(['success' => false, 'message' => $errorMsg]);
                return;
             }
        }

        echo json_encode(['success' => true, 'message' => 'Libro guardado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarLibro() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Libro eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

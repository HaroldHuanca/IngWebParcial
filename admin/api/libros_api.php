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
    
    $stmt = $con->prepare("SELECT * FROM books WHERE book_id = ?");
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
    
    if (empty($title) || $price <= 0 || $stock < 0) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
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

        // Manejo de imagen de portada
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['png', 'jpg', 'jpeg', 'webp'];
            $file_tmp = $_FILES['cover_image']['tmp_name'];
            $original_name = $_FILES['cover_image']['name'];
            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed_ext)) {
                $baseDir = dirname(__DIR__, 2) . '/books';
                if (!is_dir($baseDir)) {
                    @mkdir($baseDir, 0777, true);
                }

                // Eliminar archivos anteriores del libro con cualquier extensión permitida
                foreach ($allowed_ext as $oldExt) {
                    $oldPath = $baseDir . '/' . $book_id . '.' . $oldExt;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $destPath = $baseDir . '/' . $book_id . '.' . $ext;
                @move_uploaded_file($file_tmp, $destPath);
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

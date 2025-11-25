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
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = $datos['id'] ?? null;
    $title = $datos['title'] ?? '';
    $isbn = $datos['isbn'] ?? '';
    $description = $datos['description'] ?? '';
    $price = floatval($datos['price'] ?? 0);
    $stock = intval($datos['stock'] ?? 0);
    $publisher = $datos['publisher'] ?? '';
    $language = $datos['language'] ?? '';
    $publication_date = $datos['publication_date'] ?? null;
    $page_count = $datos['page_count'] ?? null;
    $format = $datos['format'] ?? '';
    $cover_image_url = $datos['cover_image_url'] ?? '';
    $is_featured = $datos['is_featured'] ? 1 : 0;
    
    if (empty($title) || $price <= 0 || $stock < 0) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        return;
    }
    
    if ($id) {
        // Actualizar
        $stmt = $con->prepare("UPDATE books SET title=?, isbn=?, description=?, price=?, stock=?, publisher=?, language=?, publication_date=?, page_count=?, format=?, cover_image_url=?, is_featured=? WHERE book_id=?");
        $stmt->bind_param("sssdisssisii", $title, $isbn, $description, $price, $stock, $publisher, $language, $publication_date, $page_count, $format, $cover_image_url, $is_featured, $id);
    } else {
        // Crear
        $stmt = $con->prepare("INSERT INTO books (title, isbn, description, price, stock, publisher, language, publication_date, page_count, format, cover_image_url, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdisssisis", $title, $isbn, $description, $price, $stock, $publisher, $language, $publication_date, $page_count, $format, $cover_image_url, $is_featured);
    }
    
    if ($stmt->execute()) {
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

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
        listarCategorias();
        break;
    case 'obtener':
        obtenerCategoria();
        break;
    case 'guardar':
        guardarCategoria();
        break;
    case 'eliminar':
        eliminarCategoria();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarCategorias() {
    global $con;
    $result = $con->query("SELECT * FROM categories ORDER BY category_id DESC");
    $categorias = [];
    
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
    
    echo json_encode($categorias);
}

function obtenerCategoria() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $stmt = $con->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $categoria = $result->fetch_assoc();
    
    echo json_encode($categoria ?: ['error' => 'Categoría no encontrada']);
}

function guardarCategoria() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = $datos['id'] ?? null;
    $name = $datos['name'] ?? '';
    $description = $datos['description'] ?? '';
    $parent_category_id = $datos['parent_category_id'] ?? null;
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
        return;
    }
    
    if ($id) {
        // Actualizar
        $stmt = $con->prepare("UPDATE categories SET name=?, description=?, parent_category_id=? WHERE category_id=?");
        $stmt->bind_param("ssii", $name, $description, $parent_category_id, $id);
    } else {
        // Crear
        $stmt = $con->prepare("INSERT INTO categories (name, description, parent_category_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $parent_category_id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Categoría guardada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarCategoria() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Categoría eliminada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

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
        listarAutores();
        break;
    case 'obtener':
        obtenerAutor();
        break;
    case 'guardar':
        guardarAutor();
        break;
    case 'eliminar':
        eliminarAutor();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarAutores() {
    global $con;
    $result = $con->query("SELECT * FROM authors ORDER BY author_id DESC");
    $autores = [];
    
    while ($row = $result->fetch_assoc()) {
        $autores[] = $row;
    }
    
    echo json_encode($autores);
}

function obtenerAutor() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $stmt = $con->prepare("SELECT * FROM authors WHERE author_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $autor = $result->fetch_assoc();
    
    echo json_encode($autor ?: ['error' => 'Autor no encontrado']);
}

function guardarAutor() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = $datos['id'] ?? null;
    $first_name = $datos['first_name'] ?? '';
    $last_name = $datos['last_name'] ?? '';
    $biography = $datos['biography'] ?? '';
    
    if (empty($first_name) || empty($last_name)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y apellido son requeridos']);
        return;
    }
    
    if ($id) {
        // Actualizar
        $stmt = $con->prepare("UPDATE authors SET first_name=?, last_name=?, biography=? WHERE author_id=?");
        $stmt->bind_param("sssi", $first_name, $last_name, $biography, $id);
    } else {
        // Crear
        $stmt = $con->prepare("INSERT INTO authors (first_name, last_name, biography) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $first_name, $last_name, $biography);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Autor guardado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarAutor() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("DELETE FROM authors WHERE author_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Autor eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

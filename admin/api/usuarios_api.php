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
        listarUsuarios();
        break;
    case 'obtener':
        obtenerUsuario();
        break;
    case 'guardar':
        guardarUsuario();
        break;
    case 'eliminar':
        eliminarUsuario();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarUsuarios() {
    global $con;
    $result = $con->query("SELECT * FROM users ORDER BY user_id DESC");
    $usuarios = [];
    
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    
    echo json_encode($usuarios);
}

function obtenerUsuario() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $stmt = $con->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    
    echo json_encode($usuario ?: ['error' => 'Usuario no encontrado']);
}

function guardarUsuario() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = $datos['id'] ?? null;
    $username = $datos['username'] ?? '';
    $email = $datos['email'] ?? '';
    $password = $datos['password'] ?? '';
    $first_name = $datos['first_name'] ?? '';
    $last_name = $datos['last_name'] ?? '';
    $phone = $datos['phone'] ?? '';
    $address = $datos['address'] ?? '';
    $is_admin = $datos['is_admin'] ? 1 : 0;
    $is_active = $datos['is_active'] ? 1 : 0;
    
    // Validaciones
    if (empty($username) || empty($email) || empty($first_name) || empty($last_name)) {
        echo json_encode(['success' => false, 'message' => 'Campos requeridos vacíos']);
        return;
    }
    
    if ($id) {
        // Actualizar
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $con->prepare("UPDATE users SET username=?, email=?, password_hash=?, first_name=?, last_name=?, phone=?, address=?, is_admin=?, is_active=? WHERE user_id=?");
            $stmt->bind_param("ssssssssii", $username, $email, $password_hash, $first_name, $last_name, $phone, $address, $is_admin, $is_active, $id);
        } else {
            $stmt = $con->prepare("UPDATE users SET username=?, email=?, first_name=?, last_name=?, phone=?, address=?, is_admin=?, is_active=? WHERE user_id=?");
            $stmt->bind_param("sssssssii", $username, $email, $first_name, $last_name, $phone, $address, $is_admin, $is_active, $id);
        }
    } else {
        // Crear
        if (empty($password)) {
            echo json_encode(['success' => false, 'message' => 'La contraseña es requerida']);
            return;
        }
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $con->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, phone, address, is_admin, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssii", $username, $email, $password_hash, $first_name, $last_name, $phone, $address, $is_admin, $is_active);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario guardado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarUsuario() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

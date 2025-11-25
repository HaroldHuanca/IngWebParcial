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
        listarPedidos();
        break;
    case 'obtener':
        obtenerPedido();
        break;
    case 'items':
        obtenerItems();
        break;
    case 'guardar':
        guardarPedido();
        break;
    case 'eliminar':
        eliminarPedido();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarPedidos() {
    global $con;
    $query = "SELECT o.*, u.username, u.email FROM orders o 
              JOIN users u ON o.user_id = u.user_id 
              ORDER BY o.order_id DESC";
    $result = $con->query($query);
    $pedidos = [];
    
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
    
    echo json_encode($pedidos);
}

function obtenerPedido() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $query = "SELECT o.*, u.username, u.email FROM orders o 
              JOIN users u ON o.user_id = u.user_id 
              WHERE o.order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();
    
    echo json_encode($pedido ?: ['error' => 'Pedido no encontrado']);
}

function obtenerItems() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $query = "SELECT oi.*, b.title FROM order_items oi 
              JOIN books b ON oi.book_id = b.book_id 
              WHERE oi.order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    echo json_encode($items);
}

function guardarPedido() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = intval($datos['id'] ?? 0);
    $status = $datos['status'] ?? '';
    $payment_status = $datos['payment_status'] ?? '';
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("UPDATE orders SET status=?, payment_status=? WHERE order_id=?");
    $stmt->bind_param("ssi", $status, $payment_status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pedido actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarPedido() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    // Eliminar items primero
    $stmt = $con->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Luego eliminar el pedido
    $stmt = $con->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pedido eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

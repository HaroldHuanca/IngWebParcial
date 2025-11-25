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
        listarPagos();
        break;
    case 'obtener':
        obtenerPago();
        break;
    case 'guardar':
        guardarPago();
        break;
    case 'eliminar':
        eliminarPago();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarPagos() {
    global $con;
    $query = "SELECT pt.*, u.username, u.email FROM payment_transactions pt 
              JOIN users u ON pt.user_id = u.user_id 
              ORDER BY pt.transaction_id DESC";
    $result = $con->query($query);
    $pagos = [];
    
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    
    echo json_encode($pagos);
}

function obtenerPago() {
    global $con;
    $id = intval($_GET['id'] ?? 0);
    
    $query = "SELECT pt.*, u.username, u.email FROM payment_transactions pt 
              JOIN users u ON pt.user_id = u.user_id 
              WHERE pt.transaction_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pago = $result->fetch_assoc();
    
    echo json_encode($pago ?: ['error' => 'Pago no encontrado']);
}

function guardarPago() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    
    $id = intval($datos['id'] ?? 0);
    $status = $datos['status'] ?? '';
    $notes = $datos['notes'] ?? '';
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("UPDATE payment_transactions SET status=?, notes=? WHERE transaction_id=?");
    $stmt->bind_param("ssi", $status, $notes, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pago actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

function eliminarPago() {
    global $con;
    $datos = json_decode(file_get_contents('php://input'), true);
    $id = intval($datos['id'] ?? 0);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        return;
    }
    
    $stmt = $con->prepare("DELETE FROM payment_transactions WHERE transaction_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pago eliminado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}
?>

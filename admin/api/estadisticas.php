<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

include '../../includes/conexion.php';

$estadisticas = [
    'usuarios' => 0,
    'libros' => 0,
    'pedidos' => 0,
    'ingresos' => 0
];

// Total de usuarios
$result = $con->query("SELECT COUNT(*) as total FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    $estadisticas['usuarios'] = $row['total'];
}

// Total de libros
$result = $con->query("SELECT COUNT(*) as total FROM books");
if ($result) {
    $row = $result->fetch_assoc();
    $estadisticas['libros'] = $row['total'];
}

// Total de pedidos
$result = $con->query("SELECT COUNT(*) as total FROM orders");
if ($result) {
    $row = $result->fetch_assoc();
    $estadisticas['pedidos'] = $row['total'];
}

// Total de ingresos
$result = $con->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
if ($result) {
    $row = $result->fetch_assoc();
    $estadisticas['ingresos'] = $row['total'] ? floatval($row['total']) : 0;
}

echo json_encode($estadisticas);
?>

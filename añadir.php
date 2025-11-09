<?php
session_start();
require 'includes/conexion.php';

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($book_id > 0) {
    // Inicializar el carrito si no existe
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Verificar si el libro ya está en el carrito
    if (isset($_SESSION['cart'][$book_id])) {
        // Si ya existe, incrementar la cantidad
        $_SESSION['cart'][$book_id]['quantity']++;
    } else {
        // Si no existe, agregarlo con cantidad 1
        $_SESSION['cart'][$book_id] = array(
            'quantity' => 1,
            'added_date' => date('Y-m-d H:i:s') // Opcional: para saber cuándo se añadió
        );
    }

    $success = true;
}

// Guardar en sesión si fue exitoso
$_SESSION['show_alert'] = $success;

// Redirigir
header('Location: ' . trim($_GET['envio'], "'"));
exit();
?>

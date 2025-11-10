<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'includes/conexion.php';

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$precio = isset($_GET['precio']) ? floatval($_GET['precio']) : 0;
$user_id = $_SESSION['user_id'] ?? null;

if ($book_id > 0 && $precio > 0) {
    // Inicializar el carrito si no existe
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
        if($user_id){
            $sql = "select * from shopping_carts where cart_id = $user_id;";
            $result = $con->query($sql);
            if($result && $result->num_rows == 0){
                $sql = "INSERT INTO shopping_carts (cart_id,user_id created_at) VALUES ($user_id, $user_id, NOW());";
            }
        }
    }

    // Verificar si el libro ya está en el carrito
    if (isset($_SESSION['cart'][$book_id])) {
        // Si ya existe, incrementar la cantidad
        $_SESSION['cart'][$book_id]['quantity']++;
        $_SESSION['cart'][$book_id]['price_at_time'] = $precio; // Actualizar el precio si es necesario
        if($user_id){
            $sql = "UPDATE cart_items ci
            JOIN shopping_carts sc ON ci.cart_id = sc.cart_id
            SET ci.quantity = ci.quantity + 1, ci.price_at_time = $precio
            WHERE sc.user_id = $user_id AND ci.book_id = $book_id;";
            $con->query($sql);
        }
    } else {
        // Si no existe, agregarlo con cantidad 1
        $_SESSION['cart'][$book_id] = array(
            'quantity' => 1,
            'price_at_time' => $precio // Opcional: para saber cuándo se añadió
        );
        if($user_id){
            $sql = "INSERT INTO cart_items (cart_id, book_id, quantity, price_at_time)
            VALUES (
                (SELECT cart_id FROM shopping_carts WHERE user_id = $user_id and book_id = $book_id),
                $book_id,
                1,
                $precio
            );";
            $con->query($sql);
        }
    }

    $success = true;
}

// Guardar en sesión si fue exitoso
$_SESSION['show_alert'] = $success;

// Redirigir
header('Location: ' . trim($_GET['envio'], "'"));
exit();
?>

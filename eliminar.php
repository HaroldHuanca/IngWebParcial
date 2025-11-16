<?php 
session_start();
include 'includes/conexion.php';
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    // Verificar si el carrito existe en la sesión
    if (isset($_SESSION['cart'])) {
        // Buscar y eliminar el libro del carrito
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['book_id'] == $book_id) {
                unset($_SESSION['cart'][$index]);
                // Reindexar el array del carrito
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE from cart_items WHERE user_id = $user_id AND book_id = $book_id;";
    $con->query($sql);
}

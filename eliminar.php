<?php 
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
include 'includes/conexion.php';
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    // Verificar si el carrito existe en la sesión
    if (isset($_SESSION['cart'])) {
        // Buscar y eliminar el libro del carrito
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($index == $book_id) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['eliminado'] = true;
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

header('Location: carrito.php');
exit();
?>

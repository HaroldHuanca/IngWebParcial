<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/conexion.php";

$book = $_GET['book'];
$eliminar = $_GET['eliminar'];
$user = $_GET['user'];
$msg = "";
if ($eliminar) {
    $sql = "DELETE from favorites where book_id = $book and user_id = $user";
    $con->query($sql);
    $msg = "Eliminado de favoritos";
} else {
    $sql = "INSERT INTO favorites (book_id, user_id, added_at) values($book,$user,NOW());";
    $con->query($sql);
    $msg = "Añadido a favoritos";
}

// Redirigir
header('Location: ' . trim($_GET['envio'], "'").'?msg=' . urlencode($msg));
exit();
?>
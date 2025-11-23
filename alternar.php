<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "includes/conexion.php";

$book = intval($_GET['book']);
$eliminar = intval($_GET['eliminar']);
$user = intval($_GET['user']);
$msg = "";
if ($eliminar) {
    $sql = "DELETE from favorites where book_id = ? and user_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $book, $user);
    $stmt->execute();
    $msg = "Eliminado de favoritos";
} else {
    $sql = "INSERT INTO favorites (book_id, user_id, added_at) values(?, ?, NOW());";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $book, $user);
    $stmt->execute();
    $msg = "Añadido a favoritos";
}

// Redirigir
$envio = isset($_GET['envio']) ? trim($_GET['envio'], "'") : 'index.php';
$signo = str_contains($envio,"?") ? "&" : "?";
header('Location: ' . $envio . $signo.'msg=' . urlencode($msg));
exit();
?>
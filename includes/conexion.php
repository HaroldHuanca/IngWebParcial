<?php

$servername = "127.0.0.1"; 
$username = "root";
$password = ""; 
$database = "bookport_db";
$port = 3307; 

// Crear conexión
$con = new mysqli($servername, $username, $password, $database, $port);

// Verificar conexión
if ($con->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

?>

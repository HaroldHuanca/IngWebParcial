<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "bookport_db";
$port = 3307;

// Crear conexión
$con = new mysqli($servername, $username, $password, $database,$port);

// Verificar conexión
if ($con->connect_error) {
    echo "<h1>¡Error al conectar con la base de datos!</h1>";
    //die("Error de conexión a la base de datos: " . $con->connect_error);
}
?>
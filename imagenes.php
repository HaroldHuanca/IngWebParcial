<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Carpeta donde se guardarán las imágenes
    $carpetaDestino = "imagenes/";

    // Verifica que la carpeta exista, si no, créala
    if (!file_exists($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    // Verifica permisos de escritura
    if (!is_writable($carpetaDestino)) {
        echo "<p>Error: La carpeta no tiene permisos de escritura.</p>";
        exit;
    }

    // Verifica que se haya enviado el archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        
        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreOriginal = $_FILES['imagen']['name'];
        $error = $_FILES['imagen']['error'];

        echo "<p>Archivo temporal: $archivoTmp</p>";
        echo "<p>Error code: $error</p>";

        // Obtener extensión del archivo
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

        // Crear un nuevo nombre basado en la fecha y hora
        $nuevoNombre = date("Ymd_His") . "." . $extension;

        // Ruta final
        $rutaFinal = $carpetaDestino . $nuevoNombre;

        // Mover archivo subido
        if (move_uploaded_file($archivoTmp, $rutaFinal)) {
            echo "<p>Imagen subida correctamente como: <strong>$nuevoNombre</strong></p>";
            echo "<p>Ruta: $rutaFinal</p>";
        } else {
            echo "<p>Error al mover el archivo.</p>";
            // Información adicional del error
            echo "<p>Último error: " . error_get_last()['message'] . "</p>";
        }
    } else {
        echo "<p>No se subió ninguna imagen o hubo un error.</p>";
        if (isset($_FILES['imagen'])) {
            echo "<p>Código de error: " . $_FILES['imagen']['error'] . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subir Imagen</title>
</head>
<body>

<h2>Subir Imagen</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="imagen" required>
    <button type="submit">Subir</button>
</form>

</body>
</html>
<?php
// test_upload.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Subida</title>
</head>
<body>
    <h1>Prueba de Subida de Archivos</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h2>Resultados:</h2>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";

        if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/books/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $destPath = $uploadDir . 'test_image_' . time() . '_' . basename($_FILES['test_file']['name']);
            
            echo "<p>Intentando mover a: $destPath</p>";
            
            if (move_uploaded_file($_FILES['test_file']['tmp_name'], $destPath)) {
                echo "<p style='color:green'><strong>¡ÉXITO! El archivo se subió correctamente.</strong></p>";
                echo "<p>Permisos del archivo: " . substr(sprintf('%o', fileperms($destPath)), -4) . "</p>";
                // Limpiar
                unlink($destPath);
                echo "<p>Archivo de prueba eliminado automáticamente.</p>";
            } else {
                echo "<p style='color:red'><strong>FALLO: move_uploaded_file retornó false.</strong></p>";
                echo "<p>Error PHP: " . error_get_last()['message'] . "</p>";
            }
        } else {
            echo "<p style='color:red'><strong>Error en la subida: Código " . $_FILES['test_file']['error'] . "</strong></p>";
        }
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <p>Selecciona tu imagen .jpeg (o cualquier otra):</p>
        <input type="file" name="test_file" required>
        <br><br>
        <button type="submit">Subir y Probar</button>
    </form>
</body>
</html>

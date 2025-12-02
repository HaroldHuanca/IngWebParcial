<?php
// check_env.php
// Forzar visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

// Desactivar buffer de salida para ver progreso en tiempo real
if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
ob_implicit_flush(1);

echo "Verificando entorno del servidor...\n";
echo "PHP Version: " . phpversion() . "\n";

// Intentar obtener usuario de forma segura
$currentUser = 'Desconocido';
if (function_exists('get_current_user')) {
    $currentUser = get_current_user();
}
echo "Usuario actual: " . $currentUser . "\n";

echo "Directorio actual: " . __DIR__ . "\n";

$booksDir = __DIR__ . '/books';

echo "\nVerificando directorio 'books'...\n";
if (file_exists($booksDir)) {
    echo " - El directorio existe.\n";
    
    // Intentar obtener permisos
    $perms = fileperms($booksDir);
    if ($perms !== false) {
        echo " - Permisos: " . substr(sprintf('%o', $perms), -4) . "\n";
    } else {
        echo " - No se pudieron leer los permisos.\n";
    }

    if (is_writable($booksDir)) {
        echo " - ES ESCRIBIBLE. Las subidas deberían funcionar.\n";
    } else {
        echo " - NO ES ESCRIBIBLE. Por favor, cambia los permisos a 777 usando tu cliente FTP (FileZilla).\n";
    }
} else {
    echo " - El directorio NO existe.\n";
    echo " - Intentando crear directorio...\n";
    if (mkdir($booksDir, 0777, true)) {
        echo " - Directorio creado exitosamente.\n";
    } else {
        $error = error_get_last();
        echo " - FALLO al crear directorio. Error: " . ($error['message'] ?? 'Desconocido') . "\n";
        echo " - Por favor, crea la carpeta 'books' manualmente y dale permisos 777.\n";
    }
}

echo "\nPrueba de escritura de archivo...\n";
$testFile = $booksDir . '/test.txt';
if (file_put_contents($testFile, 'Prueba de escritura')) {
    echo " - Archivo de prueba creado exitosamente.\n";
    if (unlink($testFile)) {
        echo " - Archivo de prueba eliminado.\n";
    } else {
        echo " - No se pudo eliminar el archivo de prueba.\n";
    }
} else {
    $error = error_get_last();
    echo " - FALLO al escribir archivo de prueba. Error: " . ($error['message'] ?? 'Desconocido') . "\n";
}
?>

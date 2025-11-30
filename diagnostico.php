<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Hosting</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; line-height: 1.6; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
        h2 { margin-top: 2rem; color: #333; border-bottom: 1px solid #eee; padding-bottom: 0.25rem; }
        .status { font-weight: bold; }
        .ok { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .card { background: #f9f9f9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>

<?php
// debug.php - Sube este archivo a tu hosting para ver errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de Hosting</h1>";

// 1. Chequear versión de PHP
echo "<h2>1. Versión de PHP</h2>";
echo "<div class='card'>";
echo "Versión actual: <strong>" . phpversion() . "</strong>";
if (version_compare(phpversion(), '7.4.0', '<')) {
    echo " <span class='status warning'>[ALERTA: Se recomienda PHP 7.4 o superior]</span>";
} else {
    echo " <span class='status ok'>[OK]</span>";
}
echo "</div>";

// 2. Chequear extensiones necesarias
echo "<h2>2. Extensiones</h2>";
echo "<div class='card'>";
$extensions = ['mysqli', 'curl', 'json', 'mbstring'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "$ext: <span class='status ok'>[OK]</span><br>";
    } else {
        echo "$ext: <span class='status error'>[FALTA]</span><br>";
    }
}
echo "</div>";

// 3. Chequear carpeta vendor
echo "<h2>3. Librerías (Vendor)</h2>";
echo "<div class='card'>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "vendor/autoload.php: <span class='status ok'>[ENCONTRADO]</span>";
} else {
    echo "vendor/autoload.php: <span class='status error'>[NO ENCONTRADO - Sube la carpeta vendor]</span>";
}
echo "</div>";

// 4. Chequear conexión a BD
echo "<h2>4. Base de Datos</h2>";
echo "<div class='card'>";
// Intentar incluir sin fallar fatalmente si hay error de sintaxis en conexion.php
if (file_exists('includes/conexion.php')) {
    try {
        include 'includes/conexion.php';
        if (isset($con) && $con->connect_error) {
            echo "Conexión: <span class='status error'>[FALLÓ]</span><br>";
            echo "Error: " . $con->connect_error;
        } elseif (isset($con)) {
            echo "Conexión: <span class='status ok'>[EXITOSA]</span><br>";
            echo "Host: " . $servername . "<br>";
            echo "Usuario: " . $username . "<br>";
            echo "Base de datos: " . $database . "<br>";
        } else {
             echo "Conexión: <span class='status error'>[INDEFINIDA]</span> (La variable \$con no se creó)";
        }
    } catch (Exception $e) {
        echo "Excepción al conectar: " . $e->getMessage();
    }
} else {
    echo "includes/conexion.php: <span class='status error'>[NO ENCONTRADO]</span>";
}
echo "</div>";

echo "<h2>5. Configuración Social</h2>";
echo "<div class='card'>";
if (file_exists(__DIR__ . '/includes/social_config.php')) {
    $config = include 'includes/social_config.php';
    echo "Archivo de configuración: <span class='status ok'>[ENCONTRADO]</span><br>";
    echo "Google Client ID configurado: " . (!empty($config['google']['client_id']) ? "<span class='status ok'>Sí</span>" : "<span class='status warning'>No</span>") . "<br>";
    echo "Facebook App ID configurado: " . (!empty($config['facebook']['app_id']) ? "<span class='status ok'>Sí</span>" : "<span class='status warning'>No</span>") . "<br>";
} else {
    echo "Archivo de configuración: <span class='status error'>[NO ENCONTRADO]</span>";
}
echo "</div>";

echo "<h2>6. Prueba de Conexión a Facebook</h2>";
echo "<div class='card'>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
// Descomentar la siguiente línea si hay problemas de SSL y quieres probar inseguro (NO RECOMENDADO PROD)
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 400) {
    echo "Conexión a Graph API: <span class='status ok'>[EXITOSA]</span> (HTTP $httpcode)<br>";
} elseif ($httpcode >= 400) {
    // Facebook devuelve 400 si no le mandas nada, pero significa que conectó
    echo "Conexión a Graph API: <span class='status ok'>[EXITOSA]</span> (Respondió HTTP $httpcode - Normal para petición vacía)<br>";
} else {
    echo "Conexión a Graph API: <span class='status error'>[FALLÓ]</span><br>";
    echo "Error cURL: " . $error . "<br>";
    echo "Posible causa: Bloqueo de firewall o falta de certificados SSL actualizados.";
}
echo "</div>";
?>

</body>
</html>

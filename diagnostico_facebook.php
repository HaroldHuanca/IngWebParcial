<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico Avanzado de Facebook SSL</h1>";

// 1. Verificar archivo de certificado
echo "<h2>1. Verificación de cacert.pem</h2>";
$cert_path = __DIR__ . '/includes/cacert.pem';
echo "Ruta esperada: " . $cert_path . "<br>";

if (file_exists($cert_path)) {
    echo "Archivo: <span style='color:green'>[ENCONTRADO]</span><br>";
    echo "Tamaño: " . filesize($cert_path) . " bytes<br>";
    echo "Permisos: " . substr(sprintf('%o', fileperms($cert_path)), -4) . "<br>";
} else {
    echo "Archivo: <span style='color:red'>[NO ENCONTRADO]</span><br>";
    echo "<strong>SOLUCIÓN:</strong> Asegúrate de haber subido el archivo 'cacert.pem' dentro de la carpeta 'includes'.";
}

// 2. Prueba cURL con Certificado Personalizado
echo "<h2>2. Prueba cURL con cacert.pem</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CAINFO, $cert_path); // Usando el certificado explícito
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);
$error = curl_error($ch);
$errno = curl_errno($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($errno) {
    echo "Resultado: <span style='color:red'>[FALLÓ]</span><br>";
    echo "Error cURL ($errno): $error<br>";
} else {
    echo "Resultado: <span style='color:green'>[EXITOSO]</span><br>";
    echo "Código HTTP: " . $info['http_code'] . "<br>";
}

// 3. Prueba cURL Insegura (Sin verificar SSL)
echo "<h2>3. Prueba cURL Insegura (Solo para descartar)</h2>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar verificación
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);
$error = curl_error($ch);
$errno = curl_errno($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($errno) {
    echo "Resultado: <span style='color:red'>[FALLÓ TAMBIÉN INSEGURO]</span><br>";
    echo "Error cURL ($errno): $error<br>";
    echo "Esto indica un bloqueo de firewall o DNS, no solo de certificados.";
} else {
    echo "Resultado: <span style='color:orange'>[EXITOSO SIN SSL]</span><br>";
    echo "Código HTTP: " . $info['http_code'] . "<br>";
    echo "Esto confirma que el problema es puramente de certificados SSL.";
}

// 4. Información de OpenSSL
echo "<h2>4. Info OpenSSL del Servidor</h2>";
echo "Openssl Version: " . OPENSSL_VERSION_TEXT . "<br>";
$locations = openssl_get_cert_locations();
echo "<pre>";
print_r($locations);
echo "</pre>";
?>

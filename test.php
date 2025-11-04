<?php
// Ejemplo: mensaje generado desde PHP
$mensaje = "Error al guardar los datos"; // o "Registro exitoso"

// Pasamos la variable PHP a JS
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>SweetAlert2 con PHP</title>
  <!-- Importa SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
  // Recibimos el mensaje desde PHP
  const mensaje = "<?php echo $mensaje; ?>";

  // Verificamos el tipo de mensaje
  if (mensaje.toLowerCase().includes("error")) {
    Swal.fire({
      icon: "error",
      title: "¡Ups!",
      text: mensaje,
    });
  } else {
    Swal.fire({
      icon: "success",
      title: "¡Éxito!",
      text: mensaje,
    });
  }
</script>

</body>
</html>

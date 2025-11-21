<?php
session_start();
include 'includes/conexion.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$mensaje = "";
$usuario = null;

// Obtener datos actuales del usuario
$sql_obtener = "SELECT first_name, last_name, username, email, phone, address FROM users WHERE user_id = $user_id";
$resultado_obtener = $con->query($sql_obtener);

if ($resultado_obtener && $resultado_obtener->num_rows > 0) {
    $usuario = $resultado_obtener->fetch_assoc();
} else {
    header('Location: miPerfil.php');
    exit();
}

// Procesar actualización de perfil
if (isset($_POST["btnActualizar"])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Validar que no haya campos obligatorios vacíos
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        // Validar formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "El correo electrónico no es válido.";
        } else {
            // Verificar que el email no esté registrado por otro usuario
            $check = $con->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
            $check->bind_param("si", $email, $user_id);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                $mensaje = "El correo electrónico ya está registrado por otro usuario.";
                $check->close();
            } else {
                $check->close();
                
                // Actualizar datos del usuario
                $sql_actualizar = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
                $actualizar = $con->prepare($sql_actualizar);
                $actualizar->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $user_id);
                
                if ($actualizar->execute()) {
                    $mensaje = "Perfil actualizado exitosamente.";
                    // Actualizar los datos mostrados
                    $usuario['first_name'] = $first_name;
                    $usuario['last_name'] = $last_name;
                    $usuario['email'] = $email;
                    $usuario['phone'] = $phone;
                    $usuario['address'] = $address;
                } else {
                    $mensaje = "Error al actualizar el perfil. Por favor, intenta de nuevo.";
                }
                $actualizar->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="d-flex align-items-center py-5" style="margin-top: 20px;">
        <div class="container">
            <div class="row justify-content-center featured-login">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                    <!-- Tarjeta -->
                    <div class="login-card shadow-lg rounded-4 border-0">
                        <div class="card-body p-4 p-sm-5">

                            <!-- Logo y título -->
                            <div class="text-center mb-4">
                                <img src="img/logo.webp" alt="Logo BookPort" class="mb-3" style="height: 50px;">
                                <h2 class="fw-bold">Editar Perfil</h2>
                                <p class="text-muted">Actualiza tu información personal</p>
                            </div>

                            <!-- Formulario -->
                            <form action="" method="POST">

                                <!-- Nombre -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="first_name" class="form-control" id="floatingFirstName" placeholder="Nombre" value="<?php echo htmlspecialchars($usuario['first_name']); ?>" required>
                                    <label for="floatingFirstName">Nombre</label>
                                </div>

                                <!-- Apellido -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="last_name" class="form-control" id="floatingLastName" placeholder="Apellido" value="<?php echo htmlspecialchars($usuario['last_name']); ?>" required>
                                    <label for="floatingLastName">Apellido</label>
                                </div>

                                <!-- Nombre de usuario (solo lectura) -->
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingUsername" placeholder="Usuario" value="<?php echo htmlspecialchars($usuario['username']); ?>" disabled>
                                    <label for="floatingUsername">Nombre de Usuario</label>
                                </div>

                                <!-- Correo -->
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="nombre@ejemplo.com" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                                    <label for="floatingEmail">Correo Electrónico</label>
                                </div>

                                <!-- Teléfono -->
                                <div class="form-floating mb-3">
                                    <input type="tel" name="phone" class="form-control" id="floatingPhone" placeholder="Número de teléfono" value="<?php echo htmlspecialchars($usuario['phone'] ?? ''); ?>">
                                    <label for="floatingPhone">Teléfono</label>
                                </div>

                                <!-- Dirección -->
                                <div class="form-floating mb-4">
                                    <textarea class="form-control" name="address" id="floatingAddress" placeholder="Dirección" style="height: 100px;"><?php echo htmlspecialchars($usuario['address'] ?? ''); ?></textarea>
                                    <label for="floatingAddress">Dirección</label>
                                </div>

                                <!-- Botones -->
                                <div class="d-grid gap-2">
                                    <button name="btnActualizar" class="btn featured-btn btn-lg" type="submit">Actualizar Perfil</button>
                                    <a href="miPerfil.php" class="btn btn-secondary btn-lg">Cancelar</a>
                                </div>

                            </form>

                            <!-- Enlace de regreso -->
                            <div class="text-center mt-4">
                                <p class="mb-0">
                                    <a href="miPerfil.php" class="fw-bold text-decoration-none" style="color: var(--texto-principal);">
                                        Volver a mi perfil
                                    </a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script>
        // Recibimos el mensaje desde PHP
        const mensaje = "<?php echo htmlspecialchars($mensaje); ?>";

        // Verificamos el tipo de mensaje
        if (mensaje === "") {
            // No hay mensaje para mostrar
            
        }
        else if (mensaje.toLowerCase().includes("error")) {
            Swal.fire({
                icon: "error",
                title: "¡Ups!",
                text: mensaje,
            });
        } else if (mensaje !== "") {
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
            });
        }
    </script>
</body>
</html>

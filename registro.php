<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
include 'includes/conexion.php';
if (isset($_SESSION['usuario'])) {
    header('Location: miPerfil.php');
    exit();
}

$mensaje = "";

if (isset($_POST["btnEnviar"])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $created_at = date('Y-m-d H:i:s');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    // Validaciones básicas
    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
    } else {
        // Usar prepared statements para evitar SQL Injection
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "insert into users (first_name, last_name, username, email, phone, address, password_hash, created_at, last_login, is_active) 
                    values (?, ?, ?, ?, ?, ?, ?, NOW(), NULL, 1)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssss", $first_name, $last_name, $username, $email, $phone, $address, $password_hashed);
        $resultado = $stmt->execute();
        if ($resultado) {
            $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            $mensaje = "Error en el registro. Por favor, intenta de nuevo.";
        }
        $stmt->close();
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
                                <h2 class="fw-bold">Crea tu Cuenta</h2>
                                <p class="text-muted">Regístrate para disfrutar de todos nuestros servicios</p>
                            </div>

                            <!-- Formulario -->
                            <form action="" method="POST">

                                <!-- Nombre -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="first_name" class="form-control" id="floatingFirstName" placeholder="Nombre" required>
                                    <label for="floatingFirstName">Nombre</label>
                                </div>

                                <!-- Apellido -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="last_name" class="form-control" id="floatingLastName" placeholder="Apellido" required>
                                    <label for="floatingLastName">Apellido</label>
                                </div>

                                <!-- Nombre de usuario -->
                                <div class="form-floating mb-3">
                                    <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Usuario" required>
                                    <label for="floatingUsername">Nombre de Usuario</label>
                                </div>

                                <!-- Correo -->
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="nombre@ejemplo.com" required>
                                    <label for="floatingEmail">Correo Electrónico</label>
                                </div>

                                <!-- Teléfono -->
                                <div class="form-floating mb-3">
                                    <input type="tel" name="phone" class="form-control" id="floatingPhone" placeholder="Número de teléfono">
                                    <label for="floatingPhone">Teléfono</label>
                                </div>

                                <!-- Dirección -->
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="address" id="floatingAddress" placeholder="Dirección" style="height: 100px;"></textarea>
                                    <label for="floatingAddress">Dirección</label>
                                </div>

                                <!-- Contraseña -->
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                                    <label for="floatingPassword">Contraseña</label>
                                </div>

                                <!-- Confirmar contraseña -->
                                <div class="form-floating mb-4">
                                    <input type="password" name="confirm_password" class="form-control" id="floatingConfirmPassword" placeholder="Confirmar Contraseña" required>
                                    <label for="floatingConfirmPassword">Confirmar Contraseña</label>
                                </div>

                                <!-- Botón -->
                                <div class="d-grid">
                                    <button name="btnEnviar" class="btn featured-btn btn-lg" type="submit">Crear cuenta</button>
                                </div>

                                <hr class="my-4">

                                <!-- Botones sociales -->
                                <p class="text-center text-muted small">O regístrate con</p>
                                <div class="d-grid gap-2">
                                    <a href="google_auth.php" class="btn btn-secondary">
                                        <i class="bi bi-google me-2"></i> Google
                                    </a>
                                    <a href="facebook_auth.php" class="btn btn-secondary">
                                        <i class="bi bi-facebook me-2"></i> Facebook
                                    </a>
                                </div>
                            </form>

                            <!-- Enlace de login -->
                            <div class="text-center mt-4">
                                <p class="mb-0">¿Ya tienes una cuenta?
                                    <a href="login.php" class="fw-bold text-decoration-none" style="color: var(--texto-principal);">
                                        Inicia sesión aquí
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
        } else {
            Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: mensaje,
        }).then(() => {
            // Solo se ejecuta después de cerrar la alerta
            window.location.href = "login.php";
        });
        }
    </script>
</body>
</html>
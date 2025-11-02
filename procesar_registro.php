<?php
    include 'includes/conexion.php';  

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $first_name = trim($_POST['first_name']);
        $last_name  = trim($_POST['last_name']);
        $username   = trim($_POST['username']);
        $email      = trim($_POST['email']);
        $phone      = trim($_POST['phone']);
        $address    = trim($_POST['address']);
        $password   = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validar que no haya campos obligatorios vacíos
        if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            echo "<script>alert('Por favor, completa todos los campos obligatorios.'); window.history.back();</script>";
            exit;
        }
        
        // Validar que las contraseñas coincidan
        if ($password !== $confirm_password) {
            echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
            exit;
        }

        // Validar formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('El correo electrónico no es válido.'); window.history.back();</script>";
            exit;
        }

        // Validación: usuario o correo ya registrados
        $check = $con->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('El nombre de usuario o correo ya están registrados.'); window.history.back();</script>";
            $check->close();
            exit;
        }
        $check->close();

        // Insertar datos en la tabla
        $insertar = $con->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, phone, address) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertar->bind_param("sssssss", $username, $email, $password, $first_name, $last_name, $phone, $address);

        if ($insertar->execute()) {
            // Redirigir a login con mensaje de éxito
            echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario: " . $con->error . "'); window.history.back();</script>";
        }

        $insertar->close();
        $con->close();
        
    } else {
            // Si alguien intenta acceder directamente
            header("Location: registro.php");
            exit;
    }
?>

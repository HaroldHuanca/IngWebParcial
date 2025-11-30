<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/conexion.php';
if (isset($_SESSION['usuario'])) {
    header('Location: miPerfil.php');
    exit();
}
$mensaje = "";
if (isset($_POST['btnEnviar'])) {
    // Aquí iría la lógica de autenticación (verificar usuario y contraseña)
    // Por simplicidad, asumimos que el login es exitoso si el email es "
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        if (password_verify($password, $fila['password_hash'])) {
        $_SESSION['usuario'] = $email;
        $_SESSION['user_id'] = $fila['user_id'];
        $_SESSION['is_admin'] = $fila['is_admin'];

        //Si es admin lo enviamos al dashboard del admin
        if ($fila['is_admin'] == 1) {
            header('Location:admin/dashboard.php');
            exit();
        }

        //Cargar los datos del carrito        
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT ci.* FROM cart_items ci
        JOIN shopping_carts sc ON ci.cart_id = sc.cart_id
        WHERE sc.user_id = $user_id;";
        $result = $con->query($sql);

        //Si no hay carrito en la base de datos
        if($result->num_rows == 0){

            //Si hay carrito en el navegador entonces llenamos el carrito de la base de datos
            if(isset($_SESSION['cart']) && !empty($_SESSION['cart']) ){
                $cart = $_SESSION['cart'];
                $sqlCheckCart = "SELECT * FROM shopping_carts WHERE user_id = $user_id;";
                $resCheck = $con->query($sqlCheckCart);
                //Si no existe el shopping cart lo creamos
                if($resCheck->num_rows ==0){
                    $sqlInsert = "INSERT INTO shopping_carts (cart_id, user_id, created_at) VALUES ($user_id, $user_id, NOW());";
                    $con->query($sqlInsert);
                }
                //Llenamos los items del shooping cart
                foreach ($cart as $book_id => $item):
                    $quantity = $item['quantity'];
                    $price_at_time = $item['price_at_time'];
                    $sqlInsertItems = "INSERT INTO cart_items (cart_id, book_id, quantity, price_at_time) 
                    VALUES ($user_id, $book_id, $quantity, $price_at_time);";
                    $con->query($sqlInsertItems);
                endforeach;
            }
            //Si existe la variable cart en el navegador la eliminamos
            else{
                unset($_SESSION['cart']);
            }
        }
        //Si hay carrito en la base de datos, llenamos la variable cart
        else{
            $_SESSION['cart'] = array();
            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                $_SESSION['cart'][$book_id]['quantity'] = $row['quantity'];
                $_SESSION['cart'][$book_id]['price_at_time'] = $row['price_at_time'];
            }
        }
        

        // Redirigir al perfil del usuario después del login exitoso
        header('Location: miPerfil.php');
        exit();
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
    <?php include 'includes/header.php'; ?>

    <!-- MAIN CONTENT: Formulario de Login -->
    <main class="d-flex align-items-center py-5 my-5" style="margin-top: 20px;">
        <div class="container">
            <div class="row justify-content-center featured-login">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                    <!-- Tarjeta con sombra. Usamos un fondo blanco de Bootstrap. -->
                    <div class="login-card shadow-lg rounded-4 border-0" >
                        <div class="card-body p-4 p-sm-5">

                            <!-- Logo y Título -->
                            <div class="text-center mb-4">
                                <img src="img/logo.webp" alt="Logo BookPort" class="mb-3" style="height: 50px;">
                                <h2 class="fw-bold">Bienvenido de Vuelta</h2>
                                <p class="text-muted">Ingresa tus credenciales para acceder</p>
                            </div>

                            <!-- Formulario -->
                            <form method="post">
                                <!-- Campo de Email con etiqueta flotante -->
                                <div class="form-floating mb-3">
                                    <input name="email" type="email" class="form-control" id="floatingInput" placeholder="nombre@ejemplo.com" required>
                                    <label for="floatingInput">Correo Electrónico</label>
                                </div>

                                <!-- Campo de Contraseña con etiqueta flotante -->
                                <div class="form-floating mb-3">
                                    <input name="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                                    <label for="floatingPassword">Contraseña</label>
                                </div>

                                <!-- Opciones: Recordar y Olvidé contraseña -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="formCheck">
                                        <label class="form-check-label" for="formCheck">
                                            Recordar sesión
                                        </label>
                                    </div>
                                    <!-- APLICANDO EL COLOR DIRECTAMENTE CON UNA CLASE DE BOOTSTRAP O ESTILO INLINE -->
                                    <a href="#" class="small text-decoration-none" style="color: var(--texto-principal);">¿Olvidaste tu contraseña?</a>
                                </div>

                                <!-- Botón de Login (usando la clase .featured-btn existente) -->
                                <div class="d-grid">
                                    <button class="btn featured-btn btn-lg" type="submit" name="btnEnviar">Ingresar</button>
                                </div>
                                <?php if ($mensaje != ""): ?>
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <?php echo htmlspecialchars($mensaje); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Separador para login social -->
                                <hr class="my-4">
                                <p class="text-center text-muted small">O inicia sesión con</p>

                                <!-- Botones de Login Social (usando clases de Bootstrap existentes) -->
                                <div class="d-grid gap-2">
                                    <a href="google_auth.php" class="btn btn-secondary">
                                        <i class="bi bi-google me-2"></i> Continuar con Google
                                    </a>
                                    <a href="facebook_auth.php" class="btn btn-secondary">
                                        <i class="bi bi-facebook me-2"></i> Continuar con Facebook
                                    </a>
                                </div>

                            </form>

                            <!-- Enlace para Registrarse -->
                            <div class="text-center mt-4">
                                <p class="mb-0">¿No tienes una cuenta? <a href="registro.php" class="fw-bold text-decoration-none" style="color: var(--texto-principal);">Regístrate aquí</a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="es">
<?php include 'includes/head.php'; ?>

<body>
<?php include 'includes/header.php'; ?>

    <!-- MAIN CONTENT: Formulario de Login -->
    <main class="d-flex align-items-center py-5 vh-100" style="padding-top: 100px !important;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    
                    <!-- Tarjeta con sombra. Usamos un fondo blanco de Bootstrap. -->
                    <div class="card shadow-lg rounded-4 border-0" style="background-color: var(--fondo-2);">
                        <div class="card-body p-4 p-sm-5">
                            
                            <!-- Logo y Título -->
                            <div class="text-center mb-4">
                                <img src="img/logo.webp" alt="Logo BookPort" class="mb-3" style="height: 50px;">
                                <h2 class="fw-bold">Bienvenido de Vuelta</h2>
                                <p class="text-muted">Ingresa tus credenciales para acceder</p>
                            </div>

                            <!-- Formulario -->
                            <form>
                                <!-- Campo de Email con etiqueta flotante -->
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" placeholder="nombre@ejemplo.com" required>
                                    <label for="floatingInput">Correo Electrónico</label>
                                </div>
                                
                                <!-- Campo de Contraseña con etiqueta flotante -->
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
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
                                    <button class="btn featured-btn btn-lg" type="submit">Ingresar</button>
                                </div>

                                <!-- Separador para login social -->
                                <hr class="my-4">
                                <p class="text-center text-muted small">O inicia sesión con</p>

                                <!-- Botones de Login Social (usando clases de Bootstrap existentes) -->
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-google me-2"></i> Continuar con Google
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-facebook me-2"></i> Continuar con Facebook
                                    </button>
                                </div>

                            </form>
                            
                            <!-- Enlace para Registrarse -->
                            <div class="text-center mt-4">
                                <p class="mb-0">¿No tienes una cuenta? <a href="registro.html" class="fw-bold text-decoration-none" style="color: var(--texto-principal);">Regístrate aquí</a></p>
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
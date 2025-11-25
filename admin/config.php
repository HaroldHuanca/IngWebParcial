<?php
/**
 * Configuración del Dashboard de Administración
 * Archivo central para personalizar el dashboard
 */

// ============================================
// CONFIGURACIÓN GENERAL
// ============================================

// Nombre de la aplicación
define('APP_NAME', 'BookPort Admin');

// Versión
define('APP_VERSION', '1.0.0');

// Zona horaria
date_default_timezone_set('America/Lima');

// ============================================
// CONFIGURACIÓN DE PAGINACIÓN
// ============================================

// Registros por página
define('REGISTROS_POR_PAGINA', 10);

// ============================================
// CONFIGURACIÓN DE SEGURIDAD
// ============================================

// Tiempo de sesión en minutos
define('SESSION_TIMEOUT', 60);

// Intentos de login fallidos permitidos
define('MAX_LOGIN_ATTEMPTS', 5);

// ============================================
// CONFIGURACIÓN DE MONEDA
// ============================================

// Símbolo de moneda
define('MONEDA_SIMBOLO', 'S/');

// Código de moneda
define('MONEDA_CODIGO', 'PEN');

// Decimales
define('MONEDA_DECIMALES', 2);

// ============================================
// CONFIGURACIÓN DE FORMATOS
// ============================================

// Formato de fecha
define('FORMATO_FECHA', 'd/m/Y');

// Formato de fecha y hora
define('FORMATO_FECHA_HORA', 'd/m/Y H:i:s');

// Formato de hora
define('FORMATO_HORA', 'H:i:s');

// ============================================
// CONFIGURACIÓN DE VALIDACIÓN
// ============================================

// Longitud mínima de contraseña
define('MIN_PASSWORD_LENGTH', 8);

// Longitud máxima de contraseña
define('MAX_PASSWORD_LENGTH', 255);

// Longitud mínima de usuario
define('MIN_USERNAME_LENGTH', 3);

// Longitud máxima de usuario
define('MAX_USERNAME_LENGTH', 50);

// ============================================
// CONFIGURACIÓN DE ARCHIVOS
// ============================================

// Tamaño máximo de archivo en MB
define('MAX_FILE_SIZE', 5);

// Extensiones permitidas
define('EXTENSIONES_PERMITIDAS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);

// Directorio de uploads
define('UPLOAD_DIR', '../uploads/');

// ============================================
// CONFIGURACIÓN DE NOTIFICACIONES
// ============================================

// Email para notificaciones
define('ADMIN_EMAIL', 'admin@bookport.com');

// Habilitar notificaciones por email
define('ENABLE_EMAIL_NOTIFICATIONS', false);

// ============================================
// CONFIGURACIÓN DE LOGS
// ============================================

// Habilitar logging
define('ENABLE_LOGGING', true);

// Directorio de logs
define('LOG_DIR', '../logs/');

// Nivel de log (DEBUG, INFO, WARNING, ERROR)
define('LOG_LEVEL', 'INFO');

// ============================================
// CONFIGURACIÓN DE CACHÉ
// ============================================

// Habilitar caché
define('ENABLE_CACHE', true);

// Tiempo de caché en segundos
define('CACHE_TIME', 3600);

// ============================================
// CONFIGURACIÓN DE ESTADÍSTICAS
// ============================================

// Mostrar estadísticas en dashboard
define('SHOW_STATISTICS', true);

// Actualizar estadísticas cada X segundos
define('STATS_REFRESH_TIME', 30);

// ============================================
// CONFIGURACIÓN DE TEMAS
// ============================================

// Tema por defecto
define('DEFAULT_THEME', 'light');

// Paleta por defecto
define('DEFAULT_PALETTE', 'azul');

// Permitir cambio de tema
define('ALLOW_THEME_CHANGE', true);

// Permitir cambio de paleta
define('ALLOW_PALETTE_CHANGE', true);

// ============================================
// CONFIGURACIÓN DE MÓDULOS
// ============================================

// Módulos habilitados
$MODULOS_HABILITADOS = [
    'usuarios' => true,
    'categorias' => true,
    'autores' => true,
    'libros' => true,
    'pedidos' => true,
    'pagos' => true,
];

// ============================================
// CONFIGURACIÓN DE PERMISOS
// ============================================

// Requerir ser administrador para acceder
define('REQUIRE_ADMIN', false);

// Permisos por rol
$PERMISOS_POR_ROL = [
    'admin' => ['crear', 'leer', 'actualizar', 'eliminar'],
    'editor' => ['crear', 'leer', 'actualizar'],
    'viewer' => ['leer'],
];

// ============================================
// CONFIGURACIÓN DE ESTADOS
// ============================================

// Estados de pedido
$ESTADOS_PEDIDO = [
    'pending' => 'Pendiente',
    'processing' => 'En Proceso',
    'shipped' => 'Enviado',
    'completed' => 'Completado',
    'cancelled' => 'Cancelado',
];

// Estados de pago
$ESTADOS_PAGO = [
    'pending' => 'Pendiente',
    'completed' => 'Completado',
    'failed' => 'Fallido',
    'refunded' => 'Reembolsado',
];

// ============================================
// CONFIGURACIÓN DE FORMATOS DE LIBRO
// ============================================

$FORMATOS_LIBRO = [
    'Tapa Dura' => 'Tapa Dura',
    'Tapa Blanda' => 'Tapa Blanda',
    'eBook' => 'eBook',
    'Audiobook' => 'Audiobook',
];

// ============================================
// CONFIGURACIÓN DE MÉTODOS DE PAGO
// ============================================

$METODOS_PAGO = [
    'tarjeta_credito' => 'Tarjeta de Crédito',
    'tarjeta_debito' => 'Tarjeta de Débito',
    'transferencia' => 'Transferencia Bancaria',
    'paypal' => 'PayPal',
    'efectivo' => 'Efectivo',
];

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================

/**
 * Obtener configuración
 */
function obtenerConfig($clave, $defecto = null) {
    global $MODULOS_HABILITADOS, $PERMISOS_POR_ROL, $ESTADOS_PEDIDO, $ESTADOS_PAGO, $FORMATOS_LIBRO, $METODOS_PAGO;
    
    $config = [
        'modulos' => $MODULOS_HABILITADOS,
        'permisos' => $PERMISOS_POR_ROL,
        'estados_pedido' => $ESTADOS_PEDIDO,
        'estados_pago' => $ESTADOS_PAGO,
        'formatos' => $FORMATOS_LIBRO,
        'metodos_pago' => $METODOS_PAGO,
    ];
    
    return $config[$clave] ?? $defecto;
}

/**
 * Formatear moneda
 */
function formatearMoneda($valor) {
    return MONEDA_SIMBOLO . ' ' . number_format($valor, MONEDA_DECIMALES, '.', ',');
}

/**
 * Formatear fecha
 */
function formatearFecha($fecha) {
    if (empty($fecha)) return '-';
    return date(FORMATO_FECHA, strtotime($fecha));
}

/**
 * Formatear fecha y hora
 */
function formatearFechaHora($fecha) {
    if (empty($fecha)) return '-';
    return date(FORMATO_FECHA_HORA, strtotime($fecha));
}

/**
 * Validar email
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar contraseña
 */
function validarContrasena($password) {
    return strlen($password) >= MIN_PASSWORD_LENGTH && strlen($password) <= MAX_PASSWORD_LENGTH;
}

/**
 * Sanitizar entrada
 */
function sanitizar($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

/**
 * Generar token CSRF
 */
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verificar token CSRF
 */
function verificarTokenCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Registrar log
 */
function registrarLog($mensaje, $nivel = 'INFO') {
    if (!ENABLE_LOGGING) return;
    
    if (!is_dir(LOG_DIR)) {
        mkdir(LOG_DIR, 0755, true);
    }
    
    $archivo = LOG_DIR . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $entrada = "[$timestamp] [$nivel] $mensaje\n";
    
    file_put_contents($archivo, $entrada, FILE_APPEND);
}

?>

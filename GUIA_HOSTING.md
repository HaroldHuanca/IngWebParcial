# Guía de Solución de Problemas en Hosting

Si tu sitio no carga o muestra errores en el hosting (InfinityFree u otros), sigue estos pasos.

## 1. Verificar Credenciales de Base de Datos

El error más común es que el archivo `includes/conexion.php` tiene los datos de tu PC local (`root`, sin contraseña, puerto 3307), pero el hosting usa otros datos.

1.  Ve al panel de control de tu hosting (cPanel/VistaPanel).
2.  Busca la sección **MySQL Databases** (Bases de datos MySQL).
3.  Anota:
    *   **MySQL Hostname** (ej: `sql123.infinityfree.com`).
    *   **MySQL Username** (ej: `if0_345...`).
    *   **MySQL Password** (la contraseña de tu panel o una específica de BD).
    *   **MySQL Database Name** (ej: `if0_345_bookport`).
4.  **NO EDITES** el archivo en tu PC si quieres seguir trabajando localmente.
5.  **EDITA** el archivo `includes/conexion.php` **directamente en el Administrador de Archivos del hosting** con estos datos.

```php
$servername = "sqlxxx.infinityfree.com"; // Tu host real
$username = "if0_xxxxxx"; // Tu usuario real
$password = "tu_contraseña_real"; // Tu contraseña real
$database = "if0_xxxxxx_bookport"; // Tu base de datos real
$port = 3306; // Normalmente es 3306 en hosting, no 3307
```

## 2. Subir la carpeta `vendor`

Como mencioné antes, es **CRÍTICO** que la carpeta `vendor` esté en el hosting.
*   Si no la subiste, el sitio mostrará "Error 500" o pantalla blanca.
*   Asegúrate de haber subido `vendor.zip` y descomprimirlo, o subir todos los archivos.

## 3. Usar el Script de Diagnóstico

He creado un archivo llamado `debug.php`.

1.  Sube el archivo `debug.php` a la carpeta raíz de tu hosting (donde está `index.php`).
2.  Entra a `https://bookport.free.nf/debug.php`.
3.  Este script te dirá en rojo qué está fallando (versión de PHP, conexión a BD, falta de librerías).

## 4. Errores 500 (Pantalla Blanca)

Si ves una pantalla blanca o "Error 500":
1.  Entra al panel de control del hosting.
2.  Busca **Alter PHP Config** o **PHP Configuration**.
3.  Habilita `display_errors` (Ponlo en "On").
4.  Recarga tu página para ver el error real.

## 5. Error ERR_EMPTY_RESPONSE (Problemas de HTTPS)

Si ves `ERR_EMPTY_RESPONSE`, significa que el servidor cortó la conexión. En InfinityFree, esto suele pasar por **problemas de SSL/HTTPS**.

1.  **Prueba con HTTP**: Intenta entrar a `http://bookport.free.nf/debug.php` (sin la "s").
    *   Si carga, el problema es que no tienes activado el certificado SSL.
2.  **Activar SSL**:
    *   Ve al panel de InfinityFree > **Free SSL Certificates**.
    *   Pide un certificado para tu dominio.
    *   Sigue los pasos (añadir registros CNAME) y espera unos minutos/horas.
3.  **Forzar HTTPS**:
    *   Una vez tengas el certificado activo, puedes crear un archivo `.htaccess` en la raíz con esto para forzar HTTPS:
    ```apache
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    ```


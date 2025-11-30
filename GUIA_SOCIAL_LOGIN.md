# Guía de Configuración para Login Social

Para que el inicio de sesión con Google y Facebook funcione, necesitas realizar dos pasos principales:
1. Actualizar la base de datos.
2. Configurar las credenciales de API.

## 1. Actualización de Base de Datos (phpMyAdmin)

Dado que usas XAMPP, la forma más fácil de actualizar la base de datos es:

1.  Abre **phpMyAdmin** (normalmente en `http://localhost/phpmyadmin`).
2.  Selecciona tu base de datos `bookport_db` en el panel izquierdo.
3.  Ve a la pestaña **Importar**.
4.  Selecciona el archivo `db/migration_social_login.sql` que he creado en tu proyecto.
5.  Haz clic en **Continuar** para ejecutarlo.

*Alternativamente, puedes copiar y pegar el contenido de `db/migration_social_login.sql` en la pestaña **SQL** y ejecutarlo.*

## 2. Configuración de Google

1.  Ve a la [Google Cloud Console](https://console.cloud.google.com/).
2.  Crea un nuevo proyecto o selecciona uno existente.
3.  Ve a **APIs & Services** > **Credentials**.
4.  Haz clic en **Create Credentials** > **OAuth client ID**.
5.  Si te lo pide, configura la pantalla de consentimiento (OAuth consent screen).
    *   User Type: External.
    *   Llena los campos obligatorios (nombre de la app, correo, etc.).
6.  En "Application type", selecciona **Web application**.
7.  En **Authorized redirect URIs**, añade la URL de tu entorno local que apunta a `google_auth.php`.
    *   Ejemplo: `http://localhost/IngWebParcial/google_auth.php`
    *   **Nota**: Asegúrate de que coincida exactamente con lo que pongas en `social_config.php`.
8.  Copia el **Client ID** y el **Client Secret**.
9.  Abre `includes/social_config.php` y pega los valores:

```php
'google' => [
    'client_id'     => 'TU_CLIENT_ID_AQUI',
    'client_secret' => 'TU_CLIENT_SECRET_AQUI',
    'redirect_uri'  => 'https://bookport.free.nf/google_auth.php',
],
```

## 2. Configuración de Facebook

1.  Ve a [Meta for Developers](https://developers.facebook.com/).
2.  Crea una nueva App (tipo "Consumer" o "Consumidor").
3.  En el panel de la app, añade el producto **Facebook Login**.
4.  Ve a **Facebook Login** > **Settings**.
5.  En **Valid OAuth Redirect URIs**, añade la URL de tu entorno local que apunta a `facebook_auth.php`.
    *   Ejemplo: `http://localhost/IngWebParcial/facebook_auth.php`
6.  Ve a **Settings** > **Basic** en el menú lateral izquierdo.
7.  Copia el **App ID** y el **App Secret**.
8.  Abre `includes/social_config.php` y pega los valores:

```php
'facebook' => [
    'app_id'        => 'TU_APP_ID_AQUI',
    'app_secret'    => 'TU_APP_SECRET_AQUI',
    'default_graph_version' => 'v2.10',
    'redirect_uri'  => 'https://bookport.free.nf/facebook_auth.php',
],
```

## 3. Requisitos Adicionales de Facebook

Facebook requiere cierta información pública para activar la app. He generado los archivos necesarios para ti.

1.  **Ícono de la App (1024x1024)**:
    *   He generado un ícono y lo he guardado en tu proyecto en: `img/app_icon_1024.png`.
    *   Sube este archivo en la sección "Ícono de la app" en la consola de Facebook.

2.  **URL de Política de Privacidad**:
    *   He creado el archivo `politica_privacidad.php`.
    *   En Facebook, coloca esta URL (ajustada a tu dominio):
        `https://bookport.free.nf/politica_privacidad.php`

3.  **Eliminación de Datos de Usuario**:
    *   He creado el archivo `eliminacion_datos.php`.
    *   En Facebook, selecciona "URL de instrucciones para la eliminación de datos" y coloca:
        `https://bookport.free.nf/eliminacion_datos.php`

4.  **Categoría**:
    *   Selecciona **"Compras" (Shopping)** o **"Educación"**.

5.  **Correo electrónico**:
    *   Asegúrate de que el correo de contacto sea válido.

6.  **Dominios de la App**:
    *   Ve a **Configuración** > **Básica**.
    *   En el campo **Dominios de la app**, escribe tu dominio sin `https://` ni `www`.
    *   Ejemplo: `bookport.free.nf`
    *   Guarda los cambios.

7.  **Agregar Plataforma (CRÍTICO)**:
    *   En la misma página de **Configuración** > **Básica**, baja hasta el final.
    *   Haz clic en el botón **+ Agregar plataforma**.
    *   Selecciona **Sitio web**.
    *   En el campo **URL del sitio**, escribe tu URL completa: `https://bookport.free.nf/`
    *   Guarda los cambios.

## 4. Notas Importantes

*   **Usuarios Nuevos**: Cuando un usuario se registra con Google o Facebook, se le asigna una contraseña aleatoria. Si quieren entrar con correo y contraseña después, tendrán que usar la función "Olvidé mi contraseña" (si está implementada) o no podrán (solo social login).
*   **Avatar**: Se ha añadido soporte para guardar la foto de perfil de la red social en la base de datos.

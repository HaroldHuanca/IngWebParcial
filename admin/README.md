# Dashboard de Administración - BookPort

## Descripción

Panel de administración completo para gestionar la plataforma BookPort. Incluye CRUDs para todas las tablas principales de la base de datos con interfaz moderna y responsiva.

## Acceso

- **URL**: `http://localhost/admin/dashboard.php`
- **Requisito**: Estar registrado como usuario en el sistema
- **Redirección**: Si no estás autenticado, serás redirigido a la página de login

## Módulos Disponibles

### 1. Dashboard
- Vista general con estadísticas principales
- Total de usuarios, libros, pedidos e ingresos
- Acceso rápido a todos los módulos

### 2. Gestión de Usuarios
- **Crear**: Nuevo usuario con contraseña
- **Leer**: Listar todos los usuarios
- **Actualizar**: Editar datos del usuario
- **Eliminar**: Remover usuario del sistema
- Campos: Usuario, Email, Nombre, Apellido, Teléfono, Dirección, Estado, Rol Admin

### 3. Gestión de Categorías
- **Crear**: Nueva categoría de libros
- **Leer**: Listar categorías
- **Actualizar**: Editar categoría
- **Eliminar**: Remover categoría
- Soporte para categorías padre (subcategorías)

### 4. Gestión de Autores
- **Crear**: Nuevo autor
- **Leer**: Listar autores
- **Actualizar**: Editar datos del autor
- **Eliminar**: Remover autor
- Campos: Nombre, Apellido, Biografía, URL de Foto

### 5. Gestión de Libros
- **Crear**: Nuevo libro en el catálogo
- **Leer**: Listar todos los libros
- **Actualizar**: Editar información del libro
- **Eliminar**: Remover libro
- Campos: Título, ISBN, Descripción, Precio, Stock, Editorial, Idioma, Fecha de Publicación, Páginas, Formato, Portada, Destacado

### 6. Gestión de Pedidos
- **Ver**: Detalles completos del pedido
- **Actualizar**: Cambiar estado del pedido y estado de pago
- **Eliminar**: Remover pedido
- Estados: Pendiente, En Proceso, Enviado, Completado, Cancelado
- Muestra artículos del pedido

### 7. Gestión de Pagos
- **Ver**: Detalles de la transacción
- **Actualizar**: Cambiar estado y agregar notas
- **Eliminar**: Remover transacción
- Estados: Pendiente, Completado, Fallido, Reembolsado
- Información de método de pago y referencia

## Características

### Búsqueda y Filtros
- Búsqueda en tiempo real en todas las tablas
- Filtros por estado en pedidos y pagos
- Búsqueda por múltiples campos

### Interfaz
- Diseño responsivo (mobile, tablet, desktop)
- Sidebar de navegación
- Modales para crear/editar
- Confirmación de eliminación
- Alertas de éxito/error

### Seguridad
- Validación de sesión
- Prepared statements para prevenir SQL injection
- Validación de datos en cliente y servidor
- Hashing de contraseñas con bcrypt

## Paleta de Colores Personalizable

El dashboard utiliza variables CSS que pueden ser modificadas fácilmente. Edita el archivo `css/dashboard.css` en la sección `:root`:

```css
:root {
    /* Colores Primarios */
    --color-primary: #0d6efd;           /* Azul principal */
    --color-primary-dark: #0b5ed7;      /* Azul oscuro */
    --color-primary-light: #0dcaf0;     /* Azul claro */
    
    /* Colores Secundarios */
    --color-secondary: #6c757d;         /* Gris */
    --color-success: #198754;           /* Verde */
    --color-danger: #dc3545;            /* Rojo */
    --color-warning: #ffc107;           /* Amarillo */
    --color-info: #0dcaf0;              /* Cian */
    
    /* Colores de Fondo */
    --color-bg-light: #f8f9fa;          /* Fondo claro */
    --color-bg-white: #ffffff;          /* Blanco */
    --color-bg-dark: #212529;           /* Oscuro */
    
    /* Colores de Texto */
    --color-text-dark: #212529;         /* Texto oscuro */
    --color-text-light: #6c757d;        /* Texto claro */
    --color-text-muted: #999999;        /* Texto atenuado */
}
```

### Ejemplos de Paletas Alternativas

#### Paleta Moderna (Púrpura)
```css
--color-primary: #7c3aed;
--color-primary-dark: #6d28d9;
--color-primary-light: #a78bfa;
--color-success: #10b981;
--color-danger: #ef4444;
--color-warning: #f59e0b;
```

#### Paleta Verde
```css
--color-primary: #059669;
--color-primary-dark: #047857;
--color-primary-light: #6ee7b7;
--color-success: #10b981;
--color-danger: #dc2626;
--color-warning: #d97706;
```

#### Paleta Oscura (Dark Mode)
```css
body.theme-dark {
    --color-bg-light: #1e1e1e;
    --color-bg-white: #2d2d2d;
    --color-text-dark: #e0e0e0;
    --color-text-light: #a0a0a0;
    --color-border: #404040;
}
```

## Estructura de Archivos

```
admin/
├── dashboard.php              # Página principal del dashboard
├── usuarios.php               # CRUD de usuarios
├── categorias.php             # CRUD de categorías
├── autores.php                # CRUD de autores
├── libros.php                 # CRUD de libros
├── pedidos.php                # CRUD de pedidos
├── pagos.php                  # CRUD de pagos
├── api/
│   ├── estadisticas.php       # API de estadísticas
│   ├── usuarios_api.php       # API de usuarios
│   ├── categorias_api.php     # API de categorías
│   ├── autores_api.php        # API de autores
│   ├── libros_api.php         # API de libros
│   ├── pedidos_api.php        # API de pedidos
│   └── pagos_api.php          # API de pagos
└── README.md                  # Este archivo

includes/
├── head2.php                  # Meta tags y estilos para admin
├── header2.php                # Navbar del admin
├── footer2.php                # Footer del admin
└── scripts2.php               # Scripts del admin

css/
└── dashboard.css              # Estilos del dashboard con paleta configurable

js/
└── dashboard.js               # Funciones JavaScript del dashboard
```

## Funciones JavaScript Disponibles

### Utilidades
- `mostrarAlerta(mensaje, tipo)` - Mostrar alertas SweetAlert
- `confirmarEliminacion(callback)` - Confirmar antes de eliminar
- `formatearFecha(fecha)` - Formatear fechas a formato local
- `formatearMoneda(valor)` - Formatear números como moneda
- `validarFormulario(formId)` - Validar campos requeridos
- `limpiarFormulario(formId)` - Limpiar formulario
- `cambiarTema(tema)` - Cambiar entre temas light/dark
- `exportarTablaCSV(tableId, nombreArchivo)` - Exportar tabla a CSV
- `buscarEnTabla(inputId, tableId)` - Búsqueda en tiempo real
- `paginar(tableId, filasPorPagina)` - Paginación simple

## Notas Importantes

1. **Autenticación**: El dashboard requiere estar autenticado. La sesión se valida en `header2.php`

2. **Base de Datos**: Asegúrate de que la base de datos `bookport_db` esté creada y con las tablas correctas

3. **Conexión**: Verifica que los datos de conexión en `includes/conexion.php` sean correctos

4. **Permisos**: Todos los usuarios pueden acceder al dashboard. Para restringir solo a administradores, agrega validación en `header2.php`

5. **Seguridad**: En producción, implementa:
   - Validación de permisos por rol
   - Rate limiting en APIs
   - HTTPS obligatorio
   - CSRF tokens

## Personalización

### Cambiar Colores Globalmente
Edita las variables CSS en `css/dashboard.css` línea 1-30

### Agregar Nuevo Módulo
1. Crea `admin/nuevo_modulo.php`
2. Crea `admin/api/nuevo_modulo_api.php`
3. Agrega enlace en el sidebar de todos los archivos
4. Implementa las funciones CRUD

### Modificar Campos de Formularios
Edita los inputs en el modal correspondiente en cada archivo `.php`

## Soporte

Para reportar problemas o sugerencias, contacta al equipo de desarrollo.

---

**Última actualización**: 2025
**Versión**: 1.0

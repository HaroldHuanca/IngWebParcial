# Guía Rápida - Dashboard de Administración

## 🚀 Acceso al Dashboard

### URL
```
http://localhost/admin/dashboard.php
```

### Requisitos
- Estar registrado como usuario en BookPort
- Tener acceso a la base de datos `bookport_db`

### Si no estás autenticado
- Serás redirigido automáticamente a `login.php`
- Regístrate o inicia sesión
- Luego accede nuevamente al dashboard

---

## 📋 Módulos Disponibles

### 1. **Dashboard** 🏠
- **Ruta**: `/admin/dashboard.php`
- **Función**: Panel principal con estadísticas
- **Características**:
  - Total de usuarios, libros, pedidos e ingresos
  - Selector de paleta de colores
  - Acceso rápido a todos los módulos

### 2. **Usuarios** 👥
- **Ruta**: `/admin/usuarios.php`
- **Operaciones**: CRUD completo
- **Campos**: Usuario, Email, Nombre, Apellido, Teléfono, Dirección, Estado, Rol Admin
- **Acciones**: Crear, Editar, Eliminar, Buscar

### 3. **Categorías** 🏷️
- **Ruta**: `/admin/categorias.php`
- **Operaciones**: CRUD completo
- **Campos**: Nombre, Descripción, Categoría Padre
- **Acciones**: Crear, Editar, Eliminar, Buscar

### 4. **Autores** ✍️
- **Ruta**: `/admin/autores.php`
- **Operaciones**: CRUD completo
- **Campos**: Nombre, Apellido, Biografía, URL de Foto
- **Acciones**: Crear, Editar, Eliminar, Buscar

### 5. **Libros** 📚
- **Ruta**: `/admin/libros.php`
- **Operaciones**: CRUD completo
- **Campos**: Título, ISBN, Descripción, Precio, Stock, Editorial, Idioma, Fecha de Publicación, Páginas, Formato, Portada, Destacado
- **Acciones**: Crear, Editar, Eliminar, Buscar

### 6. **Pedidos** 📦
- **Ruta**: `/admin/pedidos.php`
- **Operaciones**: Ver, Editar, Eliminar
- **Estados**: Pendiente, En Proceso, Enviado, Completado, Cancelado
- **Acciones**: Ver detalles, Cambiar estado, Cambiar estado de pago, Eliminar

### 7. **Pagos** 💳
- **Ruta**: `/admin/pagos.php`
- **Operaciones**: Ver, Editar, Eliminar
- **Estados**: Pendiente, Completado, Fallido, Reembolsado
- **Acciones**: Ver detalles, Cambiar estado, Agregar notas, Eliminar

---

## 🎨 Personalización de Colores

### Paletas Disponibles
1. **Azul** (Por defecto)
2. **Púrpura**
3. **Verde**
4. **Rojo**
5. **Naranja**
6. **Rosa**
7. **Cian**
8. **Índigo**
9. **Gris Profesional**
10. **Minimalista**

### Cómo Cambiar la Paleta
1. Ve al Dashboard
2. Busca la sección "Personalizar Paleta de Colores"
3. Haz clic en la paleta que desees
4. Los cambios se guardan automáticamente en tu navegador

### Cambiar Paleta Manualmente
Edita `/css/dashboard.css` en la sección `:root`:

```css
:root {
    --color-primary: #0d6efd;           /* Cambiar este color */
    --color-primary-dark: #0b5ed7;
    --color-primary-light: #0dcaf0;
    /* ... más colores ... */
}
```

### Crear Paleta Personalizada
1. Abre `/css/paletas.css`
2. Copia una paleta existente
3. Cambia el nombre y los colores
4. Agrega la clase en el HTML

---

## 🔍 Funciones Comunes

### Buscar
- Usa el campo de búsqueda en cada módulo
- La búsqueda es en tiempo real
- Busca en múltiples campos

### Crear Nuevo Registro
1. Haz clic en el botón "Nuevo [Elemento]"
2. Completa el formulario
3. Haz clic en "Guardar"
4. Verás una confirmación

### Editar Registro
1. Haz clic en el icono de lápiz (✏️)
2. Modifica los datos
3. Haz clic en "Guardar"
4. Verás una confirmación

### Eliminar Registro
1. Haz clic en el icono de papelera (🗑️)
2. Confirma la eliminación
3. El registro será removido

### Filtrar
- En Pedidos y Pagos hay filtros por estado
- Selecciona el estado deseado
- La tabla se actualiza automáticamente

---

## 📊 Estructura de Archivos

```
admin/
├── dashboard.php              # Panel principal
├── usuarios.php               # Gestión de usuarios
├── categorias.php             # Gestión de categorías
├── autores.php                # Gestión de autores
├── libros.php                 # Gestión de libros
├── pedidos.php                # Gestión de pedidos
├── pagos.php                  # Gestión de pagos
├── api/
│   ├── estadisticas.php       # API de estadísticas
│   ├── usuarios_api.php       # API de usuarios
│   ├── categorias_api.php     # API de categorías
│   ├── autores_api.php        # API de autores
│   ├── libros_api.php         # API de libros
│   ├── pedidos_api.php        # API de pedidos
│   └── pagos_api.php          # API de pagos
└── README.md                  # Documentación completa

includes/
├── head2.php                  # Meta tags y estilos
├── header2.php                # Navbar del admin
├── footer2.php                # Footer del admin
└── scripts2.php               # Scripts del admin

css/
├── dashboard.css              # Estilos principales
└── paletas.css                # Paletas de colores

js/
├── dashboard.js               # Funciones del dashboard
└── paletas.js                 # Gestor de paletas
```

---

## 🔐 Seguridad

### Validaciones
- ✅ Validación de sesión
- ✅ Prepared statements (SQL injection prevention)
- ✅ Validación de datos en cliente y servidor
- ✅ Hashing de contraseñas con bcrypt
- ✅ Confirmación de eliminación

### Recomendaciones
- Usa contraseñas fuertes
- Cierra sesión cuando termines
- No compartas credenciales
- Revisa los cambios regularmente

---

## 🐛 Solución de Problemas

### No puedo acceder al dashboard
- Verifica estar autenticado
- Comprueba la URL: `http://localhost/admin/dashboard.php`
- Revisa la conexión a la base de datos

### Los datos no se guardan
- Verifica la conexión a la base de datos
- Revisa los permisos de la base de datos
- Comprueba que los campos requeridos estén completos

### Los colores no cambian
- Limpia el caché del navegador
- Intenta con otra paleta
- Verifica que `paletas.css` esté cargado

### Errores en la consola
- Abre DevTools (F12)
- Revisa la pestaña "Console"
- Verifica que todos los archivos estén en su lugar

---

## 📞 Soporte

Para reportar problemas o sugerencias:
1. Revisa la documentación en `/admin/README.md`
2. Verifica los logs de la base de datos
3. Contacta al equipo de desarrollo

---

## 📝 Notas Importantes

1. **Backup**: Realiza backups regulares de la base de datos
2. **Actualizaciones**: Mantén el sistema actualizado
3. **Monitoreo**: Revisa los pedidos y pagos regularmente
4. **Limpieza**: Elimina registros obsoletos periódicamente

---

**Última actualización**: 2025
**Versión**: 1.0

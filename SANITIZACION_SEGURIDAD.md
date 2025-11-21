# Sanitización y Seguridad - Proyecto BookPort

## Resumen de Cambios Realizados

Se han sanitizado todos los formularios y outputs del proyecto para prevenir vulnerabilidades de **SQL Injection** y **XSS (Cross-Site Scripting)**.

---

## Archivos Modificados

### 1. **login.php**
- ✅ Convertido a Prepared Statements para prevenir SQL Injection
- ✅ Agregado `htmlspecialchars()` en mensajes de error

### 2. **registro.php**
- ✅ Convertido a Prepared Statements para inserción de usuarios
- ✅ Agregadas validaciones de campos obligatorios
- ✅ Validación de formato de email con `filter_var()`
- ✅ Agregado `htmlspecialchars()` en mensajes

### 3. **editarPerfil.php**
- ✅ Agregado `htmlspecialchars()` en todos los outputs de datos del usuario
- ✅ Agregado `htmlspecialchars()` en mensajes de confirmación

### 4. **Catalogo.php**
- ✅ Mejorada sanitización de filtros de búsqueda
- ✅ Uso de `real_escape_string()` para búsquedas LIKE
- ✅ Validación de categorías con `intval()`
- ✅ Agregado `htmlspecialchars()` en todos los outputs

### 5. **carrito.php**
- ✅ Convertido a Prepared Statements para consultas de libros
- ✅ Validación de `book_id` con `intval()`
- ✅ Agregado `htmlspecialchars()` en títulos, autores e imágenes

### 6. **miPerfil.php**
- ✅ Convertido a Prepared Statements para consultas de usuario
- ✅ Convertido a Prepared Statements para consultas de favoritos y pedidos
- ✅ Agregado `htmlspecialchars()` en todos los datos del usuario
- ✅ Agregado `htmlspecialchars()` en datos de pedidos

### 7. **producto.php**
- ✅ Ya utilizaba Prepared Statements (sin cambios necesarios)
- ✅ Verificado que todos los outputs tengan `htmlspecialchars()`

### 8. **favoritos.php**
- ✅ Convertido a Prepared Statements para DELETE y SELECT
- ✅ Validación de `user_id` y `book_id` con `intval()`
- ✅ Agregado `htmlspecialchars()` en mensajes

### 9. **pedido.php**
- ✅ Convertido a Prepared Statements para todas las operaciones
- ✅ Validación de `user_id` con `intval()`
- ✅ Validación de `total_amount` con `floatval()`
- ✅ Validación de `book_id` y `quantity` con `intval()`

### 10. **detallePedido.php**
- ✅ Convertido a Prepared Statements para consultas de pedidos
- ✅ Convertido a Prepared Statements para consultas de items
- ✅ Convertido a Prepared Statements para consultas de autores
- ✅ Agregado `htmlspecialchars()` en todos los outputs

### 11. **index.php**
- ✅ Agregado `htmlspecialchars()` en mensajes GET

### 12. **alternar.php**
- ✅ Convertido a Prepared Statements para INSERT y DELETE
- ✅ Validación de parámetros con `intval()`
- ✅ Sanitización de redirección

### 13. **eliminar.php**
- ✅ Convertido a Prepared Statements para DELETE
- ✅ Validación de `user_id` y `book_id` con `intval()`

### 14. **añadir.php**
- ✅ Convertido a Prepared Statements para todas las operaciones
- ✅ Validación de `user_id` con `intval()`
- ✅ Validación de `book_id` con `intval()`
- ✅ Validación de `precio` con `floatval()`

---

## Medidas de Seguridad Implementadas

### 1. **Prevención de SQL Injection**
- Uso de **Prepared Statements** con `bind_param()` en todas las consultas
- Validación de tipos de datos con `intval()`, `floatval()`
- Uso de `real_escape_string()` para búsquedas LIKE cuando es necesario

### 2. **Prevención de XSS (Cross-Site Scripting)**
- Uso de `htmlspecialchars()` en todos los outputs de datos del usuario
- Aplicado en:
  - Títulos de libros
  - Nombres de autores
  - Descripciones
  - Datos de usuarios
  - Mensajes de confirmación
  - URLs en atributos HTML

### 3. **Validación de Entrada**
- Validación de emails con `filter_var(FILTER_VALIDATE_EMAIL)`
- Validación de campos obligatorios
- Validación de tipos de datos antes de usar en consultas

### 4. **Sanitización de Redirecciones**
- Validación de parámetros de redirección
- Uso de `trim()` para eliminar espacios

---

## Funciones de Seguridad Utilizadas

| Función | Propósito |
|---------|----------|
| `$con->prepare()` | Preparar consultas SQL seguras |
| `$stmt->bind_param()` | Vincular parámetros de forma segura |
| `htmlspecialchars()` | Escapar caracteres HTML para prevenir XSS |
| `intval()` | Convertir a entero |
| `floatval()` | Convertir a número decimal |
| `filter_var()` | Validar formato de email |
| `trim()` | Eliminar espacios en blanco |

---

## Recomendaciones Adicionales

1. **Hashing de Contraseñas**: Considerar usar `password_hash()` y `password_verify()` en lugar de almacenar contraseñas en texto plano
2. **HTTPS**: Implementar HTTPS en producción
3. **CSRF Tokens**: Agregar tokens CSRF en formularios
4. **Rate Limiting**: Implementar límite de intentos de login
5. **Logging**: Registrar intentos de acceso no autorizados
6. **Auditoría**: Mantener logs de cambios en datos sensibles

---

## Fecha de Sanitización
- **Completado**: 21 de Noviembre de 2025

## Estado
✅ **SANITIZACIÓN COMPLETADA**

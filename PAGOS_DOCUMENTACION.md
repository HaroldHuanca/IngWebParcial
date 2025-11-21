# Sistema de Pagos - BookPort

## Descripción General

Se ha implementado un sistema de pagos completo que permite a los usuarios pagar sus pedidos pendientes utilizando dos métodos:
1. **PayPal** (Sandbox)
2. **Tarjeta Visa** (Sandbox)

---

## Archivos Creados/Modificados

### 1. **pagos.php** (Nuevo)
Página principal del sistema de pagos donde los usuarios pueden:
- Ver todos sus pedidos pendientes de pago
- Seleccionar un pedido para pagar
- Elegir entre PayPal o Tarjeta Visa
- Ingresar datos de pago (simulados en sandbox)
- Procesar el pago

**Características:**
- Validación de entrada (solo números para valores numéricos)
- Prepared Statements para prevenir SQL Injection
- Interfaz intuitiva con Bootstrap
- Uso de la paleta de colores del sitio
- Mensajes de confirmación con SweetAlert2

### 2. **includes/header.php** (Modificado)
Se agregó un botón de "Pagos" en el menú de navegación que:
- Solo aparece cuando el usuario está autenticado
- Muestra un badge con el número de pedidos pendientes
- Solo se visualiza si hay órdenes con `payment_status = 'pending'`
- Usa Prepared Statements para verificar órdenes pendientes

---

## Flujo de Funcionamiento

### 1. Verificación de Órdenes Pendientes
```
Usuario autenticado → Header verifica órdenes pendientes → 
Muestra botón "Pagos" si hay órdenes → Usuario hace clic
```

### 2. Selección de Pedido
```
Usuario selecciona un pedido → Se muestra información del pedido → 
Se habilita la selección de método de pago
```

### 3. Selección de Método de Pago
```
Usuario selecciona PayPal o Tarjeta → Se muestra formulario correspondiente → 
Se habilita botón "Procesar Pago"
```

### 4. Procesamiento del Pago
```
Usuario ingresa datos → Sistema valida (solo números) → 
Actualiza estado de la orden → Redirige a miPerfil.php
```

---

## Métodos de Pago Implementados

### PayPal (Sandbox)
- **Campo requerido:** Billetera Virtual (valor numérico)
- **Validación:** Solo acepta números
- **Simulación:** Cualquier número válido es aceptado
- **Actualización:** `payment_status = 'completed'`, `status = 'processing'`

### Tarjeta Visa (Sandbox)
- **Campos requeridos:**
  - Número de Tarjeta (solo números)
  - Fecha de Vencimiento (formato MM/YYYY)
  - CVV (3-4 dígitos, solo números)
- **Validación:** 
  - Número de tarjeta: solo números
  - CVV: 3-4 dígitos numéricos
- **Simulación:** Cualquier número válido es aceptado
- **Actualización:** `payment_status = 'completed'`, `status = 'processing'`

---

## Cambios en la Base de Datos

### Tabla `orders`
Se utilizan los siguientes campos:
- `order_id`: Identificador único del pedido
- `user_id`: Identificador del usuario
- `total_amount`: Monto total del pedido
- `payment_status`: Estado del pago
  - `'pending'`: Pendiente de pago
  - `'completed'`: Pago completado
- `status`: Estado del pedido
  - `'pending'`: Pendiente
  - `'processing'`: En procesamiento
  - `'completed'`: Completado
  - `'cancelled'`: Cancelado

---

## Medidas de Seguridad

✅ **SQL Injection Prevention:**
- Prepared Statements en todas las consultas
- Validación de `user_id` con `intval()`
- Validación de `order_id` con `intval()`

✅ **XSS Prevention:**
- `htmlspecialchars()` en todos los outputs
- Validación de entrada en formularios

✅ **Validación de Entrada:**
- Solo números para campos numéricos
- Validación de tipos de datos
- Validación de pertenencia de órdenes al usuario

✅ **Autorización:**
- Verificación de que el pedido pertenece al usuario autenticado
- Redirección a login si no está autenticado

---

## Interfaz de Usuario

### Paleta de Colores Utilizada
- **Fondo principal:** `--fondo-3` (#BEE3DB - Mint green)
- **Encabezados:** `--fondo-3` (#BEE3DB)
- **Botones:** `--fondo-4` (#89B0AE - Cambridge blue)
- **Texto:** `--dark-text` (#3E4251)
- **Texto claro:** `--light-text` (#f8f9fa)

### Componentes Bootstrap
- Cards para secciones
- List Group para selección de pedidos
- Form Controls para entrada de datos
- Badges para indicadores
- Buttons para acciones

---

## Validación de Entrada

### PayPal
```javascript
// Solo acepta números
paypal_email: /^[0-9]+$/
```

### Tarjeta Visa
```javascript
numero_tarjeta: /^[0-9]+$/
fecha_vencimiento: formato MM/YYYY (HTML5 month input)
cvv: /^[0-9]{3,4}$/
```

---

## Flujo de Actualización de Estado

Cuando un pago se procesa exitosamente:

```sql
UPDATE orders 
SET payment_status = 'completed', 
    status = 'processing' 
WHERE order_id = ?
```

Esto cambia:
- `payment_status`: de `'pending'` a `'completed'`
- `status`: de `'pending'` a `'processing'`

---

## Mensajes de Confirmación

### Éxito
- PayPal: "¡Pago realizado exitosamente con PayPal! Tu pedido está siendo procesado."
- Tarjeta: "¡Pago realizado exitosamente con Tarjeta Visa! Tu pedido está siendo procesado."

### Error
- Validación: "Por favor ingresa un valor numérico válido para [método]."
- Pedido no encontrado: "Pedido no encontrado o no tienes permiso para pagarlo."
- Error de base de datos: "Error al procesar el pago. Por favor intenta de nuevo."

---

## Notas Importantes

1. **Sandbox Mode:** Todos los pagos son simulados. No se realiza ninguna transacción real.
2. **Valores Numéricos:** Se aceptan cualquier valores numéricos válidos en los campos de pago.
3. **Redirección:** Después de un pago exitoso, el usuario es redirigido a `miPerfil.php` después de 2 segundos.
4. **Badge de Pagos:** El badge en el header se actualiza automáticamente según el número de órdenes pendientes.

---

## Futuras Mejoras

1. Integración real con APIs de PayPal y Stripe
2. Implementación de webhooks para confirmación de pagos
3. Historial de transacciones
4. Recibos de pago por email
5. Múltiples métodos de pago adicionales
6. Reembolsos y cancelaciones

---

## Fecha de Implementación
- **Completado:** 21 de Noviembre de 2025

## Estado
✅ **SISTEMA DE PAGOS IMPLEMENTADO**

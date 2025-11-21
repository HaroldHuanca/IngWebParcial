# Tabla de Transacciones de Pagos

## Descripción

Se ha agregado la tabla `payment_transactions` a la base de datos para registrar todas las transacciones de pago realizadas en el sistema.

---

## Estructura de la Tabla

```sql
CREATE TABLE payment_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_reference VARCHAR(255),
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

---

## Campos de la Tabla

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `transaction_id` | INT (PK, AI) | Identificador único de la transacción |
| `order_id` | INT (FK) | Referencia al pedido asociado |
| `user_id` | INT (FK) | Referencia al usuario que realizó el pago |
| `payment_method` | VARCHAR(50) | Método de pago utilizado (`paypal` o `tarjeta_visa`) |
| `amount` | DECIMAL(10,2) | Monto pagado |
| `status` | VARCHAR(20) | Estado de la transacción (`pending`, `completed`, `failed`) |
| `transaction_date` | TIMESTAMP | Fecha y hora de la transacción |
| `payment_reference` | VARCHAR(255) | Referencia única de la transacción (ej: `PAYPAL_123_1700600000`) |
| `notes` | TEXT | Notas adicionales (ej: últimos dígitos de tarjeta, billetera) |

---

## Índices Creados

Se han creado los siguientes índices para optimizar las búsquedas:

```sql
CREATE INDEX idx_payment_order ON payment_transactions(order_id);
CREATE INDEX idx_payment_user ON payment_transactions(user_id);
CREATE INDEX idx_payment_status ON payment_transactions(status);
```

---

## Datos Registrados por Método de Pago

### PayPal
- **payment_method:** `paypal`
- **payment_reference:** `PAYPAL_{order_id}_{timestamp}`
- **notes:** `Pago simulado en sandbox. Billetera: {valor_ingresado}`

Ejemplo:
```
transaction_id: 1
order_id: 5
user_id: 3
payment_method: paypal
amount: 150.00
status: completed
transaction_date: 2025-11-21 16:30:00
payment_reference: PAYPAL_5_1700600000
notes: Pago simulado en sandbox. Billetera: 123456789
```

### Tarjeta Visa
- **payment_method:** `tarjeta_visa`
- **payment_reference:** `VISA_{order_id}_{timestamp}`
- **notes:** `Pago simulado en sandbox. Tarjeta terminada en: {últimos_4_dígitos}`

Ejemplo:
```
transaction_id: 2
order_id: 6
user_id: 4
payment_method: tarjeta_visa
amount: 200.50
status: completed
transaction_date: 2025-11-21 16:35:00
payment_reference: VISA_6_1700600500
notes: Pago simulado en sandbox. Tarjeta terminada en: 4567
```

---

## Consultas Útiles

### Ver todas las transacciones de un usuario
```sql
SELECT * FROM payment_transactions 
WHERE user_id = ? 
ORDER BY transaction_date DESC;
```

### Ver transacciones de un pedido específico
```sql
SELECT * FROM payment_transactions 
WHERE order_id = ? 
ORDER BY transaction_date DESC;
```

### Ver transacciones completadas
```sql
SELECT * FROM payment_transactions 
WHERE status = 'completed' 
ORDER BY transaction_date DESC;
```

### Monto total pagado por usuario
```sql
SELECT user_id, SUM(amount) as total_pagado 
FROM payment_transactions 
WHERE status = 'completed' 
GROUP BY user_id;
```

### Transacciones por método de pago
```sql
SELECT payment_method, COUNT(*) as total_transacciones, SUM(amount) as monto_total
FROM payment_transactions 
WHERE status = 'completed' 
GROUP BY payment_method;
```

---

## Integración con pagos.php

Cuando se procesa un pago exitosamente en `pagos.php`:

1. Se actualiza la tabla `orders`:
   - `payment_status = 'completed'`
   - `status = 'processing'`

2. Se inserta un registro en `payment_transactions`:
   - Se registra el método de pago
   - Se guarda el monto
   - Se crea una referencia única
   - Se guardan notas con información de seguridad (sin datos sensibles)

---

## Seguridad

✅ **Datos Sensibles Protegidos:**
- No se almacenan números de tarjeta completos
- No se almacenan CVV
- Solo se guardan los últimos 4 dígitos de la tarjeta
- Se usa `htmlspecialchars()` para sanitizar datos

✅ **Integridad de Datos:**
- Claves foráneas garantizan relación con `orders` y `users`
- Timestamps automáticos para auditoría
- Referencias únicas para cada transacción

---

## Pasos para Implementar

1. Ejecutar el script SQL en `database.sql`
2. La tabla se creará automáticamente con los índices
3. Los pagos realizados en `pagos.php` registrarán automáticamente las transacciones

---

## Ejemplo de Ejecución

```bash
mysql -u usuario -p bookport_db < db/database.sql
```

---

## Notas Importantes

- La tabla se crea automáticamente al ejecutar `database.sql`
- Las transacciones se registran automáticamente cuando se procesa un pago
- Los datos se guardan de forma segura sin información sensible
- Se puede auditar el historial completo de pagos por usuario

---

## Fecha de Implementación
- **Completado:** 21 de Noviembre de 2025

## Estado
✅ **TABLA DE TRANSACCIONES IMPLEMENTADA**

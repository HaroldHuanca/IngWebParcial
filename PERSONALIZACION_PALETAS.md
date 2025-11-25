# Guía de Personalización de Paletas de Colores

## 🎨 Paletas Predefinidas

El dashboard incluye 10 paletas de colores listas para usar:

### 1. **Azul** (Por defecto)
```css
--color-primary: #0d6efd;
--color-primary-dark: #0b5ed7;
--color-primary-light: #0dcaf0;
```
Ideal para: Interfaz profesional y moderna

### 2. **Púrpura**
```css
--color-primary: #7c3aed;
--color-primary-dark: #6d28d9;
--color-primary-light: #a78bfa;
```
Ideal para: Diseño creativo y elegante

### 3. **Verde**
```css
--color-primary: #059669;
--color-primary-dark: #047857;
--color-primary-light: #6ee7b7;
```
Ideal para: Sostenibilidad y naturaleza

### 4. **Rojo**
```css
--color-primary: #dc2626;
--color-primary-dark: #b91c1c;
--color-primary-light: #fca5a5;
```
Ideal para: Urgencia y atención

### 5. **Naranja**
```css
--color-primary: #ea580c;
--color-primary-dark: #c2410c;
--color-primary-light: #fed7aa;
```
Ideal para: Energía y dinamismo

### 6. **Rosa**
```css
--color-primary: #ec4899;
--color-primary-dark: #be185d;
--color-primary-light: #fbcfe8;
```
Ideal para: Diseño femenino y moderno

### 7. **Cian**
```css
--color-primary: #0891b2;
--color-primary-dark: #0e7490;
--color-primary-light: #67e8f9;
```
Ideal para: Tecnología y innovación

### 8. **Índigo**
```css
--color-primary: #4f46e5;
--color-primary-dark: #4338ca;
--color-primary-light: #a5b4fc;
```
Ideal para: Profesionalismo y confianza

### 9. **Gris Profesional**
```css
--color-primary: #374151;
--color-primary-dark: #1f2937;
--color-primary-light: #9ca3af;
```
Ideal para: Corporativo y minimalista

### 10. **Minimalista**
```css
--color-primary: #000000;
--color-primary-dark: #1a1a1a;
--color-primary-light: #333333;
```
Ideal para: Diseño limpio y moderno

---

## 🛠️ Cómo Cambiar la Paleta

### Opción 1: Desde el Dashboard (Recomendado)
1. Accede a `http://localhost/admin/dashboard.php`
2. Busca la sección "Personalizar Paleta de Colores"
3. Haz clic en la paleta deseada
4. Los cambios se guardan automáticamente

### Opción 2: Editar CSS Manualmente
1. Abre `/css/dashboard.css`
2. Busca la sección `:root`
3. Modifica los valores de los colores
4. Guarda el archivo

### Opción 3: Usar Paletas Predefinidas
1. Abre `/css/paletas.css`
2. Busca la paleta que deseas
3. Copia la clase (ej: `paleta-purpura`)
4. Agrega la clase al elemento `<html>` o `<body>`

---

## 🎯 Crear Paleta Personalizada

### Paso 1: Definir los Colores
Elige 3 colores principales:
- **Color Primario**: Color principal de la interfaz
- **Color Primario Oscuro**: Versión oscura para hover
- **Color Primario Claro**: Versión clara para fondo

**Ejemplo:**
```
Primario: #FF6B6B (Rojo vibrante)
Oscuro: #C92A2A (Rojo oscuro)
Claro: #FFE3E3 (Rojo claro)
```

### Paso 2: Agregar a paletas.css
Abre `/css/paletas.css` y agrega tu paleta:

```css
/* PALETA PERSONALIZADA: MI PALETA */
:root.paleta-mia {
    --color-primary: #FF6B6B;
    --color-primary-dark: #C92A2A;
    --color-primary-light: #FFE3E3;
    --color-success: #51CF66;
    --color-danger: #FF6B6B;
    --color-warning: #FFD43B;
    --color-info: #74C0FC;
}
```

### Paso 3: Agregar a paletas.js
Abre `/js/paletas.js` y agrega tu paleta al array:

```javascript
paletas: [
    // ... paletas existentes ...
    { 
        id: 'mia', 
        nombre: 'Mi Paleta', 
        colores: ['#FF6B6B', '#C92A2A', '#FFE3E3'] 
    }
]
```

### Paso 4: Usar la Paleta
Ahora puedes cambiar a tu paleta desde el dashboard.

---

## 🌙 Crear Tema Oscuro Personalizado

Agrega a `/css/paletas.css`:

```css
/* TEMA OSCURO PERSONALIZADO */
body.theme-dark.paleta-mia {
    --color-bg-light: #1A1A1A;
    --color-bg-white: #2D2D2D;
    --color-text-dark: #FFE3E3;
    --color-text-light: #C92A2A;
    --color-border: #3D3D3D;
}
```

---

## 📋 Variables CSS Disponibles

```css
/* Colores Primarios */
--color-primary: #0d6efd;           /* Color principal */
--color-primary-dark: #0b5ed7;      /* Versión oscura */
--color-primary-light: #0dcaf0;     /* Versión clara */

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

/* Colores de Bordes */
--color-border: #dee2e6;            /* Borde normal */
--color-border-light: #e9ecef;      /* Borde claro */

/* Sombras */
--shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
--shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
--shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);

/* Espaciado */
--spacing-xs: 0.25rem;
--spacing-sm: 0.5rem;
--spacing-md: 1rem;
--spacing-lg: 1.5rem;
--spacing-xl: 2rem;
--spacing-xxl: 3rem;

/* Transiciones */
--transition-fast: 0.15s ease-in-out;
--transition-base: 0.3s ease-in-out;
--transition-slow: 0.5s ease-in-out;
```

---

## 🎨 Herramientas Útiles

### Generadores de Paletas
- [Coolors.co](https://coolors.co) - Generador de paletas
- [Color Hunt](https://colorhunt.co) - Paletas prediseñadas
- [Adobe Color](https://color.adobe.com) - Herramienta profesional
- [Tailwind Colors](https://tailwindcss.com/docs/customizing-colors) - Colores Tailwind

### Validadores de Contraste
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) - Verificar accesibilidad
- [Contrast Ratio](https://contrast-ratio.com) - Ratio de contraste

---

## 💡 Consejos de Diseño

### 1. **Mantener Contraste**
- Asegúrate de que el texto sea legible
- Usa herramientas de contraste para verificar

### 2. **Consistencia**
- Usa máximo 3 colores primarios
- Mantén la paleta consistente en toda la interfaz

### 3. **Accesibilidad**
- Evita rojo/verde juntos (daltonismo)
- Usa suficiente contraste para usuarios con baja visión

### 4. **Psicología del Color**
- Azul: Confianza, profesionalismo
- Verde: Naturaleza, crecimiento
- Rojo: Urgencia, atención
- Amarillo: Optimismo, energía
- Púrpura: Creatividad, lujo

---

## 🔄 Cambiar Paleta Globalmente

Para cambiar la paleta por defecto en toda la aplicación:

### Opción 1: Editar config.php
```php
// En /admin/config.php
define('DEFAULT_PALETTE', 'purpura'); // Cambiar de 'azul' a 'purpura'
```

### Opción 2: Editar paletas.js
```javascript
// En /js/paletas.js
cargarPaletaGuardada: function() {
    const paletaGuardada = localStorage.getItem('paletaActual') || 'purpura'; // Cambiar aquí
    this.aplicarPaleta(paletaGuardada);
}
```

---

## 🐛 Solución de Problemas

### Los colores no cambian
- Limpia el caché del navegador (Ctrl+Shift+Delete)
- Verifica que los archivos CSS estén cargados
- Abre DevTools (F12) y revisa la consola

### Los colores se ven raros
- Verifica el contraste con herramientas online
- Ajusta los valores RGB/HEX
- Prueba con otra paleta

### No puedo cambiar la paleta
- Verifica que JavaScript esté habilitado
- Revisa que paletas.js esté cargado
- Comprueba la consola para errores

---

## 📝 Ejemplo Completo

### Crear Paleta "Bosque"

**Paso 1: Definir colores**
```
Primario: #2D5016 (Verde oscuro)
Oscuro: #1B3009 (Verde muy oscuro)
Claro: #E8F5E9 (Verde muy claro)
```

**Paso 2: Agregar a paletas.css**
```css
:root.paleta-bosque {
    --color-primary: #2D5016;
    --color-primary-dark: #1B3009;
    --color-primary-light: #E8F5E9;
    --color-success: #558B2F;
    --color-danger: #D32F2F;
    --color-warning: #F57F17;
    --color-info: #00897B;
}

body.theme-dark.paleta-bosque {
    --color-bg-light: #1B3009;
    --color-bg-white: #2D5016;
    --color-text-dark: #E8F5E9;
    --color-text-light: #A5D6A7;
    --color-border: #3D6B1F;
}
```

**Paso 3: Agregar a paletas.js**
```javascript
{ 
    id: 'bosque', 
    nombre: 'Bosque', 
    colores: ['#2D5016', '#1B3009', '#E8F5E9'] 
}
```

**Paso 4: ¡Listo!**
Ahora puedes cambiar a la paleta "Bosque" desde el dashboard.

---

## 📞 Soporte

Para más ayuda:
- Revisa `/admin/README.md`
- Consulta `/GUIA_DASHBOARD.md`
- Abre DevTools (F12) para debugging

---

**Última actualización**: 2025
**Versión**: 1.0

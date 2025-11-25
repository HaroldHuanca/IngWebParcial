// Gestor de Paletas de Colores

const PaletasManager = {
    // Paletas disponibles
    paletas: [
        { id: 'azul', nombre: 'Azul', colores: ['#0d6efd', '#0b5ed7', '#0dcaf0'] },
        { id: 'purpura', nombre: 'Púrpura', colores: ['#7c3aed', '#6d28d9', '#a78bfa'] },
        { id: 'verde', nombre: 'Verde', colores: ['#059669', '#047857', '#6ee7b7'] },
        { id: 'rojo', nombre: 'Rojo', colores: ['#dc2626', '#b91c1c', '#fca5a5'] },
        { id: 'naranja', nombre: 'Naranja', colores: ['#ea580c', '#c2410c', '#fed7aa'] },
        { id: 'rosa', nombre: 'Rosa', colores: ['#ec4899', '#be185d', '#fbcfe8'] },
        { id: 'cian', nombre: 'Cian', colores: ['#0891b2', '#0e7490', '#67e8f9'] },
        { id: 'indigo', nombre: 'Índigo', colores: ['#4f46e5', '#4338ca', '#a5b4fc'] },
        { id: 'gris', nombre: 'Gris', colores: ['#374151', '#1f2937', '#9ca3af'] },
        { id: 'minimalista', nombre: 'Minimalista', colores: ['#000000', '#1a1a1a', '#333333'] }
    ],

    // Inicializar
    init: function() {
        this.cargarPaletaGuardada();
        this.crearSelectorPaletas();
    },

    // Cargar paleta guardada
    cargarPaletaGuardada: function() {
        const paletaGuardada = localStorage.getItem('paletaActual') || 'azul';
        this.aplicarPaleta(paletaGuardada);
    },

    // Aplicar paleta
    aplicarPaleta: function(paletaId) {
        const root = document.documentElement;
        
        // Remover todas las clases de paleta
        this.paletas.forEach(p => {
            root.classList.remove(`paleta-${p.id}`);
        });
        
        // Agregar la nueva paleta
        root.classList.add(`paleta-${paletaId}`);
        
        // Guardar en localStorage
        localStorage.setItem('paletaActual', paletaId);
        
        // Agregar animación
        document.body.classList.add('paleta-cambio');
        setTimeout(() => {
            document.body.classList.remove('paleta-cambio');
        }, 300);
    },

    // Crear selector de paletas
    crearSelectorPaletas: function() {
        const contenedor = document.getElementById('paletaSelector');
        if (!contenedor) return;

        const paletaActual = localStorage.getItem('paletaActual') || 'azul';
        
        this.paletas.forEach(paleta => {
            const boton = document.createElement('button');
            boton.className = `paleta-boton ${paleta.id === paletaActual ? 'activo' : ''}`;
            boton.type = 'button';
            boton.title = `Cambiar a paleta ${paleta.nombre}`;
            
            // Crear vista previa de colores
            const preview = document.createElement('span');
            preview.className = 'paleta-preview';
            
            paleta.colores.forEach(color => {
                const colorDiv = document.createElement('span');
                colorDiv.className = 'paleta-color';
                colorDiv.style.backgroundColor = color;
                preview.appendChild(colorDiv);
            });
            
            boton.appendChild(preview);
            const label = document.createElement('span');
            label.textContent = paleta.nombre;
            label.style.marginLeft = '8px';
            boton.appendChild(label);
            
            boton.addEventListener('click', () => {
                this.aplicarPaleta(paleta.id);
                this.actualizarBotones(paleta.id);
            });
            
            contenedor.appendChild(boton);
        });
    },

    // Actualizar botones activos
    actualizarBotones: function(paletaActual) {
        const botones = document.querySelectorAll('.paleta-boton');
        botones.forEach(boton => {
            boton.classList.remove('activo');
            if (boton.textContent.includes(this.obtenerNombrePaleta(paletaActual))) {
                boton.classList.add('activo');
            }
        });
    },

    // Obtener nombre de paleta
    obtenerNombrePaleta: function(paletaId) {
        const paleta = this.paletas.find(p => p.id === paletaId);
        return paleta ? paleta.nombre : '';
    },

    // Cambiar tema (light/dark)
    cambiarTema: function(tema) {
        if (tema === 'dark') {
            document.body.classList.add('theme-dark');
            localStorage.setItem('tema', 'dark');
        } else {
            document.body.classList.remove('theme-dark');
            localStorage.setItem('tema', 'light');
        }
    },

    // Cargar tema guardado
    cargarTemaGuardado: function() {
        const temaGuardado = localStorage.getItem('tema') || 'light';
        if (temaGuardado === 'dark') {
            this.cambiarTema('dark');
        }
    },

    // Obtener paleta actual
    obtenerPaletaActual: function() {
        return localStorage.getItem('paletaActual') || 'azul';
    },

    // Obtener tema actual
    obtenerTemaActual: function() {
        return localStorage.getItem('tema') || 'light';
    },

    // Resetear a valores por defecto
    resetear: function() {
        localStorage.removeItem('paletaActual');
        localStorage.removeItem('tema');
        this.init();
        this.cargarTemaGuardado();
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    PaletasManager.init();
    PaletasManager.cargarTemaGuardado();
});

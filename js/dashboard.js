// Funciones de utilidad para el dashboard

// Mostrar alerta de éxito
function mostrarAlerta(mensaje, tipo = 'success') {
    Swal.fire({
        icon: tipo,
        title: tipo === 'success' ? 'Éxito' : tipo === 'error' ? 'Error' : 'Información',
        text: mensaje,
        confirmButtonColor: '#0d6efd',
        timer: 3000
    });
}

// Confirmar eliminación
function confirmarEliminacion(callback) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Formatear fecha
function formatearFecha(fecha) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(fecha).toLocaleDateString('es-ES', options);
}

// Formatear moneda
function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
    }).format(valor);
}

// Validar formulario
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('[required]');
    let valido = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valido = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return valido;
}

// Limpiar formulario
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }
}

// Cambiar tema
function cambiarTema(tema) {
    if (tema === 'dark') {
        document.body.classList.add('theme-dark');
        localStorage.setItem('tema', 'dark');
    } else {
        document.body.classList.remove('theme-dark');
        localStorage.setItem('tema', 'light');
    }
}

// Cargar tema guardado
document.addEventListener('DOMContentLoaded', function() {
    const temaSaved = localStorage.getItem('tema');
    if (temaSaved === 'dark') {
        cambiarTema('dark');
    }
});

// Exportar tabla a CSV
function exportarTablaCSV(tableId, nombreArchivo = 'datos.csv') {
    const tabla = document.getElementById(tableId);
    if (!tabla) return;
    
    let csv = [];
    const filas = tabla.querySelectorAll('tr');
    
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td, th');
        const fila_csv = [];
        celdas.forEach(celda => {
            fila_csv.push('"' + celda.textContent.trim() + '"');
        });
        csv.push(fila_csv.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = nombreArchivo;
    a.click();
    window.URL.revokeObjectURL(url);
}

// Buscar en tabla
function buscarEnTabla(inputId, tableId) {
    const input = document.getElementById(inputId);
    const tabla = document.getElementById(tableId);
    
    if (!input || !tabla) return;
    
    input.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        const filas = tabla.querySelectorAll('tbody tr');
        
        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });
}

// Paginación simple
function paginar(tableId, filasPorPagina = 10) {
    const tabla = document.getElementById(tableId);
    if (!tabla) return;
    
    const filas = tabla.querySelectorAll('tbody tr');
    const totalPaginas = Math.ceil(filas.length / filasPorPagina);
    let paginaActual = 1;
    
    function mostrarPagina(pagina) {
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        
        filas.forEach((fila, index) => {
            fila.style.display = (index >= inicio && index < fin) ? '' : 'none';
        });
    }
    
    mostrarPagina(1);
    return { mostrarPagina, totalPaginas };
}

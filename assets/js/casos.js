'use strict';

// =============================================
// UTILIDADES
// =============================================
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function debounce(fn, ms) {
    let t;
    return function (...args) {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), ms);
    };
}

// =============================================
// FILTROS — AJAX
// =============================================
async function cargarCasos() {
    const tbody = document.getElementById('tabla-body');
    if(!tbody) return;

    // --- MEJORA DE SEGURIDAD ---
    // Usamos una función auxiliar para leer valores sin que el JS "explote"
    const getVal = (id) => {
        const el = document.getElementById(id);
        return el ? el.value : ''; 
    };

    const params = new URLSearchParams({
        controller: 'caso',
        action: 'listar',
        buscar: getVal('f-buscar'),
        clasificacion: getVal('f-clasificacion'),
        estado_evaluacion: getVal('f-evaluacion'),
        estado_proceso: getVal('f-proceso'),
    });
    // ---------------------------

    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
    </td></tr>`;

    try {
        const res = await fetch(window.BASE_URL + 'public/index.php?' + params);
        const data = await res.json();
        
        renderTabla(data.casos || []);
        renderStats(data.stats || {});
    } catch (error) {
        console.error("Error en el fetch:", error);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5 text-danger">Error al obtener datos</td></tr>`;
    }
}

function renderStats(s) {
    // Estas IDs deben existir en tu bandeja.php (id="st-total", etc)
    const set = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val || 0;
    };
    set('st-total', s.total);
    set('st-pendientes', s.pendientes);
    set('st-aprobados', s.aprobados);
    set('st-observados', s.observados);
    set('st-rechazados', s.rechazados);
    set('st-publicados', s.publicados);
}

function renderTabla(casos) {
    const tbody = document.getElementById('tabla-body');
    const contEl = document.getElementById('total-count');
    if (contEl) contEl.textContent = `${casos.length} caso(s)`;

    if (!casos.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-5 text-muted">No se encontraron resultados</td></tr>`;
        return;
    }

    tbody.innerHTML = casos.map(c => {
        // Icono de publicación
        const pubIcon = c.publicado == 1 ? 
            '<i class="bi bi-check-circle-fill text-success" title="Publicado"></i>' : 
            '<i class="bi bi-dash-circle text-muted opacity-50" title="No publicado"></i>';

        // Clases de badge según estado (opcional para colores)
        const badgeColor = {
            'pendiente': 'bg-warning text-dark',
            'aprobado': 'bg-success',
            'rechazado': 'bg-danger',
            'observado': 'bg-info text-dark'
        }[c.estado_evaluacion] || 'bg-secondary';

        return `<tr>
            <td class="small fw-bold text-muted">#${c.id}</td>
            <td>
                <div class="fw-bold text-dark">${esc(c.titulo_caso)}</div>
                <div class="small text-muted">${esc(c.nombre_ong)}</div>
            </td>
            <td><span class="badge rounded-pill bg-light text-dark border">${esc(c.clasificacion)}</span></td>
            <td><span class="badge rounded-pill ${badgeColor}">${esc(c.estado_evaluacion)}</span></td>
            <td class="text-center">${pubIcon}</td>
            <td><span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary">${esc(c.estado_proceso)}</span></td>
            <td class="small text-muted">${(c.fecha_registro || '').substring(0, 10)}</td>
            <td class="text-end">
                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" onclick="abrirDetalle(${c.id})">
                    <i class="bi bi-eye me-1"></i>Ver
                </button>
            </td>
        </tr>`;
    }).join('');
}

function limpiarFiltros() {
    document.getElementById('f-buscar').value = '';
    document.getElementById('f-clasificacion').value = '';
    document.getElementById('f-evaluacion').value = '';
    document.getElementById('f-proceso').value = '';
    cargarCasos();
}

let modalInstancia = null;

async function abrirDetalle(id) {
    const modalEl = document.getElementById('modalDetalle');
    if (!modalInstancia) modalInstancia = new bootstrap.Modal(modalEl);
    
    // Limpiar campos antes de cargar
    document.getElementById('det-titulo').textContent = "Cargando...";
    document.getElementById('det-descripcion').textContent = "";

    try {
        const res = await fetch(`${window.BASE_URL}public/index.php?controller=caso&action=detalle&id=${id}`);
        const data = await res.json();
        
        if (data.error) throw new Error(data.error);

        const c = data.caso;
        // Llenar Modal
        document.getElementById('det-id').textContent = `#${c.id}`;
        document.getElementById('det-titulo').textContent = c.titulo_caso;
        document.getElementById('det-ong').textContent = c.nombre_ong;
        document.getElementById('det-descripcion').textContent = c.descripcion;
        document.getElementById('det-beneficiario').textContent = c.nombre_beneficiario;
        document.getElementById('det-monto').textContent = `S/ ${parseFloat(c.monto_requerido).toLocaleString()}`;
        
        // Seleccionar estado actual en el select
        document.getElementById('select-eval').value = c.estado_evaluacion;
        document.getElementById('comentario-eval').value = c.comentario_evaluacion || "";

        modalInstancia.show();
    } catch (error) {
        console.error("Error detalle:", error);
        alert("No se pudo cargar el detalle del caso.");
    }
}

async function guardarCambios() {
    const id = document.getElementById('det-id').textContent.replace('#', '');
    const estado = document.getElementById('select-eval').value;
    const comentario = document.getElementById('comentario-eval').value;
    
    // Si tienes un checkbox para publicar, podrías capturarlo así:
    // const publicado = document.getElementById('check-publicar').checked;

    try {
        // 1. Guardar Estado de Evaluación
        const resEval = await fetch(`${window.BASE_URL}public/index.php?controller=evaluacion&action=cambiarEstado`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, estado, comentario })
        });

        const text = await resEval.text();      

        const dataEval = JSON.parse(text);

       if (dataEval.ok) {
            modalInstancia.hide();

            setTimeout(() => {
                mostrarMensaje('Estado actualizado correctamente', 'success');
            }, 300);

            cargarCasos();
        } else {
            mostrarMensaje('danger', dataEval.error || 'Error al actualizar');
        }
    } catch (error) {
        console.error("Error al guardar:", error);
        alert("Fallo la conexión con el controlador de evaluación");
    }
}

function mostrarMensaje(msg, tipo = 'success') {
    const container = document.getElementById('alert-container');

    const alert = document.createElement('div');
    alert.className = `alert alert-${tipo} shadow`;
    alert.style.minWidth = '280px';
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(20px)';
    alert.style.transition = 'all 0.4s ease';
    alert.innerHTML = msg;

    container.innerHTML = '';
    container.appendChild(alert);

    // animación entrada
    setTimeout(() => {
        alert.style.opacity = '1';
        alert.style.transform = 'translateY(0)';
    }, 50);

    // ajustar posición
    setTimeout(ajustarAlerta, 100);

    // salida
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(20px)';

        setTimeout(() => {
            alert.remove();
        }, 400);
    }, 3000);
}

function ajustarAlerta() {
    const footer = document.querySelector('footer');
    const alert = document.getElementById('alert-container');

    if (footer && alert) {
        const rect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        // distancia desde el bottom del viewport hasta el inicio del footer
        const espacio = windowHeight - rect.top;

        alert.style.bottom = (espacio + 20) + 'px';
    }
}

window.addEventListener('load', ajustarAlerta);
window.addEventListener('resize', ajustarAlerta);

// Función extra para el switch de publicación rápida desde la tabla
async function togglePublicar(id, estadoActual) {
    const nuevoEstado = !estadoActual;
    try {
        const res = await fetch(`${window.BASE_URL}public/index.php?controller=evaluacion&action=publicar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, publicar: nuevoEstado })
        });
        const data = await res.json();
        if (data.ok) {
            cargarCasos();
        } else {
            alert(data.error);
        }
    } catch (error) {
        alert("Error al cambiar estado de publicación");
    }
}
// =============================================
// INIT
// =============================================
document.addEventListener('DOMContentLoaded', () => {
    // 1. Listeners para filtros automáticos
    const inputs = ['f-buscar', 'f-clasificacion', 'f-evaluacion', 'f-proceso'];
    inputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            if (el.tagName === 'INPUT') {
                el.addEventListener('input', debounce(cargarCasos, 400));
            } else {
                el.addEventListener('change', cargarCasos);
            }
        }
    });
    
    // 2. Carga inicial al entrar a la página
    cargarCasos();
});
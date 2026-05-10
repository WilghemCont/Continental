'use strict';
//console.log("BANDEJA JS CARGADO");
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

function limpiarFiltros() {
    const ids = ['f-buscar', 'f-clasificacion', 'f-evaluacion', 'f-proceso'];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        if (el.tagName === 'INPUT') {
            el.value = '';
        } else {
            el.selectedIndex = 0;
        }
    });

    cargarCasos(); // recarga tabla sin filtros
}

// =============================================
// FILTROS — AJAX (BANDEJA)
// =============================================
async function cargarCasos() {
    const tbody = document.getElementById('tabla-body');
    if(!tbody) return;

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
        const pubIcon = c.publicado == 1 ? 
            '<i class="bi bi-check-circle-fill text-success" title="Publicado"></i>' : 
            '<i class="bi bi-dash-circle text-muted opacity-50" title="No publicado"></i>';

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
                <a href="${window.BASE_URL}public/index.php?controller=caso&action=ver&id=${c.id}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-eye me-1"></i>Ver
                </a>
            </td>
        </tr>`;
    }).join('');
}

// =============================================
// MODAL DETALLE Y EVALUACIÓN
// =============================================
let modalInstancia = null;

async function abrirDetalle(id) {
    const modalEl = document.getElementById('modalDetalle');
    if (!modalInstancia) modalInstancia = new bootstrap.Modal(modalEl);
    
    try {
        const res = await fetch(`${window.BASE_URL}public/index.php?controller=caso&action=detalle&id=${id}`);
        const data = await res.json();
        const c = data.caso;

        document.getElementById('det-id').textContent = `#${c.id}`;
        document.getElementById('det-titulo').textContent = c.titulo_caso;
        document.getElementById('det-ong').textContent = c.nombre_ong;
        document.getElementById('det-descripcion').textContent = c.descripcion;
        document.getElementById('det-beneficiario').textContent = c.nombre_beneficiario;
        document.getElementById('det-monto').textContent = `S/ ${parseFloat(c.monto_requerido).toLocaleString()}`;
        document.getElementById('select-eval').value = c.estado_evaluacion;
        document.getElementById('comentario-eval').value = c.comentario_evaluacion || "";

        modalInstancia.show();
    } catch (error) {
        console.error("Error detalle:", error);
        alert("No se pudo cargar el detalle del caso.");
    }
}

// =============================================
// REGISTRO DE NUEVO CASO (NUEVO)
// =============================================
async function registrarCaso(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    
    // Validar archivos
    const foto = form.querySelector('input[name="foto_beneficiario"]');
    if (foto.files.length > 0 && foto.files[0].size > 2000000) {
        alert("La foto no debe pesar más de 2MB");
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';

    const formData = new FormData(form);
    const url = `${window.BASE_URL}public/index.php?controller=caso&action=guardar`;

    try {
        const res = await fetch(url, {
            method: 'POST',
            body: formData
        });

        // Intentamos parsear como JSON. Si el controlador redirige, esto podría fallar.
        const text = await res.text();
        try {
            const data = JSON.parse(text);
            if (data.ok || data.status === 'success') {
                mostrarMensaje('¡Caso registrado con éxito!', 'success');
                setTimeout(() => window.location.href = 'home.php', 1500);
            } else {
                mostrarMensaje('Error: ' + (data.error || 'No se pudo registrar'), 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Registrar Caso';
            }
        } catch (e) {
            // Si no es JSON pero fue exitoso (redirección manual)
            window.location.href = 'bandeja.php?registro=ok';
        }
    } catch (error) {
        console.error("Error registro:", error);
        mostrarMensaje('Fallo en la conexión con el servidor', 'danger');
        btn.disabled = false;
    }
}

// =============================================
// MENSAJES Y UI
// =============================================
function mostrarMensaje(msg, tipo = 'success') {
    // Buscamos el contenedor que definimos en registro_caso.php
    let container = document.getElementById('alertaCaso'); 
    
    if (container) {
        container.className = `alert alert-${tipo} rounded-4 d-block fade-up`;
        container.innerHTML = `<i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i> ${msg}`;
        
        // Hacer scroll hasta el mensaje para que el usuario lo vea
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        // Si no existe el contenedor, usamos un alert clásico como respaldo
        alert(msg);
    }
}

// =============================================
// INIT
// =============================================
document.addEventListener('DOMContentLoaded', () => {
    // 1. Listeners para filtros (Bandeja)
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

    // 2. Listener para Formulario de Registro (Si existe en la página)
    const formRegistro = document.getElementById('formRegistroCaso');
    if (formRegistro) {
        formRegistro.addEventListener('submit', registrarCaso);
    }
    
    // 3. Carga inicial de tabla
    if (document.getElementById('tabla-body')) {
        cargarCasos();
    }
});
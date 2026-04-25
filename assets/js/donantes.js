// assets/js/donantes.js
document.addEventListener('DOMContentLoaded', () => {
    listarDonantes();
});

async function listarDonantes() {
    const tbody = document.getElementById('tabla-donantes');
    try {
        const res = await fetch(`${window.BASE_URL}public/index.php?controller=donante&action=listar`);
        const data = await res.json();
        
        tbody.innerHTML = data.map(d => `
            <tr>
                <td>
                    <div class="fw-bold text-dark">${d.nombres}</div>
                </td>
                <td><i class="bi bi-envelope me-1"></i>${d.correo}</td>
                 <td><i class="bi bi-calendar-date me-1"></i>${d.fechacreacion}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="abrirEditar(${d.idusuario})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminarDonante(${d.idusuario})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    } catch (e) { console.error(e); }
}

async function abrirEditar(id) {
    const res = await fetch(`${window.BASE_URL}public/index.php?controller=donante&action=detalle&id=${id}`);
    const d = await res.json();
    
    document.getElementById('edit-id').value = d.idusuario;
    document.getElementById('edit-nombre').value = d.nombres;
    document.getElementById('edit-email').value = d.correo;
    
    new bootstrap.Modal(document.getElementById('modalEditarDonante')).show();
}

async function guardarDonante(e) {
    e.preventDefault();
    const data = {
        nombres: document.getElementById('reg-nombre').value,
        correo: document.getElementById('reg-email').value
    };

    const res = await fetch(`${window.BASE_URL}public/index.php?controller=donante&action=guardar`, {
        method: 'POST',
        body: JSON.stringify(data)
    });
    
    if ((await res.json()).ok) {
        alert("Guardado");
        listarDonantes();
    }
}
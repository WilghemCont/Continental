if (typeof idAEliminar === 'undefined') {
    var idAEliminar = null;
}

function eliminarIngreso(id) {
    idAEliminar = id;
    const modalEl = document.getElementById('confirmDeleteModal');
    if (modalEl) {
        const modalConfirm = new bootstrap.Modal(modalEl);
        modalConfirm.show();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Captura de elementos
    const tipoSelect = document.getElementById('tipoSelect');
    const seccionPatrocinio = document.getElementById('seccionPatrocinio');
    const formIngreso = document.getElementById('formIngreso');
    const btnConfirmar = document.getElementById('btnConfirmarEliminar');

    // Validación para el botón de eliminar
    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', () => {
            if (idAEliminar) {
                fetch(`../public/eliminar_ingreso.php?id=${idAEliminar}`)
                    .then(res => res.text())
                    .then(data => {
                        if (data.trim() === "OK") {
                            location.reload();
                        } else {
                            alert("Error: " + data);
                        }
                    })
                    .catch(err => console.error("Error:", err));
            }
        });
    }

    // 1. Mostrar/Ocultar campos según el tipo (SOLO si existen)
    // El error ingresos.js:39 pasaba aquí porque tipoSelect era null
    if (tipoSelect && seccionPatrocinio) {
        tipoSelect.addEventListener('change', () => {
            seccionPatrocinio.style.display = (tipoSelect.value === 'Patrocinio') ? 'flex' : 'none';
        });
    }

    // 2. Envío Asíncrono (Fetch)
    if (formIngreso) {
        formIngreso.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../public/guardar_ingreso.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "OK") {
                    location.reload();
                } else {
                    alert("Error: " + data);
                }
            });
        });
    }   
});
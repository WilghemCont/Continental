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
    const tipoSelect = document.getElementById('tipoSelect');
    const seccionPatrocinio = document.getElementById('seccionPatrocinio');
    const formIngreso = document.getElementById('formIngreso');
    const btnConfirmar = document.getElementById('btnConfirmarEliminar');

    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', () => {
            if (idAEliminar) {
                // Ejecutamos el fetch real
                fetch(`../public/eliminar_ingreso.php?id=${idAEliminar}`)
                    .then(res => res.text())
                    .then(data => {
                        if (data.trim() === "OK") {
                            location.reload(); // Recarga para actualizar tabla y tarjetas
                        } else {
                            alert("Error: " + data);
                        }
                    })
                    .catch(err => console.error("Error:", err));
            }
        });
    }

    // 1. Mostrar/Ocultar campos según el tipo
    tipoSelect.addEventListener('change', () => {
        seccionPatrocinio.style.display = (tipoSelect.value === 'Patrocinio') ? 'flex' : 'none';
    });

    // 2. Envío Asíncrono (Fetch)
    if(formIngreso) {
        formIngreso.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../public/guardar_ingreso.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() === "OK") {
                    location.reload(); // Recargamos para ver el nuevo ingreso en la tabla
                } else {
                    alert("Error: " + data);
                }
            });
        });
    }   
});
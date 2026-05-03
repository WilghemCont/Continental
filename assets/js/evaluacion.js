document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEvaluacion');
    if (!form) return;

    // 1. Mostrar/Ocultar comentarios dinámicamente
    form.addEventListener('change', (e) => {
        if (e.target.classList.contains('check-opcion')) {
            const itemId = e.target.dataset.item;
            const box = document.getElementById('comentario-box-' + itemId);
            const txt = box.querySelector('textarea');

            if (e.target.value === 'NO') {
                box.classList.remove('d-none');
                box.classList.add('fade-up');
                txt.required = true;
                txt.focus();
            } else {
                box.classList.add('d-none');
                txt.required = false;
                txt.value = '';
            }
        }
    });

    // 2. Función para mostrar alertas en el contenedor HTML
    function mostrarMensaje(msg, tipo = 'success') {
        let container = document.getElementById('alertaCaso'); 
        if (container) {
            container.className = `alert alert-${tipo} rounded-4 d-block fade-up shadow-sm mb-4`;
            container.innerHTML = `<i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i> ${msg}`;
            container.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            alert(msg);
        }
    } 

    // 3. Envío del formulario vía AJAX (Fetch)
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // DETENER RECARGA DE PÁGINA

        // Validación manual de que todo esté marcado
        const radios = form.querySelectorAll('.check-opcion');
        const nombres = new Set();
        radios.forEach(r => nombres.add(r.name));

        let todosRespondidos = true;
        nombres.forEach(nombre => {
            if (!form.querySelector(`input[name="${nombre}"]:checked`)) {
                todosRespondidos = false;
            }
        });

        if (!todosRespondidos) {
            mostrarMensaje('Por favor, responda todos los ítems del checklist.', 'danger');
            return;
        }

        // Preparar envío
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.ok) {
                mostrarMensaje(data.mensaje || '¡Evaluación guardada con éxito!', 'success');
                // Redirigir después de 2 segundos
                setTimeout(() => {
                    window.location.href = window.BASE_URL + "view/bandeja.php";
                }, 2000);
            } else {
                throw new Exception(data.error || 'Error desconocido');
            }
        } catch (error) {
            mostrarMensaje('Error: ' + error.message, 'danger');
            btn.disabled = false;
            btn.innerHTML = 'FINALIZAR EVALUACIÓN <i class="bi bi-shield-fill-check ms-2"></i>';
        }
    });
});
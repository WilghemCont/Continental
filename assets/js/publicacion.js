document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formPublicacion');
    if (!form) return;

    // --- 1. VISTA PREVIA EN TIEMPO REAL CON VALIDACIÓN ---
    const inputs = {
        titulo: document.getElementById('titulo'),
        descripcion: document.getElementById('descripcion'),
        imagen: document.getElementById('imagen')
    };

    const previews = {
        titulo: document.getElementById('previewTitulo'),
        descripcion: document.getElementById('previewDesc'),
        imagen: document.getElementById('previewImg')
    };

    // Validar y asignar eventos solo si los elementos existen
    if (inputs.titulo && previews.titulo) {
        inputs.titulo.addEventListener('input', e => previews.titulo.innerText = e.target.value);
    }

    if (inputs.descripcion && previews.descripcion) {
        inputs.descripcion.addEventListener('input', e => previews.descripcion.innerText = e.target.value);
    }
    
    // Aquí es donde ocurría el error (Línea 21)
    if (inputs.imagen && previews.imagen) {
        inputs.imagen.addEventListener('input', e => {
            let valor = e.target.value.trim();
            if (valor) {
                // Prevenir duplicación de BASE_URL si el valor ya es una URL completa
                const src = valor.startsWith('http') ? valor : window.BASE_URL + valor;
                previews.imagen.src = src;
            }
        });
    }

    // --- 2. ENVÍO VÍA FETCH (AJAX) ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Determinar qué botón se presionó (borrador o publicar)
        const accion = e.submitter ? e.submitter.value : 'borrador';
        const btn = e.submitter;
        
        btn.disabled = true;
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

        try {
            const formData = new FormData(form);
            formData.append('accion_tipo', accion); // Enviamos el tipo de acción al controlador

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.ok) {
                mostrarAlerta(data.mensaje || '¡Operación exitosa!', 'success');
                setTimeout(() => {
                    window.location.href = window.BASE_URL + "view/bandeja.php";
                }, 2000);
            } else {
                throw new Error(data.error || 'Error al procesar la publicación');
            }
        } catch (error) {
            mostrarAlerta(error.message, 'danger');
            btn.disabled = false;
            btn.innerHTML = textoOriginal;
        }
    });

    function mostrarAlerta(msg, tipo) {
        const container = document.getElementById('alertaCaso');
        container.className = `alert alert-${tipo} rounded-4 d-block fade-up shadow-sm mb-4`;
        container.innerHTML = `<i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i> ${msg}`;
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
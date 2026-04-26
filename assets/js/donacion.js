// donacion.js
document.addEventListener('DOMContentLoaded', () => {
    // Inicializamos Mercado Pago (Usa tu Public Key)
    const mp = new MercadoPago('APP_USR-c8741a70-28f6-400d-89fd-09c5c2d99ddd', {
        locale: 'es-PE'
    });

    const form = document.querySelector('form');
    const btn = document.querySelector('button[onclick="iniciarPago()"]');

    window.iniciarPago = async function () {
        if (!form || !form.reportValidity()) return;

        // Feedback visual
        const originalText = btn.innerHTML;
        btn.innerText = "Procesando...";
        btn.disabled = true;

        try {
            // Enviamos los datos del formulario (monto, nombre, etc.)
            const formData = new FormData(form);
            const res = await fetch('../public/crear_preferencia.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.init_point) {
                // REDIRECCIÓN SEGURA: Usamos el punto de inicio oficial
                window.location.href = data.init_point;
            } else {
                console.error("Error de MP:", data);
                alert("Error al generar el pago: " + (data.error || "Desconocido"));
            }

        } catch (error) {
            console.error('Error de conexión:', error);
            alert("Error de conexión con el servidor.");
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    };
});
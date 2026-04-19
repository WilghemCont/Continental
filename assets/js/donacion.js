// assets/js/donacion.js

document.addEventListener('DOMContentLoaded', () => {
    const metodoSelect = document.getElementById('metodoSelect');
    const paypalArea = document.getElementById('paypal-area');
    const btnManual = document.getElementById('btnManual');
    const montoInput = document.getElementById('monto');

    // 1. Control de visibilidad de métodos de pago
    if (metodoSelect) {
        metodoSelect.addEventListener('change', function() {
            if (this.value === 'PayPal') {
                paypalArea.style.display = 'block';
                btnManual.style.display = 'none';
            } else {
                paypalArea.style.display = 'none';
                btnManual.style.display = 'block';
            }
        });
    }

    // 2. Configuración de PayPal SDK
    if (document.getElementById('paypal-button-container')) {
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'gold',
                shape:  'pill',
                label:  'paypal'
            },
            createOrder: function(data, actions) {
                let monto = montoInput.value;
                if (!monto || monto <= 0) {
                    alert("Por favor, ingresa un monto válido antes de continuar.");
                    return actions.reject();
                }
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: monto }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    procesarDonacionPaypal(details);
                });
            },
            onError: function(err) {
                console.error('Error en el pago:', err);
                alert('Hubo un error al procesar el pago con PayPal.');
            }
        }).render('#paypal-button-container');
    }
});

/**
 * Envía los datos al servidor mediante Fetch API
 */
function procesarDonacionPaypal(details) {
    const datos = {
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        monto: document.getElementById('monto').value,
        metodo: "PayPal",
        id_transaccion: details.id,
        estado: details.status
    };

    fetch('../api/guardar_paypal.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            alert(`¡Gracias ${details.payer.name.given_name}! Donación registrada con éxito.`);
            window.location.href = "gracias.php";
        } else {
            alert("Error al registrar: " + res.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error de conexión con el servidor.");
    });
}
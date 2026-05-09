document.addEventListener('DOMContentLoaded', () => {
    // 1. CONFIGURACIÓN GRÁFICA MENSUAL
    const ctxMes = document.getElementById('chartMeses');
    if (ctxMes) {
        new Chart(ctxMes, {
            type: 'bar',
            data: {
                labels: window.DATA_CHART_MESES.map(m => m.mes),
                datasets: [{
                    label: 'S/ Recaudado',
                    data: window.DATA_CHART_MESES.map(m => m.total),
                    backgroundColor: '#6C47FF20',
                    borderColor: '#6C47FF',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    // 2. CONFIGURACIÓN GRÁFICA MÉTODOS
    const ctxMet = document.getElementById('chartMetodos');
    if (ctxMet) {
        new Chart(ctxMet, {
            type: 'doughnut',
            data: {
                labels: window.DATA_CHART_METODOS.map(m => m.metodo_pago),
                datasets: [{
                    data: window.DATA_CHART_METODOS.map(m => m.total),
                    backgroundColor: ['#6C47FF', '#10B981', '#F59E0B', '#3B82F6'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // 3. ACTUALIZACIÓN EN TIEMPO REAL
    const btnUpdate = document.getElementById('btnUpdateStats');
    if (btnUpdate) {
        btnUpdate.addEventListener('click', async () => {
            btnUpdate.disabled = true;
            btnUpdate.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';

            const items = document.querySelectorAll('.caso-item');
            for (const item of items) {
                const id = item.dataset.id;
                try {
                    const res = await fetch(`index.php?controller=estadistica&action=progreso_json&id=${id}`);
                    const data = await res.json();
                    
                    if (data.ok) {
                        const bar = item.querySelector('.progress-bar');
                        const pctLabel = item.querySelector('.small.fw-bold');
                        const montoLabel = item.querySelector('.fw-black');
                        
                        const pct = Math.min(100, data.porcentaje);
                        bar.style.width = pct + '%';
                        pctLabel.innerText = pct + '% Alcanzado';
                        montoLabel.innerText = 'S/ ' + parseFloat(data.monto_recaudado).toLocaleString();
                    }
                } catch (e) { console.error("Error actualizando ID: " + id); }
            }

            btnUpdate.disabled = false;
            btnUpdate.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Actualizar';
        });
    }
});
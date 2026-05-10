<?php
// ====================== CORRECCIÓN DE RUTAS ======================
require_once __DIR__ . '/../models/donaciones.php';
require_once __DIR__ . '/../view/layout/header.php';

$modelDonacion = new DonacionModel();

$total_recaudado = $modelDonacion->obtenerSumaTotal();
$datosMetodos    = $modelDonacion->obtenerTotalesPorMetodo();
$comisiones      = $modelDonacion->obtenerComisionesPorCaso();

$metodos = [];
$montos  = [];

foreach ($datosMetodos as $dm) {
    $metodos[] = $dm['metodo'];
    $montos[]  = $dm['total'];
}

// Totales de comisiones
$totalComisiones  = array_sum(array_column($comisiones, 'comision'));
$totalDonaciones  = array_sum(array_column($comisiones, 'total_donado'));
?>

<div class="container py-5 fade-up">
    <div class="row align-items-center mb-5">
        <div class="col-md-7">
            <h2 class="about-title mb-1">Impacto de Donaciones</h2>
            <p class="about-text">Visualiza cómo la comunidad está aportando a las causas sociales.</p>
        </div>
        <div class="col-md-5">
            <div class="stat-card text-center text-white border-0 shadow-lg" style="background: var(--grad); border-radius: 20px;">
                <p class="small mb-1 opacity-75 fw-bold text-uppercase">Total Recaudado</p>
                <div class="stat-num" style="font-size: 3rem;">S/ <?php echo number_format($total_recaudado, 2); ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 18px;">
                <h5 class="fw-bold mb-4" style="color: var(--dark);">📊 Donaciones por Método de Pago</h5>
                <div style="min-height: 350px;">
                    <canvas id="graficoDonar"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 18px;">
                <h6 class="fw-bold mb-4 text-muted">Desglose de Aportes</h6>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <?php foreach($datosMetodos as $d): ?>
                            <tr>
                                <td class="ps-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width:12px; height:12px; border-radius:50%; background: var(--verde);"></div>
                                        <span class="small fw-bold"><?php echo htmlspecialchars($d['metodo']); ?></span>
                                    </div>
                                </td>
                                <td class="text-end pe-0 fw-bold text-primary">S/ <?php echo number_format($d['total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-auto p-3 bg-light rounded-3 text-center">
                    <p class="small text-muted mb-0">Gracias por ser parte del cambio 💙</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comisiones por Casos -->
    <div class="row align-items-center mt-5 mb-4">
        <div class="col-md-7">
            <h2 class="about-title mb-1">Comisiones por Casos</h2>
            <p class="about-text">Ingresos generados por casos sociales activos.</p>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-3" style="border-radius:18px; background:var(--grad); color:white;">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <p class="small mb-1 opacity-75 fw-bold text-uppercase">Total Donado</p>
                        <div class="fw-bold fs-4">S/ <?php echo number_format($totalDonaciones, 2); ?></div>
                    </div>
                    <div class="col-6">
                        <p class="small mb-1 opacity-75 fw-bold text-uppercase">Total Comisiones</p>
                        <div class="fw-bold fs-4">S/ <?php echo number_format($totalComisiones, 2); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert border-0 shadow-sm mb-4 d-flex align-items-start gap-3" style="border-radius:14px; background:#eff6ff;">
        <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
        <div>
            <strong>Cálculo automático de comisiones:</strong> 
            3% hasta S/ 10,000 y 5% para montos superiores.
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:18px;">
                <h5 class="fw-bold mb-4" style="color:var(--dark);">📈 Donado vs Comisión</h5>
                <div style="min-height:320px;">
                    <canvas id="graficoComisiones"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:18px;">
                <h6 class="fw-bold mb-3 text-muted">Detalle por Donante</h6>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle small">
                        <thead>
                            <tr class="text-muted">
                                <th class="ps-0">Donante</th>
                                <th class="text-end">Donado</th>
                                <th class="text-end">Comisión</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($comisiones as $c): ?>
                            <tr>
                                <td class="ps-0 fw-semibold"><?php echo htmlspecialchars($c['nombre']); ?></td>
                                <td class="text-end">S/ <?php echo number_format($c['total_donado'], 2); ?></td>
                                <td class="text-end text-success fw-bold">S/ <?php echo number_format($c['comision'], 2); ?></td>
                                <td class="text-end">
                                    <span class="badge bg-<?php echo ($c['porcentaje'] ?? 0) >= 5 ? 'warning text-dark' : 'info text-dark'; ?>">
                                        <?php echo $c['porcentaje'] ?? 0; ?>%
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($comisiones)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox d-block fs-3 mb-2 opacity-50"></i>
                                    Sin donaciones registradas
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gráfico Donut - Métodos de pago
    new Chart(document.getElementById('graficoDonar'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($metodos); ?>,
            datasets: [{
                data: <?php echo json_encode($montos); ?>,
                backgroundColor: ['#2a7ab5', '#3a9e6f', '#1a5a8a', '#e8f3fb', '#256b4a'],
                borderWidth: 0,
                hoverOffset: 25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { position: 'bottom', padding: 20 }
            }
        }
    });

    // Gráfico de Barras - Donado vs Comisión
    const ctxCom = document.getElementById('graficoComisiones');
    if (ctxCom) {
        new Chart(ctxCom, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($comisiones, 'nombre')); ?>,
                datasets: [
                    {
                        label: 'Total Donado',
                        data: <?php echo json_encode(array_map('floatval', array_column($comisiones, 'total_donado'))); ?>,
                        backgroundColor: '#2a7ab5',
                        borderRadius: 6
                    },
                    {
                        label: 'Comisión Plataforma',
                        data: <?php echo json_encode(array_map('floatval', array_column($comisiones, 'comision'))); ?>,
                        backgroundColor: '#3a9e6f',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'S/ ' + v.toLocaleString('es-PE') }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/../view/layout/footer.php'; ?>
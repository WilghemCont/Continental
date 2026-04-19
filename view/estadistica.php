<?php
require_once("../models/donaciones.php");
$modelDonacion = new DonacionModel();

$total_recaudado = $modelDonacion->obtenerSumaTotal();
$datosMetodos = $modelDonacion->obtenerTotalesPorMetodo();

$metodos = [];
$montos = [];

foreach ($datosMetodos as $dm) {
    $metodos[] = $dm['metodo'];
    $montos[] = $dm['total'];
}

require_once 'layout/header.php'; 
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
                                        <span class="small fw-bold"><?php echo $d['metodo']; ?></span>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('graficoDonar').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($metodos); ?>,
            datasets: [{
                data: <?php echo json_encode($montos); ?>,
                backgroundColor: [
                    '#2a7ab5', // Azul SocialFunding
                    '#3a9e6f', // Verde SocialFunding
                    '#1a5a8a', // Variación Azul
                    '#e8f3fb', // Azul Light
                    '#256b4a'  // Verde Dark
                ],
                hoverOffset: 20,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 25,
                        usePointStyle: true,
                        font: { family: 'DM Sans', size: 13 }
                    }
                }
            },
            cutout: '65%' // Estilo moderno de anillo
        }
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
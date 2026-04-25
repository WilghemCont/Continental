<?php
session_start();
require_once("../models/donaciones.php");

// Seguridad: Si no hay usuario, al login
if (!isset($_SESSION['idlogin'])) { 
    header("Location: login.php");
    exit;
}

$model = new DonacionModel();
$total_general = $model->obtenerSumaTotal();
$datosMes = $model->obtenerDonacionesPorMes();
$datosMetodo = $model->obtenerTotalesPorMetodo();
$topDonantes = $model->obtenerTopDonantes();

// Preparar datos para JS
$mesesLabels = json_encode(array_column($datosMes, 'mes'));
$mesesValores = json_encode(array_column($datosMes, 'total'));
$metodosLabels = json_encode(array_column($datosMetodo, 'metodo'));
$metodosValores = json_encode(array_column($datosMetodo, 'total'));

require_once 'layout/header.php'; 
?>

<div class="container py-5 fade-up">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="about-title mb-1">Panel de Control</h2>
            <p class="about-text">Bienvenido, administrador. Aquí está el resumen de impacto.</p>
        </div>       
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card text-center text-white border-0 shadow-lg" style="background: var(--grad);">
                <p class="small mb-1 opacity-75 fw-bold text-uppercase">Total Recaudado Histórico</p>
                <div class="stat-num">S/ <?php echo number_format($total_general, 2); ?></div>
            </div>
        </div>
        </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 18px;">
                <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-graph-up-arrow me-2 text-primary"></i> Evolución Mensual</h5>
                <div style="height: 300px;">
                    <canvas id="chartMes"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 18px;">
                <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-pie-chart-fill me-2 text-success"></i> Métodos de Pago</h5>
                <div style="height: 300px;">
                    <canvas id="chartMetodo"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 18px;">
        <h5 class="fw-bold mb-4" style="color: var(--dark);"><i class="bi bi-trophy-fill me-2 text-warning"></i> Top 5 Donantes</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 text-muted small fw-bold px-4">POSICIÓN</th>
                        <th class="border-0 text-muted small fw-bold">NOMBRE</th>
                        <th class="border-0 text-muted small fw-bold text-end">TOTAL APORTADO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    foreach($topDonantes as $d): 
                    ?>
                    <tr>
                        <td class="px-4">
                            <span class="badge rounded-pill <?php echo ($pos==1) ? 'bg-warning' : 'bg-light text-dark border'; ?>">
                                #<?php echo $pos++; ?>
                            </span>
                        </td>
                        <td class="fw-bold text-secondary"><?php echo $d['nombre']; ?></td>
                        <td class="text-end fw-bold text-success">S/ <?php echo number_format($d['total'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Estilos comunes
    Chart.defaults.font.family = 'DM Sans';
    
    // Gráfico Mensual (Línea)
    new Chart(document.getElementById('chartMes'), {
        type: 'line',
        data: {
            labels: <?php echo $mesesLabels; ?>,
            datasets: [{
                label: 'Donaciones',
                data: <?php echo $mesesValores; ?>,
                borderColor: '#2a7ab5',
                backgroundColor: 'rgba(42, 122, 181, 0.1)',
                fill: true,
                tension: 0.4, // Curva suavizada
                pointRadius: 5,
                pointBackgroundColor: '#2a7ab5'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Gráfico Métodos (Dona)
    new Chart(document.getElementById('chartMetodo'), {
        type: 'doughnut',
        data: {
            labels: <?php echo $metodosLabels; ?>,
            datasets: [{
                data: <?php echo $metodosValores; ?>,
                backgroundColor: ['#2a7ab5', '#3a9e6f', '#1a5a8a', '#256b4a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
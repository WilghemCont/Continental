
<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">
    <!-- CABECERA -->
    <div class="mb-5">
        <h2 class="about-title mb-1">Panel de Estadísticas</h2>
        <p class="text-muted">Monitoreo global de impacto y recaudación en tiempo real.</p>
    </div>

    <!-- KPI CARDS -->
    <div class="row g-4 mb-5">
        <!-- Reutilizamos la estructura de tarjetas pequeñas con iconos -->
        <?php 
        $kpis = [
            ['🎯', 'Casos Activos', $resumen['activos'] ?? 0, ($resumen['cerrados'] ?? 0) . ' cerrados', 'bg-primary-subtle text-primary'],
            ['💰', 'Total Recaudado', 'S/ ' . number_format($resumen['monto_recaudado'] ?? 0, 0), 'Meta: S/ ' . number_format($resumen['total_meta'] ?? 0, 0), 'bg-success-subtle text-success'],
            ['📋', 'Total Donaciones', number_format($kpi_donaciones['total_donaciones'] ?? 0), ($kpi_donaciones['total_donadores'] ?? 0) . ' donadores', 'bg-info-subtle text-info'],
            ['⚡', 'Aporte Promedio', 'S/ ' . number_format($kpi_donaciones['promedio_monto'] ?? 0, 0), 'por transacción', 'bg-warning-subtle text-warning']
        ];
        foreach($kpis as $k): ?>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm <?= $k[4] ?> rounded-circle d-flex align-items-center justify-content-center fs-4 me-3" style="width: 50px; height: 50px;">
                        <?= $k[0] ?>
                    </div>
                    <div>
                        <p class="text-muted small fw-bold mb-0"><?= $k[1] ?></p>
                        <h4 class="fw-bold mb-0 text-dark"><?= $k[2] ?></h4>
                    </div>
                </div>
                <small class="text-muted fw-medium"><?= $k[3] ?></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ALERTAS -->
    <?php if(!empty($casos_pendientes)): ?>
    <div class="alert bg-warning-subtle border-0 rounded-4 p-4 mb-5 shadow-sm d-flex align-items-center justify-content-between">
        <div>
            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
            <span class="fw-bold text-dark">
                Hay <?= count($casos_pendientes) ?> casos que alcanzaron su meta y esperan transferencia.
            </span>
        </div>
        <a href="index.php?controller=transferencia&action=index" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Gestionar</a>
    </div>
    <?php endif; ?>

    <!-- GRÁFICAS -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 25px;">
                <h5 class="section-title mb-4"><i class="bi bi-graph-up me-2"></i>Recaudación Mensual</h5>
                <canvas id="chartMeses" height="280"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 25px;">
                <h5 class="section-title mb-4"><i class="bi bi-pie-chart me-2"></i>Métodos de Pago</h5>
                <canvas id="chartMetodos" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- MONITOREO DE CASOS -->
    <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 25px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="section-title mb-0">Monitoreo de Recaudación en Tiempo Real</h5>
            <button id="btnUpdateStats" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-arrow-clockwise me-2"></i>Actualizar
            </button>
        </div>

        <div class="row g-4">
            <?php foreach($casos as $caso): 
                $pct = min(100, (float)$caso['porcentaje']);
                $colorBar = $pct >= 100 ? 'bg-success' : ($pct >= 70 ? 'bg-primary' : 'bg-warning');
            ?>
            <div class="col-md-6 col-xl-4 caso-item" data-id="<?= $caso['id'] ?>">
                <div class="p-4 border rounded-4 bg-white transition-hover h-100 shadow-sm">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="small fw-bold text-primary"><?= esc($caso['nombre_ong']) ?></span>
                        <span class="badge bg-light text-dark rounded-pill border"><?= ucfirst($caso['estado']) ?></span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3"><?= esc($caso['titulo_caso']) ?></h6>
                    
                    <div class="progress mb-2" style="height: 10px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?= $colorBar ?>" style="width: <?= $pct ?>%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="fw-bold text-muted"><?= $pct ?>% Alcanzado</small>
                        <small class="fw-black text-dark">S/ <?= number_format($caso['monto_recaudado'], 2) ?></small>
                    </div>

                    <a href="index.php?controller=estadistica&action=detalle&id=<?= $caso['id'] ?>" class="btn btn-primary btn-sm w-100 rounded-pill">Ver Detalle</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Pasamos datos PHP a JS de forma segura -->
<script>
    window.DATA_CHART_MESES = <?= json_encode($por_mes ?? []) ?>;
    window.DATA_CHART_METODOS = <?= json_encode($por_metodo ?? []) ?>;
</script>

<?php require_once 'layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/estadistica.js"></script>

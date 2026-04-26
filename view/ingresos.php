<?php
require_once("../models/ingresos.php");
$model = new IngresoModel();

$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';
$ingresos = $model->listar($desde, $hasta);

$total_general = 0;
$total_comisiones = 0;
$total_patrocinios = 0;

if ($ingresos) {
    foreach ($ingresos as $i) {
        $monto = floatval($i['monto_final']);
        $total_general += $monto;
        if ($i['tipo'] === 'Comisión por donación') $total_comisiones += $monto;
        elseif ($i['tipo'] === 'Patrocinio') $total_patrocinios += $monto;
    }
}

require_once 'layout/header.php'; 
?>

<div class="container py-5 fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="about-title mb-1">Gestión de Ingresos</h2>
            <p class="about-text">Monitoreo financiero y control de patrocinios</p>
        </div>
        <a href="nuevo_ingreso.php" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo Ingreso
        </a>
    </div>

    <div class="card p-3 mb-5 bg-white border-0 shadow-sm" style="border-radius: 18px;">
        <form class="row g-3 align-items-end" method="GET">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Fecha Inicio</label>
                <input type="date" name="desde" value="<?php echo htmlspecialchars($desde); ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Fecha Fin</label>
                <input type="date" name="hasta" value="<?php echo htmlspecialchars($hasta); ?>" class="form-control">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary shadow-sm"><i class="bi bi-funnel"></i> Filtrar</button>
            </div>
            <div class="col-md-2 d-grid">
                <a href="ingresos.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Limpiar</a>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card text-center text-white" style="background: var(--grad);">
                <div class="stat-num">S/ <?php echo number_format($total_general, 2); ?></div>
                <p>Ingresos Totales</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center bg-white border-0 shadow-sm">
                <div class="stat-num" style="color: var(--azul);">S/ <?php echo number_format($total_comisiones, 2); ?></div>
                <p class="text-muted">Total Comisiones</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center bg-white border-0 shadow-sm">
                <div class="stat-num" style="color: var(--verde);">S/ <?php echo number_format($total_patrocinios, 2); ?></div>
                <p class="text-muted">Total Patrocinios</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 overflow-hidden">
        <div class="d-flex align-items-center mb-4">
            <div class="about-icon me-3" style="width: 45px; height: 45px; font-size: 1.2rem;"><i class="bi bi-list-check"></i></div>
            <h5 class="fw-bold m-0" style="color: var(--dark);">Últimos Movimientos</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle border-light">
                <thead style="background: var(--azul-light);">
                    <tr>
                        <th class="border-0 px-4 py-3 text-muted small fw-bold">FECHA</th>
                        <th class="border-0 py-3 text-muted small fw-bold">TIPO</th>
                        <th class="border-0 py-3 text-muted small fw-bold">EMPRESA / ORIGEN</th>
                        <th class="border-0 py-3 text-muted small fw-bold">MONTO FINAL</th>
                        <th class="border-0 px-4 py-3 text-muted small fw-bold text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($ingresos): foreach($ingresos as $i): ?>
                    <tr>
                        <td class="px-4"><span class="about-badge" style="background: var(--azul-light); color: var(--azul);"><?php echo $i['fecha']; ?></span></td>
                        <td class="fw-bold"><?php echo $i['tipo']; ?></td>
                        <td class="text-muted small"><?php echo $i['empresa'] ?: $i['subtipo'] ?: '—'; ?></td>
                        <td class="fw-bold text-success">S/ <?php echo number_format($i['monto_final'], 2); ?></td>
                        <td class="px-4 text-end">
                            <button type="button" 
                                    class="btn btn-sm btn-light rounded-circle text-danger shadow-sm" 
                                    onclick="eliminarIngreso(<?php echo $i['id']; ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">No se encontraron registros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold mb-3" style="color: var(--dark);">¿Estás seguro?</h4>
                <p class="text-muted mb-4">Esta acción eliminará el registro de forma permanente de la base de datos.</p>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-100 py-2 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">
                        Cancelar
                    </button>
                    <button type="button" id="btnConfirmarEliminar" class="btn btn-danger w-100 py-2 fw-bold" style="border-radius: 10px; background-color: #e11d48;">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once 'layout/footer.php'; ?>
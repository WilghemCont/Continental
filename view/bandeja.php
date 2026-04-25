<?php 
require_once 'layout/header.php'; 

// Seguridad
if (!isset($_SESSION["idlogin"]) || $_SESSION["tipo"] !== 'ADMIN') {
    header("Location: home.php");
    exit;
}

// Inicialización de seguridad para evitar errores de "Variable undefined"
$stats   = $stats   ?? [];
$filtros = $filtros ?? [];
$casos   = $casos   ?? [];
?>

<div class="container-fluid py-4 fade-up">
    
    <div class="row g-3 mb-4">
        <?php 
        $cards = [
            ['total',      'Total de Casos', 'bi-folder',        'bg-primary'],
            ['pendientes',  'Pendientes',     'bi-clock-history', 'bg-warning'],
            ['aprobados',   'Aprobados',      'bi-check-circle',  'bg-success'],
            ['observados',  'Observados',     'bi-eye',           'bg-info'],
            ['rechazados',  'Rechazados',     'bi-x-circle',      'bg-danger'],
            ['publicados',  'Publicados Web', 'bi-globe',         'bg-dark']
        ];
        
        foreach ($cards as $card): 
            $key = $card[0];
            // Intentamos sacar el valor del array $stats que viene del controlador
            $val = (int)($stats[$key] ?? 0);
        ?>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius: 15px;">
                <div class="rounded-circle <?= $card[3] ?> text-white mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi <?= $card[2] ?>"></i>
                </div>
                <h4 class="fw-bold mb-0" id="st-<?= $key ?>"><?= $val ?></h4>
                <small class="text-muted fw-semibold" style="font-size: 0.7rem; text-transform: uppercase;"><?= $card[1] ?></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Buscar</label>
                    <input type="text" id="f-buscar" class="form-control rounded-3" 
                           placeholder="ONG, título, beneficiario...">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Clasificación</label>
                    <select id="f-clasificacion" class="form-select rounded-3">
                        <option value="">Todas</option>
                        <option value="salud">Salud</option>
                        <option value="educacion">Educación</option>
                        <option value="alimentacion">Alimentación</option>
                        <option value="vivienda">Vivienda</option>
                        <option value="desastres">Desastres Naturales</option>
                        <option value="medio_ambiente">Medio Ambiente</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Evaluación</label>
                    <select id="f-evaluacion" class="form-select rounded-3">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="observado">Observado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Proceso</label>
                    <select id="f-proceso" class="form-select rounded-3">
                        <option value="">Todos</option>
                        <option value="sin_proceso">Sin Proceso</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary rounded-3 w-100 fw-bold" onclick="cargarCasos()">
                        <i class="bi bi-search me-2"></i>Filtrar
                    </button>
                    <button class="btn btn-outline-secondary rounded-3 w-100 fw-bold" onclick="limpiarFiltros()">
                        <i class="bi bi-x-lg me-2"></i>Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clipboard-data me-2 text-primary"></i>Listado de Casos Sociales</h5>
            <span class="badge bg-light text-dark border px-3 rounded-pill fw-bold" id="total-count">0 caso(s)</span>
        </div>
        <div class="table-responsive px-4 pb-4">
            <table class="table table-hover align-middle">
                <thead class="bg-light text-muted small fw-bold text-uppercase">
                    <tr>
                        <th class="border-0">ID</th>
                        <th class="border-0">Caso / ONG</th>
                        <th class="border-0">Clasificación</th>
                        <th class="border-0">Evaluación</th>
                        <th class="border-0 text-center">Web</th>
                        <th class="border-0">Proceso</th>
                        <th class="border-0 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-body" class="border-top-0">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                    Detalle del Caso <span id="det-id" class="text-muted small">#0</span>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="bg-light p-4 rounded-4 mb-4">
                            <h3 id="det-titulo" class="fw-bold mb-1 text-dark">Cargando...</h3>
                            <p id="det-ong" class="text-primary fw-semibold mb-4"></p>
                            
                            <h6 class="fw-bold text-muted small text-uppercase mb-2">Descripción</h6>
                            <p id="det-descripcion" class="text-dark" style="white-space: pre-wrap;"></p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3">
                                    <small class="text-muted d-block">Beneficiario</small>
                                    <span id="det-beneficiario" class="fw-bold"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3">
                                    <small class="text-muted d-block">Monto Requerido</small>
                                    <span id="det-monto" class="fw-bold text-success"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 bg-light rounded-4 p-3 mb-3">
                            <label class="small fw-bold text-muted text-uppercase mb-2">Gestión de Evaluación</label>
                            <div id="det-eval-badge" class="mb-3"></div>
                            
                            <select id="select-eval" class="form-select border-0 shadow-sm rounded-3 mb-2">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="observado">Observado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                            <textarea id="comentario-eval" class="form-control border-0 shadow-sm rounded-3 mb-2" rows="3" placeholder="Nota de evaluación..."></textarea>
                            <button class="btn btn-primary w-100 fw-bold" onclick="guardarCambios()">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="alert-container"
     style="position: fixed; left: 50%; transform: translateX(-50%); bottom: 120px; z-index: 9999;">
</div>
<?php require_once 'layout/footer.php'; ?>
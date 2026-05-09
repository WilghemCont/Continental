<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">
    <?php
    // Cálculos de lógica de negocio
    $pct   = min(100, (float)$caso['porcentaje']);
    $falta = max(0, $caso['monto_requerido'] - $caso['monto_recaudado']);
    ?>

    <!-- BOTÓN VOLVER Y CABECERA -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="index.php?controller=caso&action=bandeja" class="btn btn-light rounded-pill px-4 mb-2 shadow-sm fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
            </a>
            <h2 class="about-title mt-2">Detalle de Recaudación</h2>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill px-4 py-2 <?= $caso['publicado'] ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?> fw-bold shadow-sm">
                <i class="bi bi-circle-fill me-2 small"></i>
                <?= $caso['publicado'] ? 'Caso Activo' : 'Borrador / Cerrado' ?>
            </span>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- INFO DEL CASO -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg p-4 p-md-5 h-100" style="border-radius: 25px;">
                <span class="about-badge mb-3">Información del Caso</span>
                <h3 class="fw-bold text-dark mb-3"><?= esc($caso['titulo_caso']) ?></h3>
                <p class="text-muted mb-4"><?= nl2br(esc($caso['descripcion'])) ?></p>

                <div class="row g-3">
                    <div class="col-md-6 p-3 bg-light rounded-4 border-start border-primary border-4">
                        <label class="small fw-bold text-muted text-uppercase d-block">ONG Responsable</label>
                        <span class="fw-bold"><?= esc($caso['nombre_ong']) ?></span>
                    </div>
                    <div class="col-md-6 p-3 bg-light rounded-4 border-start border-info border-4">
                        <label class="small fw-bold text-muted text-uppercase d-block">Fecha Límite</label>
                        <span class="fw-bold"><?= date('d/m/Y', strtotime($caso['fecha_publicacion'] . ' + 30 days')) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROGRESO -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg p-4 p-md-5 h-100 text-center" style="border-radius: 25px; background: linear-gradient(145deg, #ffffff, #f8f9ff);">
                <span class="about-badge mb-4 mx-auto">Estado de Meta</span>
                
                <div class="display-3 fw-black text-primary mb-0"><?= $pct ?>%</div>
                <p class="text-muted fw-bold mb-4">recaudado de la meta final</p>

                <div class="progress mb-5 shadow-sm" style="height: 18px; border-radius: 10px; background-color: #eee;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated <?= $pct >= 100 ? 'bg-success' : 'bg-primary' ?>" 
                         style="width: <?= $pct ?>%"></div>
                </div>

                <div class="row g-2">
                    <div class="col-4">
                        <div class="p-2 rounded-4" style="background: #e8f5e9;">
                            <div class="small fw-bold text-success">RECAUDADO</div>
                            <div class="fw-bold">S/ <?= number_format($caso['monto_recaudado'], 2) ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-4" style="background: #fff3e0;">
                            <div class="small fw-bold text-warning">FALTANTE</div>
                            <div class="fw-bold">S/ <?= number_format($falta, 2) ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-4" style="background: #e3f2fd;">
                            <div class="small fw-bold text-primary">META</div>
                            <div class="fw-bold">S/ <?= number_format($caso['monto_requerido'], 2) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LISTADO DE DONACIONES -->
    <div class="card border-0 shadow-lg" style="border-radius: 25px;">
        <div class="card-header bg-transparent border-0 p-4 p-md-5 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="section-title mb-0">
                <i class="bi bi-cash-stack me-2 text-primary"></i>Historial de Aportes (<?= count($donaciones) ?>)
            </h5>
            <a href="index.php?controller=donacion&action=nuevo" class="btn btn-primary rounded-pill px-4 fw-bold shadow">
                <i class="bi bi-plus-lg me-2"></i>Registrar Aporte
            </a>
        </div>
        <div class="card-body p-4 p-md-5 pt-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 rounded-start">Donador</th>
                            <th class="border-0">Monto</th>
                            <th class="border-0">Método</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 rounded-end">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($donaciones)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="../assets/img/empty.svg" width="80" class="opacity-25 mb-3 d-block mx-auto">
                                    <span class="text-muted fw-bold">Aún no se han registrado donaciones para este caso.</span>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($donaciones as $d): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold">
                                            <?= strtoupper(substr($d['donador_nombre'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= esc($d['donador_nombre']) ?></div>
                                            <div class="extra-small text-muted"><?= esc($d['donador_email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="fw-black text-success">S/ <?= number_format($d['monto'], 2) ?></span></td>
                                <td><span class="badge bg-light text-dark border rounded-pill px-3"><?= ucfirst($d['metodo_pago']) ?></span></td>
                                <td>
                                    <?php 
                                        $statusClass = $d['estado'] === 'verificado' ? 'bg-success' : ($d['estado'] === 'rechazado' ? 'bg-danger' : 'bg-warning');
                                    ?>
                                    <span class="badge <?= $statusClass ?> rounded-pill px-3"><?= ucfirst($d['estado']) ?></span>
                                </td>
                                <td class="text-muted small fw-bold"><?= date('d/m/Y H:i', strtotime($d['fecha_donacion'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
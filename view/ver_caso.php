<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">

    <!-- CABECERA DE PÁGINA -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="about-title mb-1"><?= esc($caso['titulo_caso']) ?></h2>
            <p class="text-muted mb-0">Expediente digital de caso social #${c.id}</p>
        </div>
        <div class="text-end">
            <span class="about-badge mb-2">Estado Actual</span>
            <br>
            <?php 
                $badgeColor = [
                    'pendiente' => 'bg-warning text-dark',
                    'aprobado'  => 'bg-success',
                    'rechazado' => 'bg-danger',
                    'observado' => 'bg-info text-dark',
                    'publicado' => 'bg-primary'
                ][$caso['estado_evaluacion']] ?? 'bg-secondary';
            ?>
            <span class="badge rounded-pill <?= $badgeColor ?> px-3 py-2 fs-6 shadow-sm">
                <?= ucfirst($caso['estado_evaluacion']) ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- COLUMNA IZQUIERDA: INFORMACIÓN -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="section-title mb-4"><i class="bi bi-info-circle me-2 text-primary"></i>Detalles de la Causa</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block">Institución Solicitante</label>
                            <span class="fs-5 fw-semibold text-dark"><?= esc($caso['nombre_ong']) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block">Beneficiario</label>
                            <span class="fs-5 fw-semibold text-dark"><?= esc($caso['nombre_beneficiario']) ?></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block">DNI / ID</label>
                            <span class="text-dark"><?= esc($caso['dni_beneficiario']) ?></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block">Edad</label>
                            <span class="text-dark"><?= esc($caso['edad_beneficiario']) ?> años</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block">Ubicación</label>
                            <span class="text-dark"><?= esc($caso['ubicacion']) ?></span>
                        </div>
                    </div>

                    <label class="small fw-bold text-muted text-uppercase d-block">Descripción de la situación</label>
                    <p class="about-text p-3 bg-light rounded-4 mt-2">
                        <?= nl2br(esc($caso['descripcion'])) ?>
                    </p>
                </div>
            </div>

            <!-- DOCUMENTOS Y FOTOS -->
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="section-title mb-4"><i class="bi bi-paperclip me-2 text-success"></i>Archivos y Evidencias</h5>
                    
                    <div class="row g-3">
                        <!-- FOTO DEL BENEFICIARIO -->
                        <?php if($caso['foto_beneficiario']): ?>
                        <div class="col-md-6">
                            <div class="p-2 border rounded-4 text-center bg-white shadow-sm">
                                <label class="small fw-bold text-muted d-block mb-2 text-uppercase">Foto de Referencia</label>
                                <img src="../assets/uploads/fotos/<?= $caso['foto_beneficiario'] ?>" class="img-fluid rounded-4 mb-2" style="max-height: 250px; width: 100%; object-fit: cover;">
                                <a href="../assets/uploads/fotos/<?= $caso['foto_beneficiario'] ?>" target="_blank" class="btn btn-sm btn-light rounded-pill w-100">Ver imagen completa</a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- DOCUMENTO DE SOLICITUD -->
                        <?php if($caso['documento_solicitud']): ?>
                        <div class="col-md-6">
                            <div class="p-2 border rounded-4 text-center bg-white shadow-sm h-100 d-flex flex-column">
                                <label class="small fw-bold text-muted d-block mb-2 text-uppercase">Expediente / PDF</label>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center flex-column py-4">
                                    <i class="bi bi-file-earmark-pdf text-danger display-4 mb-2"></i>
                                    <p class="small text-muted px-3"><?= esc($caso['documento_solicitud']) ?></p>
                                </div>
                                <a href="../assets/uploads/docs/<?= $caso['documento_solicitud'] ?>" target="_blank" class="btn btn-sm btn-light rounded-pill w-100 mt-auto">
                                    Abrir Documento PDF
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(!$caso['foto_beneficiario'] && !$caso['documento_solicitud']): ?>
                            <div class="col-12 text-center py-4 opacity-50">
                                <i class="bi bi-folder-x display-4"></i>
                                <p>No se adjuntaron documentos en este registro.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: ACCIONES -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 20px; background: var(--white);">
                <h5 class="fw-bold mb-4">Acciones de Gestión</h5>

                <div class="d-grid gap-3">
                    <?php if(in_array($caso['estado_evaluacion'], ['pendiente','observado'])): ?>                        
                       <a href="index.php?controller=evaluacion&action=ver&id=<?= $caso['id'] ?>" class="btn btn-primary py-3 rounded-pill fw-bold shadow">
                            <i class="bi bi-shield-check me-2"></i> Evaluar Caso
                        </a>
                    <?php endif; ?>

                    <?php if($caso['estado_evaluacion'] === 'aprobado'): ?>

                        <a href="index.php?controller=publicacion&action=crear&id=<?= $caso['id'] ?>" 
                        class="btn btn-success py-3 rounded-pill fw-bold shadow">

                            <i class="bi bi-megaphone me-2"></i>
                            Publicar en Web

                        </a>

                    <?php endif; ?>


                    <?php
                        $metaAlcanzada = $caso['monto_recaudado'] >= $caso['meta_total'];
                    ?>


                    <?php if(
                        $caso['estado_evaluacion'] === 'publicado'
                        && $metaAlcanzada
                        && $caso['estado_proceso'] !== 'finalizado'
                    ): ?>

                        <a href="index.php?controller=cierre&action=ver&id=<?= $caso['id'] ?>"
                        class="btn btn-danger py-3 rounded-pill fw-bold shadow">

                            <i class="bi bi-lock-fill me-2"></i>
                            Cerrar Caso

                        </a>

                    <?php endif; ?>


                    <?php if($caso['estado_proceso'] === 'finalizado'): ?>

                        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-0">

                            <div class="d-flex align-items-center">

                                <i class="bi bi-check-circle-fill fs-3 me-3"></i>

                                <div>
                                    <strong>Caso Finalizado</strong>
                                    <div class="small">
                                        El proceso fue cerrado correctamente.
                                    </div>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>


                    <hr class="my-2">

                    <a href="../view/bandeja.php" class="btn btn-outline-secondary py-3 rounded-pill fw-bold hover-back">
                        <i class="bi bi-arrow-left me-2"></i> Volver a la Lista
                    </a>
                </div>

                <div class="mt-4 p-3 bg-light rounded-4">
                    <p class="small text-muted mb-0 text-center">
                        <i class="bi bi-info-circle me-1"></i> Registrado el <?= date('d/m/Y', strtotime($caso['fecha_registro'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
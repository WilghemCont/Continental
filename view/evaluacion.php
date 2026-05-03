<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">
    
    <!-- CABECERA -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="about-title mb-1">Evaluación del Caso</h2>
            <p class="text-muted">Validación técnica y documental de la solicitud</p>
        </div>
        <a href="index.php?controller=caso&action=ver&id=<?= $caso['id'] ?>" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Ver Expediente
        </a>
    </div>

    <div class="row g-4">
        <!-- RESUMEN DEL CASO (COLUMNA LATERAL) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px;">
                <span class="about-badge mb-3">Datos del Solicitante</span>
                <h5 class="fw-bold text-dark mb-3"><?= esc($caso['titulo_caso']) ?></h5>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase d-block">Institución</label>
                    <span><?= esc($caso['nombre_ong']) ?></span>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase d-block">Clasificación</label>
                    <span class="badge bg-light text-dark border rounded-pill px-3"><?= ucfirst($caso['clasificacion']) ?></span>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold text-muted text-uppercase d-block">Monto Solicitado</label>
                    <span class="fs-5 fw-bold text-primary">S/ <?= number_format($caso['monto_requerido'], 2) ?></span>
                </div>
            </div>

            <div class="alert alert-info border-0 rounded-4 shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i>
                Si marca **"NO"** en algún ítem, es obligatorio detallar el motivo para que la ONG pueda subsanar.
            </div>
        </div>

        <!-- FORMULARIO DE CHECKLIST -->
        <div class="col-lg-8">
            <form method="POST" action="index.php?controller=evaluacion&action=guardar&id=<?= $caso['id'] ?>" id="formEvaluacion">
                <div class="card border-0 shadow-lg" style="border-radius: 25px;">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="section-title mb-4"><i class="bi bi-clipboard-check me-2 text-primary"></i>Checklist de Validación</h5>

                        <?php foreach($checklist as $item): ?>
                            <div class="item-evaluacion mb-4 p-4 border rounded-4 bg-white transition-hover">
                                <label class="fw-bold mb-3 d-block fs-5 text-dark">
                                    <?= esc($item['nombre']) ?>
                                </label>

                                <div class="d-flex gap-4 mb-3">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input check-opcion" type="radio" 
                                               name="check[<?= $item['id'] ?>]" 
                                               id="si-<?= $item['id'] ?>" 
                                               value="SI" 
                                               data-item="<?= $item['id'] ?>" required>
                                        <label class="form-check-label fw-bold text-success" for="si-<?= $item['id'] ?>">SÍ Cumple</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input check-opcion" type="radio" 
                                               name="check[<?= $item['id'] ?>]" 
                                               id="no-<?= $item['id'] ?>" 
                                               value="NO" 
                                               data-item="<?= $item['id'] ?>">
                                        <label class="form-check-label fw-bold text-danger" for="no-<?= $item['id'] ?>">NO Cumple</label>
                                    </div>
                                </div>

                                <!-- Caja de comentario oculta por defecto -->
                                <div class="mt-3 d-none" id="comentario-box-<?= $item['id'] ?>">
                                    <label class="small fw-bold text-muted mb-2">MOTIVO DE OBSERVACIÓN</label>
                                    <textarea name="comentario[<?= $item['id'] ?>]" 
                                              class="form-control input-premium" 
                                              rows="2" 
                                              placeholder="Describa qué falta o qué corregir..."></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="index.php?controller=caso&action=bandeja" class="btn btn-light px-4 rounded-pill fw-bold text-muted">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg">
                                FINALIZAR EVALUACIÓN <i class="bi bi-shield-fill-check ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container py-5 fade-up">
    
    <!-- CONTENEDOR PARA MENSAJES AJAX (NUEVO) -->
    <div id="alertaCaso" class="d-none"></div>

    <!-- CABECERA -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="about-title mb-1">Evaluación del Caso</h2>
            <p class="text-muted">Validación técnica y documental de la solicitud</p>
        </div>
        <!-- ... resto de tu código igual ... -->

<?php require_once 'layout/footer.php'; ?>
<script src="../assets/js/evaluacion.js"></script>


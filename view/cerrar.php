<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="about-title mb-1">
                <i class="bi bi-lock-fill text-danger me-2"></i>
                Cierre de Caso Social
            </h2>

            <p class="text-muted mb-0">
                Validación final del proceso y revisión del sustento presentado por la ONG.
            </p>
        </div>

        <span class="badge bg-danger rounded-pill px-4 py-3 shadow-sm">
            Etapa Final
        </span>

    </div>

    <?php
        $tieneDocumento = !empty($caso['documento_cierre']);
    ?>

    <?php if(!$tieneDocumento): ?>

        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            La ONG aún no adjuntó el documento sustento final.
            No es posible cerrar el caso hasta contar con la evidencia correspondiente.

        </div>

    <?php endif; ?>

    <form method="POST"
          action="index.php?controller=cierre&action=guardar&id=<?= $caso['id'] ?>">

        <div class="row g-4">

            <!-- CHECKLIST -->
            <div class="col-lg-6">

                <div class="card border-0 shadow-lg p-4 p-md-5 h-100"
                     style="border-radius: 25px;">

                    <h5 class="section-title mb-4">
                        <i class="bi bi-ui-checks-grid me-2 text-primary"></i>
                        Checklist de Validación
                    </h5>

                    <?php foreach($items as $item): ?>

                        <div class="border rounded-4 p-4 mb-4 bg-light">

                            <label class="fw-bold d-block mb-3">
                                <?= esc($item['nombre']) ?>
                            </label>

                            <div class="d-flex gap-4 mb-3">

                                <label class="d-flex align-items-center gap-2 fw-semibold">
                                    <input type="radio"
                                           name="check[<?= $item['id'] ?>]"
                                           value="SI"
                                           required>

                                    SI
                                </label>

                                <label class="d-flex align-items-center gap-2 fw-semibold">
                                    <input type="radio"
                                           name="check[<?= $item['id'] ?>]"
                                           value="NO">

                                    NO
                                </label>

                            </div>

                            <textarea
                                name="comentario[<?= $item['id'] ?>]"
                                class="form-control rounded-4"
                                rows="3"
                                placeholder="Comentario adicional u observación..."></textarea>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

            <!-- EVIDENCIAS Y DOCUMENTOS -->
<div class="col-lg-6">

    <div class="sticky-top" style="top: 100px;">

        <div class="card border-0 shadow-lg"
             style="border-radius: 25px; overflow: hidden;">

            <div class="card-body p-4">

                <h5 class="section-title mb-4">
                    <i class="bi bi-paperclip me-2 text-success"></i>
                    Evidencias y Sustento Final
                </h5>

                <div class="row g-3">

                    <!-- FOTO BENEFICIARIO -->
                    <?php if($caso['foto_beneficiario']): ?>

                        <div class="col-md-6">

                            <div class="p-2 border rounded-4 text-center bg-white shadow-sm h-100">

                                <label class="small fw-bold text-muted d-block mb-2 text-uppercase">
                                    Evidencia de Voucher
                                </label>

                                <img src="../assets/uploads/fotos/<?= $caso['foto_beneficiario'] ?>"
                                     class="img-fluid rounded-4 mb-2"
                                     style="height:250px; width:100%; object-fit:cover;">

                                <a href="../assets/uploads/fotos/<?= $caso['foto_beneficiario'] ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-light rounded-pill w-100">

                                    Ver imagen completa

                                </a>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- DOCUMENTO SOLICITUD -->
                    <?php if($caso['documento_solicitud']): ?>

                        <div class="col-md-6">

                            <div class="p-2 border rounded-4 text-center bg-white shadow-sm h-100 d-flex flex-column">

                                <label class="small fw-bold text-muted d-block mb-2 text-uppercase">
                                    Carta de la ONG
                                </label>

                                <div class="flex-grow-1 d-flex align-items-center justify-content-center flex-column py-4">

                                    <i class="bi bi-file-earmark-pdf text-danger display-4 mb-2"></i>

                                    <p class="small text-muted px-3">
                                        <?= esc($caso['documento_solicitud']) ?>
                                    </p>

                                </div>

                                <a href="../assets/uploads/docs/<?= $caso['documento_solicitud'] ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-light rounded-pill w-100 mt-auto">

                                    Abrir Documento PDF

                                </a>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- DOCUMENTO FINAL ONG -->
                    <?php if($caso['documento_cierre']): ?>

                        <div class="col-12">

                            <div class="border rounded-4 bg-light shadow-sm overflow-hidden">

                                <!-- CABECERA -->
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">

                                    <div>

                                        <label class="small fw-bold text-muted d-block text-uppercase">
                                            Sustento Final ONG
                                        </label>

                                        <div class="fw-bold">
                                            <?= esc($caso['documento_cierre']) ?>
                                        </div>

                                    </div>

                                    <a href="../assets/uploads/docs/<?= $caso['documento_cierre'] ?>"
                                       target="_blank"
                                       class="btn btn-danger rounded-pill px-4">

                                        <i class="bi bi-box-arrow-up-right me-2"></i>
                                        Abrir PDF

                                    </a>

                                </div>

                                <!-- PREVIEW PDF -->
                                <iframe
                                    src="../assets/uploads/docs/<?= $caso['documento_cierre'] ?>"
                                    width="100%"
                                    height="500"
                                    style="border:none;">
                                </iframe>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="col-12">

                            <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-0">

                                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                                La ONG aún no adjuntó el documento sustento final.

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <!-- BOTONES -->
        <div class="card border-0 shadow-sm p-4 mt-4"
             style="border-radius: 20px;">

            <button type="submit"
                    class="btn btn-success btn-lg rounded-pill fw-bold py-3 shadow-sm w-100"
                    <?= !$tieneDocumento ? 'disabled' : '' ?>>

                <i class="bi bi-check-circle-fill me-2"></i>

                <?= $tieneDocumento
                    ? 'Cerrar Caso Social'
                    : 'Falta Documento de Sustento' ?>

            </button>

            <a href="index.php?controller=caso&action=ver&id=<?= $caso['id'] ?>"
               class="btn btn-outline-secondary rounded-pill fw-bold py-3 mt-3">

                <i class="bi bi-arrow-left me-2"></i>
                Volver al Caso

            </a>

        </div>

    </div>

    </div>
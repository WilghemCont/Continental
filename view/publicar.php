<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">
    
    <!-- CONTENEDOR PARA MENSAJES AJAX -->
    <div id="alertaCaso" class="d-none"></div>

    <div class="mb-4">
        <h2 class="about-title mb-1">Publicar Caso</h2>
        <p class="text-muted">Prepare la información que verán los donantes en la plataforma pública.</p>
    </div>

    <form method="POST" action="index.php?controller=publicacion&action=guardar&id=<?= $caso['id'] ?>" id="formPublicacion">
        <div class="row g-4">
            
            <!-- FORMULARIO DE EDICIÓN -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 25px;">
                    <h5 class="section-title mb-4"><i class="bi bi-pencil-square me-2 text-primary"></i>Editor de Contenido</h5>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-2">Título Público</label>
                        <input type="text" name="titulo" id="titulo" class="form-control input-premium" 
                               value="<?= esc($caso['titulo_caso']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-2">Descripción para Donantes</label>
                        <textarea name="descripcion" id="descripcion" class="form-control input-premium" 
                                  rows="6" required><?= esc($caso['descripcion']) ?></textarea>
                    </div>

                    <!-- <div class="mb-4">
                        <label class="small fw-bold text-muted text-uppercase mb-2">URL de Imagen Destacada</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-image text-muted"></i></span>
                            <input type="text" name="imagen" id="imagen" class="form-control input-premium border-start-0" 
                                   value="assets/uploads/fotos/<?= $caso['foto_beneficiario'] ?>" placeholder="Ruta de la imagen...">
                        </div>
                    </div> -->

                    <div class="d-flex gap-2 pt-3">
                       <!--  <button type="submit" name="accion" value="borrador" class="btn btn-light rounded-pill px-4 fw-bold">
                            <i class="bi bi-archive me-2"></i>Guardar Borrador
                        </button> -->
                        <button type="submit" name="accion" value="publicar" class="btn btn-primary rounded-pill px-4 fw-bold shadow">
                            <i class="bi bi-cloud-arrow-up me-2"></i>Publicar Caso
                        </button>
                    </div>
                </div>
            </div>

            <!-- VISTA PREVIA (SIMULACIÓN DE TARJETA PÚBLICA) -->
            <div class="col-lg-6">
                <div class="sticky-top" style="top: 100px;">                                     
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                        <img id="previewImg" 
                            src="<?= "http://localhost/CONTINENTAL/assets/uploads/fotos/" . $caso['foto_beneficiario'] ?>" 
                            class="card-img-top" 
                            style="height: 250px; object-fit: cover;" 
                            alt="Vista previa"
                            onerror="this.src='http://localhost/CONTINENTAL/assets/img/placeholder.jpg'">
                        
                        <div class="card-body p-4">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 mb-2"><?= ucfirst($caso['clasificacion']) ?></span>
                            <h4 id="previewTitulo" class="fw-bold text-dark mb-3"><?= esc($caso['titulo_caso']) ?></h4>
                            <p id="previewDesc" class="text-muted small mb-4 line-clamp-3"><?= esc($caso['descripcion']) ?></p>
                            
                            <div class="p-3 bg-light rounded-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small fw-bold">Meta: S/ <?= number_format($caso['monto_requerido'], 2) ?></span>
                                    <span class="small text-primary fw-bold">0%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 5%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 border rounded-4 bg-white shadow-sm">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-2">Documentación Adjunta (PDF)</label>
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
                            <div>
                                <p class="mb-0 small fw-bold" id="previewPdfName"><?= $caso['documento_solicitud'] ?? 'expediente.pdf' ?></p>
                                <span class="text-muted extra-small">Archivo validado por el administrador</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<?php require_once 'layout/footer.php'; ?>
<script src="../assets/js/publicacion.js"></script>
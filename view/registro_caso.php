<?php require_once 'layout/header.php'; ?>

<div class="container py-5 fade-up">
    <div class="mb-4">
        <a href="bandeja.php" class="btn-regresar text-decoration-none fw-bold shadow-sm px-3 py-2 rounded-pill bg-white">
            <i class="bi bi-arrow-left me-1"></i> Volver a la Bandeja
        </a>
    </div>

    <div class="card card-premium shadow-lg border-0">        
        <div class="p-5 text-center text-white" style="background: var(--grad);">
            <i class="bi bi-file-earmark-medical fs-1 mb-2"></i>
            <h2 class="fw-bold mb-1">Registrar Nuevo Caso Social</h2>
            <p class="mb-0 opacity-75">Complete la información detallada para la evaluación del caso</p>
        </div>

        <div class="card-body p-4 p-md-5">
            <form id="formRegistroCaso" enctype="multipart/form-data">
                
                <!-- SECCIÓN 1: DATOS DE LA ONG -->
                <h5 class="section-title mb-4"><i class="bi bi-building me-2"></i>Información de la Institución</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="label-premium">Nombre de la ONG / Institución</label>
                        <input type="text" class="form-control input-premium" name="nombre_ong" required>
                    </div>
                    <div class="col-md-4">
                        <label class="label-premium">RUC</label>
                        <input type="text" class="form-control input-premium" name="ruc_ong" maxlength="11">
                    </div>
                    <div class="col-md-6">
                        <label class="label-premium">Email de Contacto</label>
                        <input type="email" class="form-control input-premium" name="email_ong" required>
                    </div>
                    <div class="col-md-6">
                        <label class="label-premium">Persona de Contacto</label>
                        <input type="text" class="form-control input-premium" name="contacto_ong">
                    </div>
                </div>

                <hr class="divider-premium">

                <!-- SECCIÓN 2: DETALLES DEL CASO -->
                <h5 class="section-title mb-4" style="color: var(--azul);"><i class="bi bi-info-circle me-2"></i>Detalles del Caso</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="label-premium">Título del Caso (Resumen impactante)</label>
                        <input type="text" class="form-control input-premium" name="titulo_caso" placeholder="Ej: Ayuda para cirugía de corazón..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="label-premium">Clasificación</label>
                        <select class="form-select input-premium" name="clasificacion" required>
                            <option value="salud">Salud</option>
                            <option value="desastres">Atención de Desastres</option>
                            <option value="medio_ambiente">Medio Ambiente</option>
                            <option value="educacion">Educación</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="label-premium">Monto Requerido (S/)</label>
                        <input type="number" step="0.01" class="form-control input-premium" name="monto_requerido" required>
                    </div>
                    <div class="col-md-12">
                        <label class="label-premium">Descripción Detallada</label>
                        <textarea class="form-control input-premium" name="descripcion" rows="4" required></textarea>
                    </div>
                </div>

                <hr class="divider-premium">

                <!-- SECCIÓN 3: BENEFICIARIO Y UBICACIÓN -->
                <h5 class="section-title mb-4" style="color: #6c757d;"><i class="bi bi-geo-alt me-2"></i>Beneficiario y Ubicación</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="label-premium">Nombre del Beneficiario / Comunidad</label>
                        <input type="text" class="form-control input-premium" name="nombre_beneficiario">
                    </div>
                    <div class="col-md-3">
                        <label class="label-premium">DNI / ID</label>
                        <input type="text" class="form-control input-premium" name="dni_beneficiario">
                    </div>
                    <div class="col-md-3">
                        <label class="label-premium">Edad</label>
                        <input type="number" class="form-control input-premium" name="edad_beneficiario">
                    </div>
                    <div class="col-md-12">
                        <label class="label-premium">Ubicación (Ciudad, Región)</label>
                        <input type="text" class="form-control input-premium" name="ubicacion" placeholder="Ej: Huancayo, Junín">
                    </div>
                </div>

                <hr class="divider-premium">

                <!-- SECCIÓN 4: ARCHIVOS ADJUNTOS -->
                <h5 class="section-title mb-4" style="color: var(--verde);"><i class="bi bi-cloud-arrow-up me-2"></i>Evidencias y Documentación</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="label-premium"><i class="bi bi-file-pdf me-1"></i>Documento de Solicitud (PDF/DOC)</label>
                        <input type="file" class="form-control input-premium" name="documento_solicitud" accept=".pdf,.doc,.docx">
                    </div>
                    <div class="col-md-6">
                        <label class="label-premium"><i class="bi bi-image me-1"></i>Foto del Beneficiario / Situación</label>
                        <input type="file" class="form-control input-premium" name="foto_beneficiario" accept="image/*">
                    </div>
                </div>

                <div id="alertaCaso" class="alert d-none rounded-4 mb-4"></div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <button type="reset" class="btn btn-light px-4 rounded-pill fw-bold text-muted">Limpiar</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow">
                        <i class="bi bi-send-check me-2"></i>Registrar Caso para Evaluación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
<script src="../assets/js/casos.js"></script>
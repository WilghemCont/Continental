<?php 
require_once 'layout/header.php'; 
?>

<div class="container py-5 fade-up">
    <div class="mb-5">
        <a href="ingresos.php" class="text-decoration-none text-muted hover-back px-4 py-2 rounded-pill d-inline-flex align-items-center bg-white shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Volver a Ingresos
        </a>
    </div>

    <div class="row g-5 align-items-center">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="pe-lg-5">
                <span class="about-badge mb-3">Gestión Financiera</span>
                <h1 class="about-title mb-4" style="font-size: 3rem;">Registro de nuevos ingresos</h1>
                <p class="about-text fs-5 mb-4">
                    Mantén el control exacto de las comisiones, donaciones voluntarias y patrocinios corporativos que hacen posible el funcionamiento de la plataforma.
                </p>
                
                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="p-3 border-0 bg-white shadow-sm rounded-4">
                            <i class="bi bi-graph-up-arrow text-primary fs-3"></i>
                            <h6 class="fw-bold mt-2">Trazabilidad</h6>
                            <p class="small text-muted mb-0">Registros detallados</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border-0 bg-white shadow-sm rounded-4">
                            <i class="bi bi-building-check text-success fs-3"></i>
                            <h6 class="fw-bold mt-2">Control Corporativo</h6>
                            <p class="small text-muted mb-0">Gestión de patrocinios</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-10 mx-auto">
            <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 25px;">
                
                <div class="p-4 text-center text-white" style="background: var(--grad);">
                    <div class="display-5 mb-2"><i class="bi bi-wallet2"></i></div>
                    <h3 class="fw-bold text-white mb-1">Detalle del Ingreso</h3>
                    <p class="opacity-75 mb-0 small">Completa los datos financieros</p>
                </div>

                <div class="card-body p-5 bg-white">
                    <form id="formIngreso" method="POST">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Tipo de Movimiento</label>
                                <select name="tipo" id="tipoSelect" class="form-select form-select-lg" required>
                                    <option value="">Seleccione...</option>                                    
                                    <option value="Donación voluntaria">Donación voluntaria</option>
                                    <option value="Patrocinio">Patrocinio</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Monto Base</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0 text-muted fw-bold">S/</span>
                                    <input type="number" step="0.01" name="monto" id="monto" class="form-control border-start-0" required placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Registro</label>
                                <input type="date" name="fecha" class="form-control form-control-lg text-muted" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Descripción (Opcional)</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Detalles adicionales del ingreso..."></textarea>
                            </div>
                        </div>

                        <div id="seccionPatrocinio" class="row g-3 bg-light p-3 rounded-4 mt-2" style="display:none;">
                            <div class="col-12 mb-1">
                                <h6 class="fw-bold text-primary m-0"><i class="bi bi-star-fill me-2"></i>Datos del Patrocinio</h6>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted">Tipo de Patrocinio</label>
                                <select name="subtipo" class="form-select">
                                    <option value="Económico">Económico</option>
                                    <option value="En especie">En especie</option>
                                    <option value="Publicidad">Publicidad</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Empresa / Entidad</label>
                                <input type="text" name="empresa" class="form-control" placeholder="Nombre de la empresa">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Valor Estimado (S/)</label>
                                <input type="number" step="0.01" name="valor" class="form-control" placeholder="0.00">
                            </div>                        
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6 rounded-pill shadow">
                                REGISTRAR INGRESO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="fw-bold mb-3" style="color: var(--dark);">¡Excelente!</h4>
                <p class="text-muted mb-4">Registro agregado correctamente.</p>
                
                <button type="button" id="btnSuccessOk" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 10px; background-color: #10b981; border: none;">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/ingresos.js"></script>
<?php require_once 'layout/footer.php'; ?>
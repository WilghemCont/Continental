<?php 
require_once 'layout/header.php'; 
?>

<div class="container py-5 fade-up">
    <div class="mb-5">
        <a href="index.php" class="text-decoration-none text-muted hover-back px-4 py-2 rounded-pill d-inline-flex align-items-center bg-white shadow-sm">
            <i class="bi bi-arrow-left me-2"></i> Volver al inicio
        </a>
    </div>

    <div class="row g-5 align-items-center">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="pe-lg-5">
                <span class="about-badge mb-3">Tu ayuda importa</span>
                <h1 class="about-title mb-4" style="font-size: 3rem;">Haz realidad un proyecto social</h1>
                <p class="about-text fs-5 mb-4">
                    Estás a un paso de generar un impacto real. Cada donación, por pequeña que sea, 
                    ayuda a financiar causas que transforman vidas en toda la región.
                </p>
                
                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="p-3 border-0 bg-white shadow-sm rounded-4">
                            <i class="bi bi-shield-check text-primary fs-3"></i>
                            <h6 class="fw-bold mt-2">100% Seguro</h6>
                            <p class="small text-muted mb-0">Transacciones protegidas</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border-0 bg-white shadow-sm rounded-4">
                            <i class="bi bi-heart-fill text-danger fs-3"></i>
                            <h6 class="fw-bold mt-2">Impacto Directo</h6>
                            <p class="small text-muted mb-0">Sin intermediarios</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-10 mx-auto">
            <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 25px;">
                
                <div class="p-5 text-center text-white" style="background: var(--grad);">
                    <div class="display-5 mb-2">🎁</div>
                    <h2 class="about-title text-white mb-1">Simular Donación</h2>
                    <p class="opacity-75 mb-0">Entorno de pruebas — SocialFunding</p>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="../public/guardar_donacion.php" method="POST">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre del Donante</label>
                                <input type="text" class="form-control form-control-lg" name="nombre" placeholder="Ej. Juan Pérez" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg" name="email" placeholder="juan@ejemplo.com" required>
                            </div>

                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-muted text-uppercase">Monto a Aportar</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0 text-muted fw-bold">S/</span>
                                    <input type="number" step="0.01" class="form-control border-start-0" name="monto" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-muted text-uppercase">Método</label>
                                <select class="form-select form-select-lg" name="metodo">
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Yape/Plin">Yape / Plin</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nota / Mensaje</label>
                                <textarea class="form-control" name="mensaje" rows="3" placeholder="Escribe un mensaje de apoyo..."></textarea>
                            </div>
                        </div>

                        <div class="row g-2 mt-4">
                            <div class="col-md-8">
                                <button type="button" onclick="iniciarPago()" class="btn btn-primary">
                                    Pagar con Mercado Pago
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="estadistica.php" class="btn btn-outline-primary w-100 py-3 fw-bold fs-5 shadow-sm" style="border-radius: 15px; border-width: 2px;">
                                    <i class="bi bi-graph-up"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> 
                    Los fondos se dirigirán al proyecto seleccionado.
                </p>
            </div>
        </div>
    </div>
</div>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="../assets/js/donacion.js"></script>
<?php require_once 'layout/footer.php'; ?>
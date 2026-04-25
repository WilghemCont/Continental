<?php 
require_once 'layout/header.php'; 
?>

<div class="container py-5 fade-up">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            
            <div class="mb-4">
                <a href="home.php" class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm text-decoration-none fw-semibold text-dark bg-white hover-back">
                    <i class="bi bi-arrow-left"></i> Volver al inicio
                </a>
            </div>

            <div class="card overflow-hidden">
                
                <div class="login-header p-5">
                    <i class="bi bi-person-badge fs-1 mb-2"></i>
                    <h2 class="text-white">Crear cuenta</h2>
                    <p class="mb-0 opacity-75">Únete a la red SocialFunding y comienza a ayudar</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form id="registroForm">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="login-form label" style="margin-top:0">Tipo de documento</label>
                                <select class="form-select" id="tipoDocumento" name="tipo_doc">
                                    <option value="">Cargando...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="login-form label" style="margin-top:0">Número de documento</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" class="form-control" id="numeroDocumento" name="num_doc" placeholder="Ej: 70654321">
                                </div>
                            </div>
                        </div>

                        <div id="personaFields" class="d-none">
                            <h5 class="section-title mb-4">
                                <i class="bi bi-person-fill me-2"></i>Datos Personales
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4"><input type="text" class="form-control" name="nombres" placeholder="Nombres"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="ape_paterno" placeholder="Apellido Paterno"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="ape_materno" placeholder="Apellido Materno"></div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="login-form label">FECHA DE NACIMIENTO</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="login-form label">SEXO</label>
                                    <select class="form-select">
                                        <option value="">Seleccione</option>
                                        <option>Masculino</option>
                                        <option>Femenino</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="login-form label">PAÍS</label>
                                    <select class="form-select" id="idpais" name="idpais"><option value="">Seleccione</option></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="login-form label">DEP.</label>
                                    <select class="form-select" id="iddepartamento" name="iddepartamento"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="login-form label">PROV.</label>
                                    <select class="form-select" id="idprovincia" name="idprovincia"></select>
                                </div>
                                <div class="col-md-3">
                                    <label class="login-form label">DIST.</label>
                                    <select class="form-select" id="iddistrito" name="iddistrito"></select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="domicilio" placeholder="Dirección de domicilio">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="+51">+51 PE</option>
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <input type="tel" class="form-control" name="celular" placeholder="Número de celular">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <input type="email" class="form-control" name="email" placeholder="Correo electrónico">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="passwordPersona" placeholder="Contraseña">
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="passwordPersona2" placeholder="Repetir contraseña">
                                </div>
                            </div>
                        </div>

                        <div id="empresaFields" class="d-none">
                            <h5 class="section-title mb-4" style="color: var(--verde);">
                                <i class="bi bi-building-fill me-2"></i>Datos de Empresa
                            </h5>
                            <div class="mb-3">
                                <input type="text" class="form-control mb-3" name="razon_social" placeholder="Razón Social">
                                <input type="text" class="form-control" name="direccion" placeholder="Dirección Fiscal">
                            </div>
                            </div>

                        <div id="alerta" class="alert mt-4 d-none" role="alert"></div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <button type="reset" class="btn btn-outline-secondary">Limpiar campos</button>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check2-circle me-2"></i>Finalizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
require_once 'layout/footer.php'; 
?>

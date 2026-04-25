<?php 
require_once 'layout/header.php'; 

// Seguridad: Si no hay sesión, no puede ver su cuenta
if (!isset($_SESSION["idlogin"])) {
    header("Location: login.php");
    exit;
}
?>

<div class="container py-5 fade-up">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="p-5 text-white text-center" style="background: linear-gradient(135deg, #2a7ab5, #3a9e6f);">
                    <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-0"><?php echo $_SESSION["nombre"] . " " . $_SESSION["apepat"]; ?></h2>
                    <span class="badge bg-white text-dark rounded-pill px-3 mt-2 opacity-75">
                        Perfil: <?php echo $_SESSION["tipo"]; ?>
                    </span>
                </div>

                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4 text-secondary border-bottom pb-2">
                        <i class="bi bi-info-circle me-2"></i>Información Personal
                    </h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Nombre Completo</label>
                            <p class="fs-5 fw-medium text-dark border-start border-primary border-3 ps-3">
                                <?php echo $_SESSION["nombre"] . " " . $_SESSION["apepat"]; ?>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Tipo de Usuario</label>
                            <p class="fs-5 fw-medium text-dark border-start border-success border-3 ps-3">
                                <?php echo $_SESSION["tipo"]; ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold">Identificador de Usuario</label>
                            <p class="fs-6 text-muted border-start border-secondary border-3 ps-3">
                                #<?php echo $_SESSION["idusuario"]; ?>
                            </p>
                        </div>

                        <div class="col-md-6 text-center text-md-end d-flex align-items-end justify-content-md-end">
                            <button class="btn btn-outline-primary rounded-pill px-4 me-2">
                                <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-5 rounded-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-shield-lock-fill text-warning fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Seguridad de la cuenta</h6>
                                <p class="small text-muted mb-0">Tu contraseña está protegida con encriptación de grado bancario. No compartas tus credenciales con nadie.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-4 text-center">
                    <a href="home.php" class="btn btn-link text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver al inicio
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
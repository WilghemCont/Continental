<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SocialFunding — Crowdfunding Social</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 
  <link rel="stylesheet" href="./../assets/css/index.css"/>

   <script>
    // ESTA LÍNEA ES VITAL: Define la ruta base para que los fetch funcionen
    window.BASE_URL = "http://localhost/CONTINENTAL/";
  </script>
</head>
<body>

<div class="bg-dark text-white py-2 small">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <span class="opacity-75">Síguenos:</span>
    </div>
    <div class="d-flex gap-3">
      <a href="#" class="top-link"><i class="bi bi-facebook"></i> Facebook</a>
      <a href="#" class="top-link"><i class="bi bi-instagram"></i> Instagram</a>
      <a href="#" class="top-link"><i class="bi bi-tiktok"></i> TikTok</a>
    </div>
  </div>
</div>

<nav id="navbar" class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand fw-bold" href="index.php">
      Social<span class="text-primary">Funding</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">

      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
          <a class="nav-link" href="#como">Cómo funciona</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#campanas">Campañas</a>
        </li>
      </ul>

      <div class="d-flex gap-2">
        <?php if (!isset($_SESSION["idlogin"])): ?>
          <a href="login.php" class="btn btn-outline-primary">Login</a>
          <a href="registro.php" class="btn btn-primary">Registro</a>
        <?php else: ?>
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1 text-primary"></i>
              <?php echo $_SESSION["nombre"] . " " . ($_SESSION["apepat"] ?? ""); ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
              <li><h6 class="dropdown-header">Perfil: <?php echo $_SESSION["tipo"]; ?></h6></li>
              <li><hr class="dropdown-divider"></li>
              
              <?php if ($_SESSION["tipo"] == 'ADMIN'): ?>
                <li><a class="dropdown-item" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="ingresos.php"><i class="bi bi-cash-stack me-2"></i>Ingresos</a></li>
                <li><a class="dropdown-item" href="bandeja.php"><i class="bi bi-cash-stack me-2"></i>Bandeja</a></li>
                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#"><i class="bi bi-folder me-2"></i>Maestros</a>
                  <ul class="dropdown-menu shadow border-0">
                     <li><a class="dropdown-item" href="donantes.php"><i class="bi bi-people me-2"></i>Donantes</a></li>
                  </ul>
                </li>
              
              <?php elseif ($_SESSION["tipo"] == 'DONANTE'): ?>
                <li><a class="dropdown-item" href="donar.php"><i class="bi bi-building me-2"></i>Donaciones</a></li>
                <li><a class="dropdown-item" href="patrocinios.php"><i class="bi bi-star me-2"></i>Patrocinios</a></li>
              
              <?php else: ?>
                <li><a class="dropdown-item" href="donaciones.php"><i class="bi bi-heart me-2"></i>Mis Donaciones</a></li>
              <?php endif; ?>

              <li><a class="dropdown-item" href="perfil.php"><i class="bi bi-person me-2"></i>Mi Cuenta</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="../Controllers/usuariocontroller.php?op=logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
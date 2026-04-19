<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar Sesión</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tu CSS -->
<link rel="stylesheet" href="./../assets/css/index.css">

</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="container">
  <div class="row justify-content-center">
    
    <div class="col-md-5 col-lg-4">
      
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="text-center text-white p-4" style="background: linear-gradient(135deg,#2a7ab5,#3a9e6f);">
          <h3 class="fw-bold mb-1">Bienvenido</h3>
          <p class="mb-0 small">Inicia sesión en SocialFunding</p>
        </div>
        
        <div class="mb-4">
            <a href="index.html"
                class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm text-decoration-none fw-semibold text-dark bg-white hover-back">
                
                <i class="fa-solid fa-arrow-left"></i>
                Volver al inicio
            </a>
        </div>

        <!-- FORM -->
        <div class="card-body p-4">

          <form action="login.php" method="POST" id="formLogin">

            <!-- EMAIL -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Correo electrónico</label>
              <input type="email" class="form-control rounded-3" id="correo" name="correo" placeholder="ejemplo@email.com" required>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control rounded-start-3" id="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePass">👁</button>
              </div>
            </div>

            <!-- ERROR -->
            <div id="error" class="text-danger small mb-3 text-center"></div>

            <!-- BUTTON -->
            <div class="d-grid">
              <button type="submit" class="btn btn-primary rounded-3 fw-semibold">
                Iniciar sesión
              </button>
            </div>

            <!-- EXTRA -->
            <div class="text-center mt-3">
              <small class="text-muted">
                ¿No tienes cuenta?
                <a href="registro.html" class="text-decoration-none fw-semibold">Regístrate</a>
              </small>
            </div>

          </form>

        </div>

      </div>

    </div>

  </div>
</div>

<!-- JS -->
<script src="./../assets/js/login.js"></script>

</body>
</html>
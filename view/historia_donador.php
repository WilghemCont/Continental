<?php
$estilo_pagina = 'home';
require_once __DIR__ . '/layout/header.php';
?>

<!-- HERO -->
<section class="container py-5">

  <div class="row align-items-center g-5">

    <div class="col-lg-6">

      <span class="about-badge mb-3">
        Experiencia del Donador
      </span>

      <h1 class="about-title mt-4">
        Donar debería sentirse humano, seguro y transparente
      </h1>

      <p class="about-text mt-4">
        SocialFunding conecta personas solidarias con causas reales mediante una experiencia moderna y confiable.
        Cada aporte ayuda a financiar historias auténticas que necesitan apoyo inmediato.
      </p>

      <div class="d-flex flex-wrap gap-3 mt-5">

        <div class="bg-white shadow-sm px-4 py-3 rounded-4">
          <div class="fw-bold fs-4 text-primary">100%</div>
          <small class="text-muted">Transacciones seguras</small>
        </div>

        <div class="bg-white shadow-sm px-4 py-3 rounded-4">
          <div class="fw-bold fs-4 text-success">+500</div>
          <small class="text-muted">Donaciones realizadas</small>
        </div>

        <div class="bg-white shadow-sm px-4 py-3 rounded-4">
          <div class="fw-bold fs-4 text-danger">24/7</div>
          <small class="text-muted">Disponibilidad</small>
        </div>

      </div>

      <a href="../public/index.php?controller=caso&action=catalogo"
         class="btn btn-primary btn-lg px-5 py-3 rounded-pill mt-5 shadow">

        <i class="bi bi-heart-fill me-2"></i>
        Explorar campañas

      </a>

    </div>

    <!-- CARD HERO -->
    <div class="col-lg-6">

      <div class="card border-0 shadow-lg overflow-hidden"
           style="border-radius:32px;">

        <div class="p-5 text-white"
             style="background: var(--grad);">

          <div class="d-flex justify-content-between align-items-center mb-5">

            <div>
              <small class="opacity-75">Campaña activa</small>

              <h3 class="fw-bold mb-0 mt-1">
                Ayuda para tratamiento médico
              </h3>
            </div>

            <div class="display-5">
              ❤️
            </div>

          </div>

          <div class="bg-white bg-opacity-10 rounded-4 p-4">

            <div class="d-flex justify-content-between mb-2">
              <span>Recaudado</span>
              <span class="fw-bold">S/ 8,500</span>
            </div>

            <div class="progress mb-3"
                 style="height:12px; border-radius:20px;">

              <div class="progress-bar bg-light"
                   style="width:70%">
              </div>

            </div>

            <div class="d-flex justify-content-between small opacity-75">
              <span>70% completado</span>
              <span>Meta: S/ 12,000</span>
            </div>

          </div>

          <div class="row text-center mt-5">

            <div class="col-4">
              <h4 class="fw-bold mb-0">128</h4>
              <small class="opacity-75">Donantes</small>
            </div>

            <div class="col-4">
              <h4 class="fw-bold mb-0">15</h4>
              <small class="opacity-75">Días</small>
            </div>

            <div class="col-4">
              <h4 class="fw-bold mb-0">100%</h4>
              <small class="opacity-75">Seguro</small>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- COMO FUNCIONA -->
<section class="container py-5">

  <div class="text-center mb-5">

    <span class="about-badge">
      Cómo funciona
    </span>

    <h2 class="fw-bold display-6 mt-3">
      Una experiencia simple y transparente
    </h2>

    <p class="text-muted mt-3">
      Diseñamos cada paso para generar confianza y facilidad al donar.
    </p>

  </div>

  <div class="row g-4">

    <div class="col-lg-3 col-md-6">

      <div class="card border-0 shadow-sm h-100 p-4 text-center"
           style="border-radius:24px;">

        <div class="display-4 mb-3">🧭</div>

        <h5 class="fw-bold">
          Explora campañas
        </h5>

        <p class="text-muted">
          Revisa casos reales y conoce sus historias antes de apoyar.
        </p>

      </div>

    </div>

    <div class="col-lg-3 col-md-6">

      <div class="card border-0 shadow-sm h-100 p-4 text-center"
           style="border-radius:24px;">

        <div class="display-4 mb-3">🔐</div>

        <h5 class="fw-bold">
          Inicia sesión
        </h5>

        <p class="text-muted">
          Accede de forma segura para gestionar tus donaciones.
        </p>

      </div>

    </div>

    <div class="col-lg-3 col-md-6">

      <div class="card border-0 shadow-sm h-100 p-4 text-center"
           style="border-radius:24px;">

        <div class="display-4 mb-3">💳</div>

        <h5 class="fw-bold">
          Realiza el pago
        </h5>

        <p class="text-muted">
          Dona mediante Mercado Pago con métodos confiables.
        </p>

      </div>

    </div>

    <div class="col-lg-3 col-md-6">

      <div class="card border-0 shadow-sm h-100 p-4 text-center"
           style="border-radius:24px;">

        <div class="display-4 mb-3">📄</div>

        <h5 class="fw-bold">
          Recibe confirmación
        </h5>

        <p class="text-muted">
          Obtén comprobantes y seguimiento de tus aportes.
        </p>

      </div>

    </div>

  </div>

</section>

<!-- TIPOS DE USUARIO -->
<section class="container py-5">

  <div class="row g-5 align-items-center">

    <div class="col-lg-6">

      <span class="about-badge">
        Estructura del sistema
      </span>

      <h2 class="fw-bold display-6 mt-3">
        Diseñado para todos los participantes
      </h2>

      <p class="text-muted lh-lg mt-4">
        La plataforma organiza la experiencia para visitantes, donantes y administradores,
        garantizando control, transparencia y facilidad de uso.
      </p>

      <div class="mt-5">

        <div class="d-flex mb-4">

          <div class="me-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                 style="width:55px; height:55px;">

              <i class="bi bi-globe"></i>

            </div>
          </div>

          <div>
            <h5 class="fw-bold mb-1">
              Público
            </h5>

            <p class="text-muted mb-0">
              Consulta campañas y visualiza avances de recaudación.
            </p>
          </div>

        </div>

        <div class="d-flex mb-4">

          <div class="me-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                 style="width:55px; height:55px;">

              <i class="bi bi-heart-fill"></i>

            </div>
          </div>

          <div>
            <h5 class="fw-bold mb-1">
              Donantes
            </h5>

            <p class="text-muted mb-0">
              Gestionan aportes, historial y certificados digitales.
            </p>
          </div>

        </div>

        <div class="d-flex">

          <div class="me-3">
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                 style="width:55px; height:55px;">

              <i class="bi bi-shield-check"></i>

            </div>
          </div>

          <div>
            <h5 class="fw-bold mb-1">
              Administradores
            </h5>

            <p class="text-muted mb-0">
              Aprueban campañas y supervisan transacciones.
            </p>
          </div>

        </div>

      </div>

    </div>

    <!-- CARD -->
    <div class="col-lg-6">

      <div class="card border-0 shadow-lg p-5"
           style="border-radius:30px;">

        <h4 class="fw-bold mb-4">
          Lo que puede ver un donador
        </h4>

        <div class="d-flex justify-content-between py-3 border-bottom">
          <span>Título del caso</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>

        <div class="d-flex justify-content-between py-3 border-bottom">
          <span>Historia completa</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>

        <div class="d-flex justify-content-between py-3 border-bottom">
          <span>Meta económica</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>

        <div class="d-flex justify-content-between py-3 border-bottom">
          <span>Progreso de recaudación</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>

        <div class="d-flex justify-content-between py-3">
          <span>Estadísticas y transparencia</span>
          <i class="bi bi-check-circle-fill text-success"></i>
        </div>

      </div>

    </div>

  </div>

</section>

<!-- SEGURIDAD -->
<section class="container py-5">

  <div class="card border-0 shadow-lg overflow-hidden"
       style="border-radius:32px;">

    <div class="row g-0">

      <div class="col-lg-6">

        <div class="p-5 h-100 text-white"
             style="background: var(--grad);">

          <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
            Seguridad
          </span>

          <h2 class="fw-bold display-6 mt-4">
            Tu confianza es prioridad
          </h2>

          <p class="opacity-75 mt-4 lh-lg">
            Aplicamos buenas prácticas modernas para proteger datos,
            pagos y sesiones de usuario.
          </p>

        </div>

      </div>

      <div class="col-lg-6 bg-white">

        <div class="p-5">

          <div class="d-flex align-items-start mb-4">

            <div class="me-3 text-primary fs-3">
              <i class="bi bi-lock-fill"></i>
            </div>

            <div>
              <h5 class="fw-bold">
                Contraseñas encriptadas
              </h5>

              <p class="text-muted mb-0">
                Protección segura de credenciales.
              </p>
            </div>

          </div>

          <div class="d-flex align-items-start mb-4">

            <div class="me-3 text-success fs-3">
              <i class="bi bi-shield-lock-fill"></i>
            </div>

            <div>
              <h5 class="fw-bold">
                Validación de sesión
              </h5>

              <p class="text-muted mb-0">
                Control de acceso en rutas privadas.
              </p>
            </div>

          </div>

          <div class="d-flex align-items-start mb-4">

            <div class="me-3 text-danger fs-3">
              <i class="bi bi-database-lock"></i>
            </div>

            <div>
              <h5 class="fw-bold">
                Protección SQL Injection
              </h5>

              <p class="text-muted mb-0">
                Consultas preparadas y sanitización.
              </p>
            </div>

          </div>

          <div class="d-flex align-items-start">

            <div class="me-3 text-warning fs-3">
              <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div>
              <h5 class="fw-bold">
                Transparencia total
              </h5>

              <p class="text-muted mb-0">
                Métricas visibles y seguimiento de campañas.
              </p>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- CTA -->
<section class="container py-5">

  <div class="text-center bg-white shadow-lg p-5"
       style="border-radius:32px;">

    <span class="about-badge">
      Empieza hoy
    </span>

    <h2 class="fw-bold display-6 mt-4">
      Cada donación puede cambiar una vida
    </h2>

    <p class="text-muted mt-4 mb-5">
      Explora campañas activas y apoya causas reales de manera segura.
    </p>

    <a href="../public/index.php?controller=caso&action=catalogo"
       class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow">

      <i class="bi bi-heart-fill me-2"></i>
      Explorar casos y donar

    </a>

  </div>

</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
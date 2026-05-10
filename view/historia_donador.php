<?php
$estilo_pagina = 'home';
require_once __DIR__ . '/layout/header.php';
?>

<section class="container py-5">
  <div class="row align-items-center gy-5">
    <div class="col-lg-6">
      <span class="about-badge">Historia del Donador</span>
      <h1 class="about-title mt-4">Cómo se construye una experiencia de donación segura y transparente</h1>
      <p class="about-text mt-4">
        Este sistema está diseñado para conectar a personas buenas con causas reales.
        La experiencia se organiza en tres niveles: público, usuario registrado y administrador.
      </p>
      <p class="about-text">
        Desde la vista pública, los visitantes conocen campañas activas, revisan el avance de recaudación y reciben la información necesaria para confiar en el proceso.
      </p>
      <a href="../public/index.php?controller=caso&action=catalogo" class="btn btn-primary btn-lg mt-4">Ver campañas disponibles</a>
    </div>

    <div class="col-lg-6">
      <div class="card border-0 shadow-sm p-5" style="border-radius: 24px; background: rgba(255,255,255,0.9);">
        <h4 class="fw-bold mb-3">Flujo del donador</h4>
        <ol class="list-group list-group-flush">
          <li class="list-group-item bg-transparent">1. El visitante conoce las campañas desde la página pública.</li>
          <li class="list-group-item bg-transparent">2. Registra su cuenta o inicia sesión para acceder al catálogo completo.</li>
          <li class="list-group-item bg-transparent">3. Selecciona un caso social y revisa la historia completa.</li>
          <li class="list-group-item bg-transparent">4. Elige monto y método de pago para donar con confianza.</li>
          <li class="list-group-item bg-transparent">5. Recibe confirmación, correo y certificado de donación.</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="container py-5 bg-light rounded-4 shadow-sm" id="como">
  <div class="row g-5 align-items-center">
    <div class="col-lg-6">
      <h2 class="fw-bold">Estructura del sistema</h2>
      <p class="mt-3 text-muted lh-lg">
        El sitio está pensado para tres tipos de usuarios:
      </p>
      <ul class="list-unstyled mt-4">
        <li class="mb-3"><strong>Público:</strong> consulta campañas, ve el progreso y accede a información general.</li>
        <li class="mb-3"><strong>Usuario registrado:</strong> dona, revisa su historial, gestiona perfil y recibe certificados.</li>
        <li class="mb-3"><strong>Administrador:</strong> aprueba casos, publica campañas y verifica transacciones.</li>
      </ul>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 24px; background: white;">
        <h5 class="section-title mb-3">Qué ve el donador</h5>
        <p class="text-muted">Información clara con:</p>
        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-transparent">Título del caso</li>
          <li class="list-group-item bg-transparent">Descripción completa y evidencia</li>
          <li class="list-group-item bg-transparent">Monto objetivo y monto recaudado</li>
          <li class="list-group-item bg-transparent">Progreso y número de donantes</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="container py-5">
  <div class="text-center mb-5">
    <span class="badge bg-primary px-3 py-2">Proceso de donación</span>
    <h2 class="fw-bold mt-3">Pasos para donar</h2>
    <p class="text-muted">Una experiencia de pago simple, funcional y segura.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4 h-100 border-0 shadow-sm">
        <div class="fs-1 mb-3">🧭</div>
        <h5 class="fw-bold">Seleccionar campaña</h5>
        <p class="text-muted">Escoge el caso que quieres apoyar y revisa sus detalles antes de dar el paso.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 h-100 border-0 shadow-sm">
        <div class="fs-1 mb-3">💳</div>
        <h5 class="fw-bold">Pagar seguro</h5>
        <p class="text-muted">Integramos pasarela de pago segura con Mercado Pago y métodos locales.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 h-100 border-0 shadow-sm">
        <div class="fs-1 mb-3">📄</div>
        <h5 class="fw-bold">Confirmación y certificado</h5>
        <p class="text-muted">El sistema genera comprobante de donación, correo y certificado de apoyo.</p>
      </div>
    </div>
  </div>
</section>

<section class="container py-5 bg-light rounded-4">
  <div class="row g-4 align-items-center">
    <div class="col-lg-7">
      <h2 class="fw-bold">Seguridad y confianza</h2>
      <p class="mt-3 text-muted lh-lg">
        Para cuidar tu donación implementamos:
      </p>
      <ul class="list-unstyled mt-4">
        <li class="mb-3"><strong>Contraseñas encriptadas</strong> para proteger identidad.</li>
        <li class="mb-3"><strong>Validación de sesión</strong> en cada ruta segura.</li>
        <li class="mb-3"><strong>Consultas preparadas</strong> para evitar SQL Injection.</li>
        <li class="mb-3"><strong>Rendimiento transparente</strong> con métricas y estadísticas visibles.</li>
      </ul>
    </div>
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        <h5 class="section-title mb-3">Transparencia</h5>
        <p class="text-muted">El donante puede ver estadísticas reales como:</p>
        <ul class="list-group list-group-flush">
          <li class="list-group-item bg-transparent">Total recaudado</li>
          <li class="list-group-item bg-transparent">Número de donantes</li>
          <li class="list-group-item bg-transparent">Campañas más apoyadas</li>
          <li class="list-group-item bg-transparent">Historial personal de donaciones</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="container py-5 text-center">
  <div class="bg-white rounded-4 shadow-sm p-5">
    <h3 class="fw-bold">¿Listo para apoyar una causa?</h3>
    <p class="text-muted mt-3 mb-4">Navega las campañas y dona con la seguridad de un sistema construido para la transparencia social.</p>
    <a href="../public/index.php?controller=caso&action=catalogo" class="btn btn-primary btn-lg">Explorar casos y donar</a>
  </div>
</section>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

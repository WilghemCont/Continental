<?php 
// 1. Iniciamos sesión y cargamos el encabezado dinámico
$estilo_pagina = 'home';
require_once 'layout/header.php'; 

$donarUrl = isset($_SESSION["idlogin"]) ? 'donar.php' : 'login.php';
?>

<section class="container py-5" id="inicio">
  <div id="bannerCarousel" class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="banner-slide d-flex align-items-center">
          <div class="container text-white">
            <h1 class="fw-bold display-5">Convierte tu idea en impacto real</h1>
            <p class="lead">Publica tu proyecto social y conecta con benefactores.</p>
            <a href="registro.php" class="btn btn-light btn-lg mt-3 shadow">Publicar proyecto</a>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="banner-slide bg-2 d-flex align-items-center">
          <div class="container text-white">
            <h1 class="fw-bold display-5">Apoya causas reales</h1>
            <p class="lead">Tu donación se canaliza con seguimiento y registro.</p>
            <a href="#campanas" class="btn btn-light btn-lg mt-3 shadow">Ver campañas</a>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="banner-slide bg-3 d-flex align-items-center">
          <div class="container text-white">
            <h1 class="fw-bold display-5">Pasarela lista para integrar</h1>
            <p class="lead">Mercado Pago queda preparado para conectarse con el flujo del donante.</p>
            <a href="<?= $donarUrl ?>" class="btn btn-light btn-lg mt-3 shadow">Ir a donar</a>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>

<section class="container py-5" id="sobre-nosotros">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="about-card text-center p-5">
        <span class="about-badge">Sobre nosotros</span>
        <h3 class="about-title mt-3">Una plataforma con propósito social</h3>
        <p class="about-text mt-3">
          SocialFunding busca acercar proyectos sociales a personas dispuestas a apoyar con aportes trazables.
        </p>
        <p class="about-text">
          La plataforma conecta campañas, donantes registrados y seguimiento de transacciones en un solo flujo.
        </p>
        <div class="about-icon mt-4"><i class="bi bi-heart-pulse"></i></div>
      </div>
    </div>
  </div>
</section>

<section class="stats-section py-5">
  <div class="container">
    <div class="row text-center g-4 justify-content-center">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <h2 class="stat-num" data-target="3400">0</h2>
          <p>Beneficiarios</p>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="stat-card">
          <h2 class="stat-num" data-target="128">0</h2>
          <p>Campañas</p>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="stat-card">
          <h2 class="stat-num" data-target="47">0</h2>
          <p>ONGs</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-5" id="campanas">
  <div class="text-center mb-5">
    <span class="badge bg-primary px-3 py-2">Causas activas</span>
    <h2 class="fw-bold mt-3">Campañas destacadas</h2>
    <p class="text-muted">La pasarela puede conectarse luego desde estas tarjetas o desde la página del donante.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 text-center">
        <div class="card-body">
          <div class="fs-1">💧</div>
          <span class="badge bg-danger mb-2">Salud</span>
          <h5 class="fw-bold">Agua potable</h5>
          <div class="progress my-3"><div class="progress-bar bg-success" style="width:64%"></div></div>
          <div class="d-flex justify-content-between align-items-center">
            <small>64%</small>
            <strong>S/ 9,600</strong>
          </div>
          <a href="<?= $donarUrl ?>" class="btn btn-outline-primary mt-3">Donar</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 text-center">
        <div class="card-body">
          <div class="fs-1">📘</div>
          <span class="badge bg-primary mb-2">Educación</span>
          <h5 class="fw-bold">Biblioteca digital</h5>
          <div class="progress my-3"><div class="progress-bar bg-success" style="width:81%"></div></div>
          <div class="d-flex justify-content-between align-items-center">
            <small>81%</small>
            <strong>S/ 6,480</strong>
          </div>
          <a href="<?= $donarUrl ?>" class="btn btn-outline-primary mt-3">Donar</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0 text-center">
        <div class="card-body">
          <div class="fs-1">💼</div>
          <span class="badge bg-warning text-dark mb-2">Empleo</span>
          <h5 class="fw-bold">Emprendimiento</h5>
          <div class="progress my-3"><div class="progress-bar bg-success" style="width:43%"></div></div>
          <div class="d-flex justify-content-between align-items-center">
            <small>43%</small>
            <strong>S/ 2,150</strong>
          </div>
          <a href="<?= $donarUrl ?>" class="btn btn-outline-primary mt-3">Donar</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container py-5" id="testimonios">
  <div class="text-center mb-5">
    <span class="badge bg-success px-3 py-2">Historias reales</span>
    <h2 class="fw-bold mt-3">Lo que dicen nuestros usuarios</h2>
    <p class="text-muted">Comentarios enfocados en confianza, transparencia y apoyo social.</p>
  </div>

  <div id="testCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="card border-0 shadow-lg p-4 mx-auto text-center" style="max-width:700px;">
          <div class="text-warning fs-4 mb-2">★★★★★</div>
          <p class="fs-5 text-muted">"Gracias a SocialFunding pudimos financiar medicamentos para nuestra comunidad."</p>
          <div class="mt-3">
            <strong>Maria Torres</strong><br>
            <small class="text-muted">Beneficiaria</small>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="card border-0 shadow-lg p-4 mx-auto text-center" style="max-width:700px;">
          <div class="text-warning fs-4 mb-2">★★★★★</div>
          <p class="fs-5 text-muted">"La trazabilidad de la donación me da confianza para seguir apoyando."</p>
          <div class="mt-3">
            <strong>Carlos Quispe</strong><br>
            <small class="text-muted">Donante</small>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="card border-0 shadow-lg p-4 mx-auto text-center" style="max-width:700px;">
          <div class="text-warning fs-4 mb-2">★★★★★</div>
          <p class="fs-5 text-muted">"La campaña pudo mostrarse y recibir aportes de forma más ordenada."</p>
          <div class="mt-3">
            <strong>Ana Huanca</strong><br>
            <small class="text-muted">Impulsora social</small>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#testCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#testCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
    </button>
  </div>
</section>

<section class="container-fluid py-5 socios-section position-relative overflow-hidden">
  <div class="text-center mb-5">
    <span class="badge bg-primary px-3 py-2 shadow-sm">Alianzas</span>
    <h2 class="fw-bold mt-3">Nuestros socios</h2>
    <p class="text-muted">Organizaciones que impulsan el ecosistema social.</p>
  </div>

  <div class="socios-slider">
    <div class="socios-track">
      <div class="socio-item">Minsa</div>
      <div class="socio-item">Minedu</div>
      <div class="socio-item">UNICEF</div>
      <div class="socio-item">Cruz Roja</div>
      <div class="socio-item">OPS</div>
      <div class="socio-item">PNUD</div>
      <div class="socio-item">ONG Vida</div>
      <div class="socio-item">Fe y Alegría</div>
      <div class="socio-item">Minsa</div>
      <div class="socio-item">Minedu</div>
      <div class="socio-item">UNICEF</div>
      <div class="socio-item">Cruz Roja</div>
      <div class="socio-item">OPS</div>
      <div class="socio-item">PNUD</div>
      <div class="socio-item">ONG Vida</div>
      <div class="socio-item">Fe y Alegría</div>
    </div>
  </div>
</section>

<?php 
require_once 'layout/footer.php'; 
?>
</body>
</html>
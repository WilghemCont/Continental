<?php 
// 1. Iniciamos sesión y cargamos el encabezado dinámico
require_once 'layout/header.php'; 
?>

<section class="container py-5">
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
            <p class="lead">Tu donación puede cambiar vidas.</p>
            <a href="#" class="btn btn-light btn-lg mt-3 shadow">Ver campañas</a>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="banner-slide bg-3 d-flex align-items-center">
          <div class="container text-white">
            <h1 class="fw-bold display-5">Transparencia total</h1>
            <p class="lead">Seguimiento en tiempo real de tu ayuda.</p>
            <a href="#" class="btn btn-light btn-lg mt-3 shadow">Cómo funciona</a>
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

<section class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="about-card text-center p-5">
        <span class="about-badge">Sobre nosotros</span>
        <h3 class="about-title mt-3">Una plataforma con propósito social</h3>
        <p class="about-text mt-3">
          SocialFunding nació para eliminar la barrera económica que frena proyectos 
          con impacto real en salud, educación y desarrollo humano.
        </p>
        <p class="about-text">
          Conectamos a quienes tienen la voluntad con quienes tienen los recursos.
        </p>
        <div class="about-icon mt-4">🤝</div>
      </div>
    </div>
  </div>
</section>

<section class="stats-section py-5">
  <div class="container">
    <div class="row text-center g-4">
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
          <h2 class="stat-num" data-target="92000" data-prefix="S/ ">0</h2>
          <p>Donado</p>
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
    <p class="text-muted">Apoya proyectos reales que están cambiando vidas</p>
  </div>

  <div id="campCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 text-center">
              <div class="card-body">
                <div class="fs-1">💧</div>
                <span class="badge bg-danger mb-2">Salud</span>
                <h5 class="fw-bold">Agua potable</h5>
                <div class="progress my-3">
                  <div class="progress-bar bg-success" style="width:64%"></div>
                </div>
                <div class="d-flex justify-content-between">
                  <small>64%</small>
                  <strong>S/ 9,600</strong>
                </div>
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#campCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#campCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
    </button>
  </div>
</section>

<?php 
// Cargamos el pie de página
require_once 'layout/footer.php'; 
?>

<script src="./../assets/js/home.js"></script>

</body>
</html>
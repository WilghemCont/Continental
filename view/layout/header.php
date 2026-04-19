<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SocialFunding — Crowdfunding Social</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="./../assets/css/index.css"/>
</head>
<body>

    <!-- TOPBAR -->
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

<!-- NAVBAR -->
<nav id="navbar" class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">

  <div class="container">

    <!-- LOGO -->
    <a class="navbar-brand fw-bold" href="#">
      Social<span class="text-primary">Funding</span>
    </a>

    <!-- BOTÓN MOBILE -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse" id="menu">

      <!-- LINKS -->
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
          <a class="nav-link" href="#como">Cómo funciona</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#campanas">Campañas</a>
        </li>
      </ul>

      <!-- BOTONES -->
      <div class="d-flex gap-2">
        <a href="login.html" class="btn btn-outline-primary">Login</a>
        <a href="registro.html" class="btn btn-primary">Registro</a>
      </div>

    </div>

  </div>

</nav>
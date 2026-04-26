<footer class="footer-premium text-white pt-5">

  <div class="container">
    <div class="row g-4">

      <!-- BRAND -->
      <div class="col-md-4">
        <h4 class="fw-bold">
          Social<span class="text-info">Funding</span>
        </h4>
        <p class="text-light opacity-75">
          Impulsamos proyectos sociales que generan impacto real en Latinoamérica.
        </p>

        <div class="d-flex gap-3 mt-3">
          <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
        </div>
      </div>

      <!-- LINKS -->
      <div class="col-6 col-md-2">
        <h6 class="fw-bold">Navegación</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Inicio</a></li>
          <li><a href="#campanas">Campañas</a></li>
          <li><a href="#como">Cómo funciona</a></li>
          <li><a href="#">Sobre nosotros</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6 class="fw-bold">Contacto</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">contacto@socialfunding.pe</a></li>
          <li><a href="#">WhatsApp</a></li>
          <li><a href="#">Soporte</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-3">
        <h6 class="fw-bold">Legal</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Términos</a></li>
          <li><a href="#">Privacidad</a></li>
          <li><a href="#">Cookies</a></li>
        </ul>
      </div>

    </div>

    <hr class="border-light opacity-25 my-4">

    <div class="d-flex flex-column flex-md-row justify-content-between pb-3 small">
      <span class="opacity-75">© 2025 SocialFunding</span>
      <span class="opacity-75">Hecho con 💙 en Perú</span>
    </div>
  </div>

</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  

    <?php 
      $paginaActual = basename($_SERVER['PHP_SELF']);
      if ($paginaActual == 'login.php') {
          echo '<script src="../assets/js/login.js"></script>';
      }
      if ($paginaActual == 'home.php') {
          echo '<script src="../assets/js/home.js"></script>';
      }
      if ($paginaActual == 'ingresos.php') {
          echo '<script src="../assets/js/ingresos.js"></script>';
      }
      if ($paginaActual == 'bandeja.php') {
          echo '<script src="../assets/js/casos.js"></script>';
      }
      if ($paginaActual == 'registro.php'){
         echo '<script src="./../assets/js/registro.js"></script>';
      }
      if ($paginaActual == 'donantes.php'){
         echo '<script src="../assets/js/donantes.js"></script>';
      }
      if ($paginaActual == 'header.php'){
         echo '<script src="./../assets/js/main.js"></script>';
      }
      
    ?>
        
    <script>
        // Pequeño script de depuración rápida
        console.log("Bootstrap cargado:", typeof bootstrap !== 'undefined');
    </script>
</body>
</html>
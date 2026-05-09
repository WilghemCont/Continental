<?php require_once 'layout/header.php'; ?>

<div class="container py-5">

  <!-- VOLVER -->
  <div class="mb-4">
    <a href="?page=estadisticas"
       class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
      ← Volver al Dashboard
    </a>
  </div>

  <!-- CONTENIDO -->
  <div class="row g-4 mb-5">

    <!-- INFORMACIÓN -->
    <div class="col-lg-6">

      <div class="about-card h-100 p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

          <h3 class="fw-bold m-0">
            📋 Información del Caso
          </h3>

          <span class="badge rounded-pill px-3 py-2
            <?= $caso['estado']==='completado'
              ? 'bg-success'
              : ($caso['estado']==='cerrado'
                ? 'bg-warning text-dark'
                : 'bg-primary') ?>">

            <?= ucfirst($caso['estado']) ?>

          </span>

        </div>

        <h2 class="about-title mb-3">
          <?= htmlspecialchars($caso['titulo']) ?>
        </h2>

        <p class="about-text mb-4">
          <?= nl2br(htmlspecialchars($caso['descripcion'])) ?>
        </p>

      </div>

    </div>

    <!-- PROGRESO -->
    <div class="col-lg-6">

      <div class="about-card h-100 p-4">

        <h3 class="fw-bold mb-4">
          📊 Progreso de Recaudación
        </h3>

        <div class="text-center mb-4">

          <div class="stat-num">
            <?= $pct ?>%
          </div>

          <p class="about-text">
            de la meta alcanzado
          </p>

        </div>

        <div class="progress mb-4" style="height:12px">

          <div class="progress-bar bg-success"
               style="width:<?= $pct ?>%">
          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- TABLA -->
  <div class="about-card p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

      <div>
        <h3 class="fw-bold">
          💰 Donaciones Recibidas
        </h3>

        <p class="text-muted">
          <?= count($donaciones) ?> donaciones
        </p>
      </div>

      <a href="?page=donaciones&action=nueva"
         class="btn btn-success rounded-pill px-4 shadow-sm">

         + Nueva Donación

      </a>

    </div>

    <div class="table-responsive">

      <table class="table table-hover align-middle">

        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Donador</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Fecha</th>
          </tr>
        </thead>

        <tbody>

        <?php foreach($donaciones as $d): ?>

          <tr>

            <td>#<?= $d['id'] ?></td>

            <td>
              <div class="fw-semibold">
                <?= htmlspecialchars($d['donador_nombre']) ?>
              </div>

              <small class="text-muted">
                <?= htmlspecialchars($d['donador_email']) ?>
              </small>
            </td>

            <td class="text-success fw-bold">
              S/ <?= number_format($d['monto'],2) ?>
            </td>

            <td>

              <span class="badge
                <?= $d['estado']==='verificado'
                  ? 'bg-success'
                  : 'bg-warning text-dark' ?>">

                <?= ucfirst($d['estado']) ?>

              </span>

            </td>

            <td>
              <?= date('d/m/Y',strtotime($d['fecha_donacion'])) ?>
            </td>

          </tr>

        <?php endforeach; ?>

        </tbody>

      </table>

    </div>

  </div>

</div>

<script src="views/estadisticas/detalle.js"></script>

<?php require_once 'layout/footer.php'; ?>
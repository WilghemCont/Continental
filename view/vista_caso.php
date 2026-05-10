<?php require_once 'layout/header.php'; ?>

<?php
// =========================
// DATOS
// =========================
$meta = $caso['monto_requerido'] ?? 0;
$recaudado = $caso['monto_recaudado'] ?? 0;

$porcentaje = ($meta > 0)
    ? min(($recaudado / $meta) * 100, 100)
    : 0;

$faltante = $meta - $recaudado;
?>

<div class="container py-5 fade-up">

    <div class="row g-5">

        <!-- =========================
             IZQUIERDA
        ========================== -->
        <div class="col-lg-7">

            <!-- TITULO -->
            <div class="mb-4">
                
                <h1 class="fw-bold display-6 text-dark">
                    <?= $caso['titulo_publico'] ?? $caso['titulo_caso'] ?>
                </h1>

            </div>

            <!-- IMAGEN -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4"
                 style="border-radius: 24px;">

                <img
                    src="<?= "http://localhost/CONTINENTAL/assets/uploads/fotos/" . $caso['foto_beneficiario'] ?>"
                    class="img-fluid"
                    style="width:100%; height:480px; object-fit:cover;"
                    alt="Caso social"
                    onerror="this.src='http://localhost/CONTINENTAL/assets/img/placeholder.jpg'"
                >
            </div>

            <!-- HISTORIA -->
            <div class="card border-0 shadow-sm p-4 p-md-5"
                 style="border-radius:24px;">

                <h4 class="fw-bold mb-4">
                    Historia del caso
                </h4>

                <p class="text-muted lh-lg fs-6">
                    <?= nl2br($caso['descripcion_publica'] ?? $caso['descripcion']) ?>
                </p>

            </div>



        </div>

        <!-- =========================
             DERECHA
        ========================== -->
        <div class="col-lg-5">

            <div class="sticky-top" style="top:100px;">

                <div class="card border-0 shadow-lg p-4 p-md-5"
                     style="border-radius:30px;">

                    <!-- RECAUDADO -->
                    <div class="mb-4">

                        <h2 class="fw-bold text-success mb-1">
                            S/ <?= number_format($recaudado, 2) ?>
                        </h2>

                        <p class="text-muted mb-0">
                            recaudados de S/ <?= number_format($meta, 2) ?>
                        </p>

                    </div>

                    <!-- PROGRESO -->
                    <div class="mb-4">

                        <div class="progress"
                             style="height:14px; border-radius:20px;">

                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                 style="width: <?= $porcentaje ?>%">
                            </div>

                        </div>

                        <div class="d-flex justify-content-between mt-2">

                            <small class="fw-bold text-success">
                                <?= round($porcentaje) ?>% alcanzado
                            </small>

                            <small class="text-muted">
                                Faltan S/ <?= number_format($faltante, 2) ?>
                            </small>

                        </div>

                    </div>

                    <!-- INFO -->
                    <div class="row text-center mb-4">

                    
                            <h5 class="fw-bold mb-1">
                                <?= $cantidadDonaciones ?? 0 ?>
                            </h5>

                            <small class="text-muted">
                                Donaciones
                            </small>
                        

                    </div>

                    <!-- BOTÓN DONAR -->
                    <a href="index.php?controller=donacion&action=crear&id=<?= $caso['id'] ?>"
                    class="btn btn-primary mt-3 w-100
                    <?= ($recaudado >= $meta) ? 'disabled' : '' ?>">

                        <i class="bi bi-heart-fill me-2"></i>
                        DONAR AHORA

                    </a>

                    <!-- COMPARTIR -->
                    <button class="btn btn-primary mt-3 w-100"
                            onclick="compartirCaso()">

                        <i class="bi bi-share-fill me-2"></i>
                        COMPARTIR

                    </button>

                    <a href="index.php?controller=donacion&action=certificado&id=<?= $ultimaDonacionId ?>"
                        target="_blank"
                        class="btn btn-danger mt-3 w-100">

                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                            GENERAR PDF

                        </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
function compartirCaso() {

    if (navigator.share) {

        navigator.share({
            title: "<?= addslashes($caso['titulo_publico'] ?? $caso['titulo_caso']) ?>",
            text: "Apoya este caso social",
            url: window.location.href
        });

    } else {

        navigator.clipboard.writeText(window.location.href);

        alert("Enlace copiado al portapapeles");

    }
}
</script>

<?php require_once 'layout/footer.php'; ?>
<?php

require_once __DIR__ . '/../models/donaciones.php';

if (!isset($_GET['id'])) {
    die("Donación no encontrada");
}

$id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| INSTANCIAR MODELO REAL DEL PROYECTO
|--------------------------------------------------------------------------
*/

$modelo = new DonacionModel();

/*
|--------------------------------------------------------------------------
| OBTENER DONACIÓN
|--------------------------------------------------------------------------
*/

$donacion = $modelo->obtenerPorId($id);

if (!$donacion) {
    die("Certificado no disponible");
}

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/layout/header.php';

/*
|--------------------------------------------------------------------------
| CÓDIGO ÚNICO DEL CERTIFICADO
|--------------------------------------------------------------------------
*/

$codigoCertificado =
    'CERT-' .
    strtoupper(
        substr(
            md5($donacion['id'] . $donacion['fecha']),
            0,
            8
        )
    );
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <!-- BOTÓN VOLVER -->

            <div class="mb-4">

                <a href="../public/index.php?controller=donacion&action=historial"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-2"></i>

                    Volver al historial

                </a>

            </div>

            <!-- CERTIFICADO -->

            <div class="card border-0 shadow-lg"
                 style="border-radius:24px; overflow:hidden;">

                <!-- HEADER -->

                <div class="text-white p-4"
                     style="background:linear-gradient(135deg,var(--azul),var(--verde));">

                    <div class="text-center">

                        <div class="display-6 mb-2">🏆</div>

                        <h2 class="fw-bold mb-1">
                            Certificado de Donación
                        </h2>

                        <p class="mb-0 opacity-75">
                            SocialFunding — Transparencia y Impacto Social
                        </p>

                    </div>

                </div>

                <!-- BODY -->

                <div class="card-body p-5">

                    <div class="row g-4">

                        <div class="col-lg-8">

                            <h4 class="fw-bold mb-4 text-center">
                                Reconocimiento por tu Contribución
                            </h4>

                            <p class="lead text-center mb-4">
                                Gracias por apoyar causas sociales mediante SocialFunding.
                            </p>

                            <div class="row g-3">

                                <!-- DONANTE -->

                                <div class="col-md-6">

                                    <div class="p-3 bg-light rounded-4">

                                        <small class="text-muted d-block fw-bold text-uppercase">
                                            Donante
                                        </small>

                                        <h5 class="mb-0">
                                            <?= htmlspecialchars($donacion['nombre']) ?>
                                        </h5>

                                    </div>

                                </div>

                                <!-- MONTO -->

                                <div class="col-md-6">

                                    <div class="p-3 bg-light rounded-4">

                                        <small class="text-muted d-block fw-bold text-uppercase">
                                            Monto Donado
                                        </small>

                                        <h5 class="mb-0 text-success">
                                            S/ <?= number_format($donacion['monto'], 2) ?>
                                        </h5>

                                    </div>

                                </div>

                                <!-- FECHA -->

                                <div class="col-md-6">

                                    <div class="p-3 bg-light rounded-4">

                                        <small class="text-muted d-block fw-bold text-uppercase">
                                            Fecha
                                        </small>

                                        <h6 class="mb-0">

                                            <?= date(
                                                'd/m/Y',
                                                strtotime($donacion['fecha'])
                                            ) ?>

                                        </h6>

                                    </div>

                                </div>

                                <!-- CÓDIGO -->

                                <div class="col-md-6">

                                    <div class="p-3 bg-light rounded-4">

                                        <small class="text-muted d-block fw-bold text-uppercase">
                                            Código
                                        </small>

                                        <h6 class="mb-0 text-primary font-monospace">

                                            <?= $codigoCertificado ?>

                                        </h6>

                                    </div>

                                </div>

                            </div>

                            <!-- MENSAJE -->

                            <?php if(!empty($donacion['mensaje'])): ?>

                                <div class="mt-4 p-3 bg-light rounded-4">

                                    <small class="text-muted d-block fw-bold text-uppercase">
                                        Mensaje
                                    </small>

                                    <p class="mb-0 fst-italic">

                                        "<?= htmlspecialchars($donacion['mensaje']) ?>"

                                    </p>

                                </div>

                            <?php endif; ?>

                        </div>

                        <!-- PANEL DERECHO -->

                        <div class="col-lg-4 text-center">

                            <div class="mb-4">

                                <div class="bg-primary bg-opacity-10 rounded-circle
                                            d-inline-flex align-items-center justify-content-center"
                                     style="width:120px;height:120px;">

                                    <i class="bi bi-heart-fill text-primary display-4"></i>

                                </div>

                            </div>

                            <h5 class="fw-bold mb-3">
                                ¡Gracias por tu solidaridad!
                            </h5>

                            <p class="text-muted small">

                                Tu apoyo genera un impacto real en la sociedad.

                            </p>

                            <div class="border-top pt-3 mt-4">

                                <small class="text-muted d-block">
                                    Método de pago
                                </small>

                                <span class="badge bg-success fs-6 px-3 py-2">

                                    <?= htmlspecialchars($donacion['metodo']) ?>

                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->

                    <div class="border-top mt-5 pt-4">

                        <div class="row text-center">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Emitido por
                                </small>

                                <strong>SocialFunding</strong>

                            </div>

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Fecha emisión
                                </small>

                                <strong><?= date('d/m/Y') ?></strong>

                            </div>

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    ID Donación
                                </small>

                                <strong>#<?= $donacion['id'] ?></strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- BOTONES -->

            <div class="row mt-4 g-3">

                <div class="col-md-6">

                    <button onclick="window.print()"
                            class="btn btn-primary w-100">

                        <i class="bi bi-printer me-2"></i>

                        Imprimir Certificado

                    </button>

                </div>

                <div class="col-md-6">

                    <button onclick="window.location.href='../view/home.php'"
                            class="btn btn-outline-primary w-100">

                        Volver al inicio

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
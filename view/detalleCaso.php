<?php require_once 'layout/header.php'; ?>

<div class="container mt-4">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Detalle del Caso
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-8">

                    <h3><?= $caso["titulo_caso"] ?></h3>

                    <p class="text-muted">
                        <?= $caso["clasificacion"] ?>
                    </p>

                    <hr>

                    <h5>Descripción</h5>

                    <p>
                        <?= $caso["descripcion"] ?>
                    </p>

                    <hr>

                    <h5>Beneficiario</h5>

                    <p>
                        <?= $caso["nombre_beneficiario"] ?>
                    </p>

                    <p>
                        DNI: <?= $caso["dni_beneficiario"] ?>
                    </p>

                    <p>
                        Edad: <?= $caso["edad_beneficiario"] ?>
                    </p>

                </div>

                <div class="col-md-4">

                    <div class="card bg-light border-0">

                        <div class="card-body">

                            <h5>Estado</h5>

                            <span class="badge bg-primary">
                                <?= strtoupper($caso["estado_evaluacion"]) ?>
                            </span>

                            <hr>

                            <h5>Monto requerido</h5>

                            <h3 class="text-success">
                                S/. <?= number_format($caso["monto_requerido"], 2) ?>
                            </h3>

                            <hr>

                            <h5>Ubicación</h5>

                            <p><?= $caso["ubicacion"] ?></p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once 'layout/footer.php'; ?>
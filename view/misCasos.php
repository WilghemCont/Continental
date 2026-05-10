<?php require_once 'layout/header.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>Mis Casos Sociales</h3>

        <a href="../controllers/Ongcontroller.php?action=registrar"
           class="btn btn-primary">

            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Caso
        </a>

    </div>

    <div class="card shadow border-0">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Clasificación</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach($casos as $c): ?>

                    <tr>

                        <td><?= $c["id"] ?></td>

                        <td><?= $c["titulo_caso"] ?></td>

                        <td><?= ucfirst($c["clasificacion"]) ?></td>

                        <td>
                            S/. <?= number_format($c["monto_requerido"], 2) ?>
                        </td>

                        <td>

                            <span class="badge bg-info">
                                <?= strtoupper($c["estado_evaluacion"]) ?>
                            </span>

                        </td>

                        <td><?= $c["fecha_registro"] ?></td>

                        <td>

                            <a href="../controllers/Ongcontroller.php?action=detalle&id=<?= $c["id"] ?>"
                               class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-eye"></i>
                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
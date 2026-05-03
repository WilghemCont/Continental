<?php require_once __DIR__ . "/../../helpers/ui_helper.php"; ?>

<table class="table align-middle table-hover">

    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>ONG</th>
            <th>Beneficiario</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

        <?php if(empty($casos)): ?>
            <tr>
                <td colspan="6" class="text-center">No hay resultados</td>
            </tr>
        <?php else: ?>

            <?php foreach ($casos as $c): ?>

                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['titulo_caso'] ?></td>
                    <td><?= $c['nombre_ong'] ?></td>
                    <td><?= $c['nombre_beneficiario'] ?></td>

                    <td><?= renderEstado($c) ?></td>

                    <td><?= renderAcciones($c) ?></td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </tbody>

</table>
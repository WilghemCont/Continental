<?php require_once __DIR__ . "/layouts/header.php"; ?>

<div class="container py-4 fade-up">

    <div class="mb-4">
        <h2 class="titulo"><?= $caso['titulo_caso'] ?></h2>
        <p class="subtitulo">Detalle completo del caso social</p>
    </div>

    <!-- INFO -->
    <div class="card p-4 mb-4">

        <p><strong>ONG:</strong> <?= $caso['ong_nombre'] ?></p>
        <p><strong>Beneficiario:</strong> <?= $caso['nombre_beneficiario'] ?></p>
        <p><strong>DNI:</strong> <?= $caso['dni_beneficiario'] ?></p>
        <p><strong>Edad:</strong> <?= $caso['edad_beneficiario'] ?></p>
        <p><strong>Ubicación:</strong> <?= $caso['ubicacion'] ?></p>

        <p><strong>Descripción:</strong></p>
        <p><?= $caso['descripcion'] ?></p>

        <p>
            <strong>Estado:</strong>
            <span class="badge bg-info"><?= ucfirst($caso['estado']) ?></span>
        </p>

    </div>

    <!-- DOCUMENTOS -->
    <div class="card p-4 mb-4">

        <h5 class="section-title mb-3">Documentos</h5>

        <?php if(empty($documentos)): ?>
            <p>No hay documentos registrados.</p>
        <?php else: ?>

            <div class="row g-3">

                <?php foreach($documentos as $doc): ?>

                    <div class="col-md-4">

                        <div class="card p-2">

                            <p class="small"><?= $doc['nombre_archivo'] ?></p>

                            <?php if(str_contains($doc['tipo_mime'], 'image')): ?>
                                <img src="../<?= $doc['ruta_archivo'] ?>" class="img-fluid rounded">
                            
                            <?php elseif($doc['tipo_mime'] === 'application/pdf'): ?>
                                <iframe src="../<?= $doc['ruta_archivo'] ?>" width="100%" height="200"></iframe>
                            
                            <?php else: ?>
                                <a href="../<?= $doc['ruta_archivo'] ?>" target="_blank" class="btn btn-sm btn-primary">
                                    Descargar
                                </a>
                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

    <!-- ACCIONES -->
    <div class="card p-4">

        <h5 class="section-title mb-3">Acciones</h5>

        <div class="d-flex gap-2">

            <?php if(in_array($caso['estado'], ['pendiente','observado'])): ?>
                <a href="index.php?controller=evaluacion&action=ver&id=<?= $caso['id'] ?>"
                   class="btn btn-warning">
                   Evaluar
                </a>
            <?php endif; ?>

            <?php if($caso['estado'] === 'aprobado'): ?>
                <a href="index.php?controller=publicacion&action=crear&id=<?= $caso['id'] ?>"
                   class="btn btn-success">
                   Publicar
                </a>
            <?php endif; ?>

            <?php if($caso['estado'] === 'publicado'): ?>
                <a href="index.php?controller=cierre&action=ver&id=<?= $caso['id'] ?>"
                   class="btn btn-secondary">
                   Cerrar
                </a>
            <?php endif; ?>

            <a href="index.php?controller=caso&action=dashboard"
               class="btn btn-outline-secondary">
               Volver
            </a>

        </div>

    </div>

</div>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>
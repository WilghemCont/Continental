<?php require_once __DIR__ . '/../view/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-5" style="border-radius:24px;">
                <div class="text-center">
                    <div class="display-4 text-success">✅</div>
                    <h1 class="fw-bold">Pago aprobado</h1>
                    <p class="text-muted">Tu donación se ha procesado correctamente. Gracias por apoyar una causa social.</p>
                </div>
                <div class="mt-4 text-center">
                    <a href="index.php?controller=caso&action=catalogo" class="btn btn-primary px-5">Volver al Catálogo</a>
                    <a href="index.php?controller=donacion&action=historia" class="btn btn-outline-secondary px-5 ms-2">Ver el flujo</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../view/layout/footer.php'; ?>

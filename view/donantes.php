<?php require_once 'layout/header.php'; ?>

<div class="container-fluid py-4 fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-people-fill text-primary me-2"></i> Gestión de Donantes
        </h3>
        <a href="registro.php" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i> Nuevo Donante
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
        <div class="table-responsive p-4">
            <table class="table table-hover align-middle">
                <thead class="bg-light text-muted small fw-bold text-uppercase">
                    <tr>
                        <th class="border-0">Donante</th>
                        <th class="border-0">Correo Electrónico</th>
                        <th class="border-0">Fecha Registro</th>
                        <th class="border-0 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-donantes">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>

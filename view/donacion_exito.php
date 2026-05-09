<?php
require_once __DIR__ . '/layout/header.php';
?>

<div class="container py-5 fade-up">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 24px;">
                <div class="text-center mb-4">
                    <div class="display-4 text-success">✅</div>
                    <h1 class="fw-bold">¡Gracias por tu donación!</h1>
                    <p class="text-muted">Tu aporte está en camino para apoyar este caso social.</p>
                </div>

                <?php if (!empty($caso)): ?>
                    <div class="mb-4 p-4 bg-light rounded-4">
                        <h4 class="fw-bold mb-2"><?= htmlspecialchars($caso['titulo_publico'] ?? $caso['titulo_caso']) ?></h4>
                        <p class="mb-0 text-muted">Caso apoyado: <?= htmlspecialchars($caso['nombre_beneficiario'] ?? '') ?></p>
                    </div>
                <?php endif; ?>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-4 border">
                            <h6 class="text-uppercase text-muted small mb-2">Donante</h6>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($nombre) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-4 border">
                            <h6 class="text-uppercase text-muted small mb-2">Monto aportado</h6>
                            <p class="mb-0 fw-semibold">S/ <?= htmlspecialchars($monto) ?></p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-4 border mb-4">
                    <h6 class="text-uppercase text-muted small mb-3">Método de pago</h6>
                    <p class="mb-0"><?= htmlspecialchars($metodo) ?></p>
                </div>

                <div class="text-center">
                    <a href="../public/index.php?controller=caso&action=catalogo" class="btn btn-primary btn-lg px-5">Volver al Catálogo</a>
                    <a href="../public/index.php?controller=donacion&action=historia" class="btn btn-outline-secondary btn-lg px-5 ms-2">Ver cómo funciona</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

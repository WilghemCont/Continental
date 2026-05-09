<?php
require_once __DIR__ . '/layout/header.php';
?>

<div class="container py-5 fade-up">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Mi Historial de Donaciones</h1>
                    <p class="text-muted mb-0">Revisa todas tus contribuciones y descarga certificados</p>
                </div>
                <a href="../public/index.php?controller=caso&action=catalogo" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Nueva Donación
                </a>
            </div>

            <?php if (!empty($donaciones)): ?>
                <div class="row g-4">
                    <?php foreach ($donaciones as $d): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-1">S/ <?= number_format($d['monto'], 2) ?></h6>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($d['fecha'])) ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-success">Completada</span>
                                    </div>

                                    <?php if (!empty($d['titulo_publico'])): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Caso apoyado:</small>
                                            <strong class="text-dark"><?= htmlspecialchars($d['titulo_publico']) ?></strong>
                                            <?php if (!empty($d['nombre_beneficiario'])): ?>
                                                <br><small class="text-muted">Beneficiario: <?= htmlspecialchars($d['nombre_beneficiario']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Método:</small>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($d['metodo']) ?></span>
                                    </div>

                                    <?php if (!empty($d['mensaje'])): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Mensaje:</small>
                                            <p class="small mb-0 text-dark">"<?= htmlspecialchars($d['mensaje']) ?>"</p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-auto">
                                        <a href="../public/index.php?controller=donacion&action=certificado&id=<?= $d['id'] ?>"
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Ver Certificado
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-5 p-4 bg-light rounded-4">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4 class="fw-bold text-primary mb-1">
                                <?= count($donaciones) ?>
                            </h4>
                            <small class="text-muted">Total de donaciones</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="fw-bold text-success mb-1">
                                S/ <?= number_format(array_sum(array_column($donaciones, 'monto')), 2) ?>
                            </h4>
                            <small class="text-muted">Monto total aportado</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="fw-bold text-info mb-1">
                                S/ <?= number_format(array_sum(array_column($donaciones, 'monto')) / count($donaciones), 2) ?>
                            </h4>
                            <small class="text-muted">Promedio por donación</small>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="text-center py-5">
                    <div class="display-4 text-muted mb-3">🤝</div>
                    <h3 class="fw-bold mb-3">Aún no has realizado donaciones</h3>
                    <p class="text-muted mb-4">Tu primera contribución puede cambiar vidas. Explora los casos disponibles.</p>
                    <a href="../public/index.php?controller=caso&action=catalogo" class="btn btn-primary btn-lg">
                        <i class="bi bi-search me-2"></i> Explorar Casos
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

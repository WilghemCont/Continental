<?php
/**
 * view/beneficiario.php
 * Página de seguimiento del beneficiario — SocialFunding.
 * Solo accesible para usuarios con tipo = 'BENEFICIARIO'.
 */
session_start();
require_once("../models/BeneficiarioModel.php");

// ── Seguridad: redirigir si no es beneficiario autenticado ──
if (!isset($_SESSION['idlogin']) || ($_SESSION['tipo'] ?? '') !== 'BENEFICIARIO') {
    header("Location: login.php");
    exit;
}

$model       = new BeneficiarioModel();
$idusuario   = (int) $_SESSION['idusuario'];

// ── Cargar datos del caso vinculado ──
$caso        = $model->obtenerCasoPorBeneficiario($idusuario);
$actualizaciones = [];
$historial   = [];
$testimonio  = null;

if ($caso) {
    $actualizaciones = $model->obtenerActualizaciones((int) $caso['id']);
    $historial       = $model->obtenerHistorial((int) $caso['id']);
    $testimonio      = $model->obtenerTestimonio((int) $caso['id']);
}

// ── Calcular porcentaje de avance ──
$porcentaje = 0;
if ($caso && $caso['monto_requerido'] > 0) {
    $porcentaje = min(100, round(($caso['monto_recaudado'] / $caso['monto_requerido']) * 100, 1));
}

// ── Mapas de etiquetas y colores para los estados ──
$etiquetas_eval = [
    'pendiente' => 'En revisión',
    'aprobado'  => 'Aprobado',
    'observado' => 'Observado',
    'rechazado' => 'Rechazado',
    'publicado' => 'Publicado',
];
$etiquetas_proc = [
    'sin_proceso' => 'Sin iniciar',
    'en_proceso'  => 'En recaudación',
    'cancelado'   => 'Cancelado',
    'finalizado'  => 'Finalizado',
];

$estilo_pagina = 'beneficiario';
require_once 'layout/header.php';
?>

<div class="container py-4 fade-up">

    <!-- ══════════════════════════════════════════════
         HERO / BIENVENIDA
    ══════════════════════════════════════════════ -->
    <div class="beneficiario-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <p class="opacity-75 mb-1 small text-uppercase fw-bold">
                    <i class="bi bi-person-check me-1"></i> Panel del Beneficiario
                </p>
                <h1 class="mb-1">
                    Bienvenido/a, <?= esc($_SESSION['nombre']) ?> <?= esc($_SESSION['apepat'] ?? '') ?>
                </h1>
                <p class="opacity-80 mb-0">
                    Aquí puedes ver el avance de tu caso y todas las novedades del proceso.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <?php if ($caso): ?>
                    <?php
                        $proc   = $caso['estado_proceso'];
                        $clProc = "estado-{$proc}";
                    ?>
                    <div class="d-inline-flex flex-column align-items-center align-items-md-end gap-2">
                        <span class="estado-badge <?= $clProc ?>">
                            <span class="dot"></span>
                            <?= esc($etiquetas_proc[$proc] ?? ucfirst($proc)) ?>
                        </span>
                        <small class="opacity-75">Estado del proceso</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!$caso): ?>
    <!-- ── Sin caso vinculado ── -->
    <div class="card border-0 shadow-sm p-5 text-center">
        <i class="bi bi-folder-x display-2 text-muted opacity-50 mb-3"></i>
        <h4 class="text-muted">Tu cuenta aún no tiene un caso vinculado</h4>
        <p class="text-muted">El administrador de la plataforma enlazará tu caso próximamente. Por favor vuelve más tarde.</p>
    </div>
    <?php else: ?>

    <div class="row g-4">

        <!-- ══════════════════════════════════════════════
             COLUMNA PRINCIPAL (izquierda / centro)
        ══════════════════════════════════════════════ -->
        <div class="col-lg-8">

            <!-- ── Métricas rápidas ── -->
            <div class="row g-3 mb-4">

                <div class="col-sm-6 col-xl-3">
                    <div class="metric-card">
                        <div class="d-flex align-items-center gap-3">
                            <div class="metric-icon bg-azul"><i class="bi bi-bullseye"></i></div>
                            <div>
                                <div class="metric-label">Meta</div>
                                <div class="metric-value count-up"
                                     data-target="<?= $caso['monto_requerido'] ?>"
                                     data-prefix="S/ " data-decimals="0">
                                    S/ 0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="metric-card green">
                        <div class="d-flex align-items-center gap-3">
                            <div class="metric-icon bg-verde"><i class="bi bi-cash-coin"></i></div>
                            <div>
                                <div class="metric-label">Recaudado</div>
                                <div class="metric-value count-up"
                                     data-target="<?= $caso['monto_recaudado'] ?>"
                                     data-prefix="S/ " data-decimals="0">
                                    S/ 0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="metric-card orange">
                        <div class="d-flex align-items-center gap-3">
                            <div class="metric-icon bg-orange"><i class="bi bi-percent"></i></div>
                            <div>
                                <div class="metric-label">Avance</div>
                                <div class="metric-value"><?= $porcentaje ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="metric-card purple">
                        <div class="d-flex align-items-center gap-3">
                            <div class="metric-icon bg-purple"><i class="bi bi-bell"></i></div>
                            <div>
                                <div class="metric-label">Actualizaciones</div>
                                <div class="metric-value"><?= count($actualizaciones) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /row métricas -->

            <!-- ── Barra de progreso de recaudación ── -->
            <div class="progress-section mb-4">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h5 class="fw-bold mb-0" style="color:var(--dark)">
                            <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Progreso de la campaña
                        </h5>
                        <p class="small text-muted mb-0">
                            <?= $caso['publicado'] ? 'Campaña activa en la plataforma' : 'Aún no publicada públicamente' ?>
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="progress-pct"><?= $porcentaje ?>%</div>
                        <div class="small text-muted">completado</div>
                    </div>
                </div>

                <div class="progress-wrap mb-3">
                    <div class="progress-fill"
                         id="progressFill"
                         data-pct="<?= $porcentaje ?>"
                         style="width:0%">
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-muted fw-semibold">
                    <span>S/ <?= number_format($caso['monto_recaudado'], 2) ?> recaudado</span>
                    <span>Meta: S/ <?= number_format($caso['monto_requerido'], 2) ?></span>
                </div>

                <?php if ($porcentaje >= 100): ?>
                <div class="alert alert-success rounded-3 mt-3 mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill fs-5"></i>
                    <span><strong>¡Meta alcanzada!</strong> Tu campaña completó el 100% del objetivo. Próximamente recibirás más información sobre la entrega de la ayuda.</span>
                </div>
                <?php endif; ?>
            </div>

            <!-- ── Estado detallado del caso ── -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-4" style="color:var(--dark)">
                    <i class="bi bi-diagram-3 me-2 text-primary"></i>Estado del proceso
                </h5>

                <?php
                // Definir las etapas del flujo
                $etapas = [
                    ['key' => 'pendiente',   'label' => 'En revisión',    'icon' => 'bi-hourglass-split',   'desc' => 'El caso está siendo revisado por el equipo administrativo.'],
                    ['key' => 'aprobado',    'label' => 'Aprobado',       'icon' => 'bi-shield-check',      'desc' => 'El caso cumple los requisitos y fue aprobado.'],
                    ['key' => 'publicado',   'label' => 'Publicado',      'icon' => 'bi-megaphone',         'desc' => 'El caso está visible en la plataforma y recibe donaciones.'],
                    ['key' => 'en_proceso',  'label' => 'En recaudación', 'icon' => 'bi-cash-stack',        'desc' => 'La campaña está activa y recaudando fondos.'],
                    ['key' => 'finalizado',  'label' => 'Finalizado',     'icon' => 'bi-check-circle',      'desc' => 'La meta fue alcanzada y el caso cerrado exitosamente.'],
                ];

                // Determinar cuál etapa está activa
                $estadoActual = $caso['estado_evaluacion'];
                $procesoActual = $caso['estado_proceso'];

                $etapaActiva = 'pendiente';
                if ($estadoActual === 'publicado' && $procesoActual === 'finalizado') {
                    $etapaActiva = 'finalizado';
                } elseif ($estadoActual === 'publicado' && $procesoActual === 'en_proceso') {
                    $etapaActiva = 'en_proceso';
                } elseif ($estadoActual === 'publicado') {
                    $etapaActiva = 'publicado';
                } elseif ($estadoActual === 'aprobado') {
                    $etapaActiva = 'aprobado';
                }

                $orden = array_column($etapas, 'key');
                $idxActivo = array_search($etapaActiva, $orden);
                ?>

                <div class="d-flex flex-column gap-2">
                    <?php foreach ($etapas as $i => $etapa): ?>
                        <?php
                        $isPasada  = $i < $idxActivo;
                        $isActiva  = $etapa['key'] === $etapaActiva;
                        $isFutura  = $i > $idxActivo;

                        if ($isActiva) {
                            $bgCls  = 'bg-primary text-white';
                            $opcion = '';
                        } elseif ($isPasada) {
                            $bgCls  = 'bg-success text-white';
                            $opcion = '';
                        } else {
                            $bgCls  = 'bg-light text-muted';
                            $opcion = 'opacity-50';
                        }
                        ?>
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3 <?= $bgCls ?> <?= $opcion ?>">
                            <div style="font-size:1.4rem; flex-shrink:0;">
                                <?php if ($isPasada): ?>
                                    <i class="bi bi-check-circle-fill"></i>
                                <?php elseif ($isActiva): ?>
                                    <i class="bi <?= $etapa['icon'] ?>"></i>
                                <?php else: ?>
                                    <i class="bi bi-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?= esc($etapa['label']) ?></div>
                                <div class="small <?= $isActiva ? 'opacity-75' : '' ?>"><?= esc($etapa['desc']) ?></div>
                            </div>
                            <?php if ($isActiva): ?>
                            <div class="ms-auto">
                                <span class="badge bg-white text-primary rounded-pill px-3 fw-bold">Actual</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── Actualizaciones / Timeline ── -->
            <div class="card border-0 shadow-sm p-4 mb-4" id="actualizaciones">
                <h5 class="fw-bold mb-4" style="color:var(--dark)">
                    <i class="bi bi-newspaper me-2 text-primary"></i>Actualizaciones del caso
                </h5>

                <?php if (empty($actualizaciones)): ?>
                    <div class="empty-state">
                        <i class="bi bi-bell-slash d-block mb-3"></i>
                        <p class="mb-0">Aún no hay actualizaciones. Pronto recibirás novedades sobre tu caso.</p>
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($actualizaciones as $upd): ?>
                            <div class="timeline-item tipo-<?= esc($upd['tipo']) ?>">
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="timeline-tipo-badge badge-<?= esc($upd['tipo']) ?>">
                                        <?= match($upd['tipo']) {
                                            'avance'       => '📊 Avance',
                                            'coordinacion' => '📋 Coordinación',
                                            'entrega'      => '🎁 Entrega',
                                            'cierre'       => '✅ Cierre',
                                            default        => ucfirst(esc($upd['tipo']))
                                        } ?>
                                    </span>
                                    <small class="text-muted ms-auto">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date('d/m/Y', strtotime($upd['fecha'])) ?>
                                    </small>
                                </div>
                                <h6 class="fw-bold mb-1"><?= esc($upd['titulo']) ?></h6>
                                <p class="mb-0 text-muted small"><?= nl2br(esc($upd['contenido'])) ?></p>
                                <div class="mt-2">
                                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i><?= esc($upd['autor']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ── Testimonio del beneficiario ── -->
            <?php if ($caso['estado_proceso'] === 'finalizado'): ?>
            <div class="card border-0 shadow-sm p-4 mb-4" id="testimonio">
                <h5 class="fw-bold mb-2" style="color:var(--dark)">
                    <i class="bi bi-chat-heart me-2 text-success"></i>Tu testimonio
                </h5>
                <p class="text-muted small mb-4">Comparte con la comunidad tu experiencia y cómo esta ayuda cambió tu vida. Tu testimonio es muy valioso.</p>

                <?php if ($testimonio && !empty($testimonio['contenido'])): ?>
                    <!-- Testimonio ya guardado: mostrarlo -->
                    <div class="testimonio-card mb-4">
                        <div class="quote-icon">"</div>
                        <p class="mb-0 fs-6 lh-lg ps-4" id="testimonioTexto"
                           style="color:var(--dark); font-style:italic;">
                            <?= nl2br(esc($testimonio['contenido'])) ?>
                        </p>
                        <div class="mt-3 ps-4 small text-muted">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Publicado el <?= date('d/m/Y', strtotime($testimonio['fecha'])) ?>
                        </div>
                    </div>
                    <p class="small text-muted text-center">¿Deseas editar tu testimonio? Puedes actualizarlo a continuación.</p>
                <?php endif; ?>

                <!-- Formulario para agregar / editar testimonio -->
                <form id="formTestimonio">
                    <input type="hidden" name="caso_id" value="<?= (int) $caso['id'] ?>">
                    <div class="testimonio-card">
                        <div class="quote-icon">"</div>
                        <div class="ps-4">
                            <textarea name="contenido"
                                      class="form-control mb-3"
                                      rows="5"
                                      placeholder="Escribe aquí cómo te ayudó esta campaña..."
                                      required><?= $testimonio ? esc($testimonio['contenido']) : '' ?></textarea>
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="bi bi-send-check me-2"></i>Guardar Testimonio
                            </button>
                        </div>
                    </div>
                    <div id="alertTestimonio" class="d-none"></div>
                </form>
            </div>
            <?php endif; ?>

            <!-- ── Historial de cambios (colapsable) ── -->
            <?php if (!empty($historial)): ?>
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0" style="color:var(--dark)">
                        <i class="bi bi-clock-history me-2 text-secondary"></i>Historial de cambios
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#colHistorial">
                        Ver / ocultar
                    </button>
                </div>
                <div class="collapse" id="colHistorial">
                    <?php foreach ($historial as $h): ?>
                        <div class="historial-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="fw-semibold text-capitalize">
                                        <?= esc(str_replace('_', ' ', $h['tipo_cambio'])) ?>:
                                    </span>
                                    <span class="text-muted">
                                        <?= esc($h['valor_anterior'] ?? '—') ?> → <?= esc($h['valor_nuevo'] ?? '—') ?>
                                    </span>
                                </div>
                                <small class="text-muted text-nowrap ms-2">
                                    <?= date('d/m/Y H:i', strtotime($h['fecha'])) ?>
                                </small>
                            </div>
                            <?php if (!empty($h['comentario'])): ?>
                                <div class="text-muted small mt-1"><?= esc($h['comentario']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /col principal -->

        <!-- ══════════════════════════════════════════════
             COLUMNA LATERAL (sidebar)
        ══════════════════════════════════════════════ -->
        <div class="col-lg-4">
            <div class="caso-info-card">

                <!-- Foto del beneficiario -->
                <?php if (!empty($caso['foto_beneficiario'])): ?>
                    <img src="../assets/uploads/fotos/<?= esc($caso['foto_beneficiario']) ?>"
                         alt="Foto del beneficiario"
                         class="caso-foto mb-3">
                <?php else: ?>
                    <div class="caso-foto-placeholder mb-3">
                        <i class="bi bi-person-circle"></i>
                    </div>
                <?php endif; ?>

                <!-- Título del caso -->
                <h5 class="fw-bold mb-1" style="color:var(--azul)">
                    <?= esc($caso['titulo_publico'] ?: $caso['titulo_caso']) ?>
                </h5>
                <p class="small text-muted mb-3">
                    <i class="bi bi-building me-1"></i><?= esc($caso['nombre_ong']) ?>
                </p>

                <!-- Clasificación -->
                <?php
                $clasificaciones = [
                    'salud'         => ['🏥', 'Salud',             'badge-salud'],
                    'desastres'     => ['🌊', 'Desastres',         'badge-desastres'],
                    'medio_ambiente'=> ['🌿', 'Medio ambiente',     'badge-ambiente'],
                    'educacion'     => ['📚', 'Educación',         'badge-educacion'],
                ];
                $clf = $clasificaciones[$caso['clasificacion']] ?? ['📌', ucfirst($caso['clasificacion']), ''];
                ?>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3">
                    <?= $clf[0] ?> <?= esc($clf[1]) ?>
                </span>

                <hr>

                <!-- Información básica -->
                <div class="info-row">
                    <span class="label"><i class="bi bi-geo-alt me-1"></i>Ubicación</span>
                    <span class="value"><?= esc($caso['ubicacion'] ?? '—') ?></span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="bi bi-calendar me-1"></i>Registro</span>
                    <span class="value"><?= date('d/m/Y', strtotime($caso['fecha_registro'])) ?></span>
                </div>
                <?php if ($caso['fecha_publicacion']): ?>
                <div class="info-row">
                    <span class="label"><i class="bi bi-megaphone me-1"></i>Publicado</span>
                    <span class="value"><?= date('d/m/Y', strtotime($caso['fecha_publicacion'])) ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="label"><i class="bi bi-flag me-1"></i>Evaluación</span>
                    <span class="value">
                        <?= esc($etiquetas_eval[$caso['estado_evaluacion']] ?? $caso['estado_evaluacion']) ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="bi bi-activity me-1"></i>Proceso</span>
                    <span class="value">
                        <?= esc($etiquetas_proc[$caso['estado_proceso']] ?? $caso['estado_proceso']) ?>
                    </span>
                </div>

                <hr>

                <!-- Descripción pública -->
                <div class="mb-3">
                    <p class="small fw-bold text-muted text-uppercase mb-1">Descripción</p>
                    <p class="small text-secondary mb-0" style="line-height:1.7;">
                        <?= nl2br(esc($caso['descripcion_publica'] ?: $caso['descripcion'])) ?>
                    </p>
                </div>

                <!-- Accesos rápidos -->
                <div class="d-grid gap-2 mt-3">
                    <a href="#actualizaciones"
                       class="btn btn-outline-primary btn-sm rounded-pill fw-semibold">
                        <i class="bi bi-bell me-1"></i> Ver actualizaciones
                    </a>
                    <?php if ($caso['estado_proceso'] === 'finalizado'): ?>
                    <a href="#testimonio"
                       class="btn btn-outline-success btn-sm rounded-pill fw-semibold">
                        <i class="bi bi-chat-heart me-1"></i> Escribir testimonio
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($caso['documento_solicitud'])): ?>
                    <a href="../assets/uploads/docs/<?= esc($caso['documento_solicitud']) ?>"
                       target="_blank"
                       class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Ver expediente
                    </a>
                    <?php endif; ?>
                </div>

            </div><!-- /caso-info-card -->
        </div><!-- /col sidebar -->

    </div><!-- /row -->
    <?php endif; /* fin if $caso */ ?>

</div><!-- /container -->

<?php require_once 'layout/footer.php'; ?>

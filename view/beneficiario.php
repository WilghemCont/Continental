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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-md-5 position-relative bg-white">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 fw-semibold text-uppercase tracking-wide">
                        <i class="bi bi-person-check-fill me-1"></i> Panel del Beneficiario
                    </span>
                    <h1 class="fw-bold mb-2" style="color: var(--dark); font-size: 2.2rem;">
                        Bienvenido/a, <?= esc($_SESSION['nombre']) ?> <?= esc($_SESSION['apepat'] ?? '') ?>
                    </h1>
                    <p class="text-secondary fs-5 mb-0">
                        Aquí puedes ver el avance de tu caso y todas las novedades de tu proceso.
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <?php if ($caso): ?>
                        <?php
                            $proc   = $caso['estado_proceso'];
                            // Mapeo básico de colores para el badge de estado general
                            $badgeColor = match($proc) {
                                'finalizado' => 'success',
                                'en_proceso' => 'primary',
                                'cancelado'  => 'danger',
                                default      => 'secondary'
                            };
                        ?>
                        <div class="d-inline-flex flex-column align-items-md-end p-3 rounded-4 bg-light border">
                            <small class="text-muted fw-semibold text-uppercase mb-1">Estado del proceso</small>
                            <span class="badge bg-<?= $badgeColor ?> rounded-pill px-4 py-2 fs-6 shadow-sm d-flex align-items-center gap-2">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" style="width: 0.5rem; height: 0.5rem;"></span>
                                <?= esc($etiquetas_proc[$proc] ?? ucfirst($proc)) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$caso): ?>
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-5">
        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-folder-x display-4 text-secondary"></i>
        </div>
        <h3 class="fw-bold text-dark mb-3">Tu cuenta aún no tiene un caso vinculado</h3>
        <p class="text-secondary fs-5 mx-auto" style="max-width: 600px;">
            El administrador de la plataforma enlazará tu caso próximamente. Por favor vuelve a revisar más tarde.
        </p>
    </div>
    <?php else: ?>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="row g-3 mb-4">

                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-bullseye fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase">Meta</div>
                                <div class="fs-5 fw-bold text-dark count-up"
                                     data-target="<?= $caso['monto_requerido'] ?>"
                                     data-prefix="S/ " data-decimals="0">
                                    S/ 0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase">Recaudado</div>
                                <div class="fs-5 fw-bold text-dark count-up"
                                     data-target="<?= $caso['monto_recaudado'] ?>"
                                     data-prefix="S/ " data-decimals="0">
                                    S/ 0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-percent fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase">Avance</div>
                                <div class="fs-5 fw-bold text-dark"><?= $porcentaje ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-10 text-info flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi bi-bell-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase">Novedades</div>
                                <div class="fs-5 fw-bold text-dark"><?= count($actualizaciones) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 50px; height: 50px;">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Progreso de la campaña</h5>
                            <p class="small text-secondary mb-0">
                                <?= $caso['publicado'] ? 'Campaña activa y visible en la plataforma' : 'Aún no publicada públicamente' ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fs-3 fw-bold text-primary lh-1"><?= $porcentaje ?>%</div>
                        <div class="small text-muted fw-semibold text-uppercase mt-1">Completado</div>
                    </div>
                </div>

                <div class="progress mb-3 bg-light rounded-pill shadow-inner" style="height: 1.5rem;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary rounded-pill"
                         id="progressFill"
                         role="progressbar"
                         data-pct="<?= $porcentaje ?>"
                         style="width: 0%; transition: width 1.5s ease-in-out;"
                         aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-secondary fw-semibold px-2">
                    <span><i class="bi bi-wallet2 me-1"></i>S/ <?= number_format($caso['monto_recaudado'], 2) ?> recaudado</span>
                    <span><i class="bi bi-flag-fill me-1"></i>Meta: S/ <?= number_format($caso['monto_requerido'], 2) ?></span>
                </div>

                <?php if ($porcentaje >= 100): ?>
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-4 mt-4 mb-0 d-flex align-items-center gap-3 p-3 shadow-sm">
                    <i class="bi bi-trophy-fill fs-3"></i>
                    <div>
                        <strong class="d-block mb-1">¡Meta alcanzada!</strong>
                        <span class="small text-success text-opacity-75">Tu campaña completó el 100% del objetivo. Próximamente recibirás más información sobre la entrega de la ayuda.</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark bg-opacity-10 text-dark" style="width: 40px; height: 40px;">
                        <i class="bi bi-diagram-3-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Ruta de tu proceso</h5>
                </div>

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

                <div class="d-flex flex-column gap-3">
                    <?php foreach ($etapas as $i => $etapa): ?>
                        <?php
                        $isPasada  = $i < $idxActivo;
                        $isActiva  = $etapa['key'] === $etapaActiva;
                        $isFutura  = $i > $idxActivo;

                        // Clases dinámicas modernas
                        if ($isActiva) {
                            $boxCls  = 'bg-primary bg-opacity-10 border border-primary border-opacity-50 text-primary shadow-sm';
                            $iconCls = 'text-primary';
                        } elseif ($isPasada) {
                            $boxCls  = 'bg-success bg-opacity-10 border border-success border-opacity-25 text-success';
                            $iconCls = 'text-success';
                        } else {
                            $boxCls  = 'bg-light border border-light text-muted opacity-75';
                            $iconCls = 'text-muted';
                        }
                        ?>
                        <div class="d-flex align-items-center gap-3 p-3 rounded-4 <?= $boxCls ?> transition-all">
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm flex-shrink-0 <?= $iconCls ?>" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                <?php if ($isPasada): ?>
                                    <i class="bi bi-check-lg"></i>
                                <?php elseif ($isActiva): ?>
                                    <i class="bi <?= $etapa['icon'] ?>"></i>
                                <?php else: ?>
                                    <i class="bi bi-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold fs-6"><?= esc($etapa['label']) ?></div>
                                <div class="small opacity-75 mt-1"><?= esc($etapa['desc']) ?></div>
                            </div>
                            <?php if ($isActiva): ?>
                            <div class="ms-auto ps-2">
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-semibold shadow-sm animate-pulse">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Actual
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="actualizaciones">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info" style="width: 40px; height: 40px;">
                        <i class="bi bi-newspaper fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Actualizaciones del caso</h5>
                </div>

                <?php if (empty($actualizaciones)): ?>
                    <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                        <i class="bi bi-bell-slash display-5 text-muted opacity-50 mb-3 d-block"></i>
                        <h6 class="fw-bold text-secondary">Sin novedades aún</h6>
                        <p class="text-muted small mb-0">Pronto recibirás notificaciones sobre el avance de tu caso.</p>
                    </div>
                <?php else: ?>
                    <div class="position-relative ms-3 border-start border-2 border-primary border-opacity-25 pb-2">
                        <?php foreach ($actualizaciones as $upd): ?>
                            <div class="position-relative ps-4 mb-4">
                                <span class="position-absolute top-0 start-0 translate-middle p-2 bg-white border border-2 border-primary rounded-circle" style="margin-top: 0.25rem;"></span>
                                
                                <div class="card border border-light shadow-sm rounded-4 hover-shadow transition-all">
                                    <div class="card-body p-3 p-md-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-semibold">
                                                <?= match($upd['tipo']) {
                                                    'avance'       => '<i class="bi bi-graph-up text-primary me-1"></i> Avance',
                                                    'coordinacion' => '<i class="bi bi-clipboard-check text-warning me-1"></i> Coordinación',
                                                    'entrega'      => '<i class="bi bi-box-seam text-info me-1"></i> Entrega',
                                                    'cierre'       => '<i class="bi bi-check-circle-fill text-success me-1"></i> Cierre',
                                                    default        => '<i class="bi bi-info-circle text-secondary me-1"></i> ' . ucfirst(esc($upd['tipo']))
                                                } ?>
                                            </span>
                                            <small class="text-secondary fw-semibold bg-light rounded-pill px-3 py-1">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                <?= date('d/m/Y', strtotime($upd['fecha'])) ?>
                                            </small>
                                        </div>
                                        <h6 class="fw-bold text-dark fs-5 mb-2"><?= esc($upd['titulo']) ?></h6>
                                        <p class="text-secondary mb-3" style="line-height: 1.6;"><?= nl2br(esc($upd['contenido'])) ?></p>
                                        <div class="pt-3 border-top border-light d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 text-secondary me-2" style="width: 24px; height: 24px;">
                                                <i class="bi bi-person-fill" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <small class="text-muted fw-semibold">Por: <?= esc($upd['autor']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($caso['estado_proceso'] === 'finalizado'): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-success bg-opacity-10 border-start border-5 border-success" id="testimonio">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white shadow-sm" style="width: 40px; height: 40px;">
                        <i class="bi bi-chat-quote-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Tu experiencia es nuestra inspiración</h5>
                </div>
                <p class="text-secondary small mb-4 ms-5">Comparte con la comunidad cómo esta ayuda cambió tu vida. Tu testimonio animará a más personas a seguir donando.</p>

                <?php if ($testimonio && !empty($testimonio['contenido'])): ?>
                    <div class="card border-0 bg-white shadow-sm rounded-4 mb-4 position-relative overflow-hidden">
                        <i class="bi bi-quote position-absolute text-success opacity-10" style="font-size: 8rem; top: -30px; left: -10px;"></i>
                        <div class="card-body p-4 position-relative z-1">
                            <p class="fs-5 text-dark fst-italic mb-0" id="testimonioTexto" style="line-height: 1.8;">
                                "<?= nl2br(esc($testimonio['contenido'])) ?>"
                            </p>
                            <hr class="my-3 border-light">
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted fw-semibold">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> Publicado públicamente
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y', strtotime($testimonio['fecha'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <p class="small text-muted text-center mb-4"><i class="bi bi-pencil me-1"></i> ¿Deseas editar tu testimonio? Hazlo en el recuadro de abajo.</p>
                <?php endif; ?>

                <form id="formTestimonio" class="ms-md-5">
                    <input type="hidden" name="caso_id" value="<?= (int) $caso['id'] ?>">
                    <div class="form-floating mb-3 shadow-sm rounded-4 overflow-hidden">
                        <textarea name="contenido" 
                                  class="form-control border-0 bg-white" 
                                  id="testimonioInput"
                                  style="height: 150px; resize: none;"
                                  placeholder="Escribe tu testimonio aquí..." 
                                  required><?= $testimonio ? esc($testimonio['contenido']) : '' ?></textarea>
                        <label for="testimonioInput" class="text-muted">Escribe tu testimonio aquí...</label>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm">
                            <i class="bi bi-send-fill me-2"></i><?= $testimonio ? 'Actualizar Testimonio' : 'Publicar Testimonio' ?>
                        </button>
                    </div>
                    <div id="alertTestimonio" class="d-none mt-3"></div>
                </form>
            </div>
            <?php endif; ?>

            <?php if (!empty($historial)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10 text-secondary" style="width: 40px; height: 40px;">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">Historial administrativo</h5>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold d-flex align-items-center gap-2"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#colHistorial"
                            aria-expanded="false">
                        Desplegar <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
                
                <div class="collapse mt-4" id="colHistorial">
                    <div class="bg-light rounded-4 p-3 border">
                        <?php foreach ($historial as $h): ?>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-3 border-bottom border-secondary border-opacity-10 last-border-0">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill text-capitalize fw-bold">
                                            <?= esc(str_replace('_', ' ', $h['tipo_cambio'])) ?>
                                        </span>
                                    </div>
                                    <div class="text-dark fs-6">
                                        <span class="text-muted text-decoration-line-through me-2"><?= esc($h['valor_anterior'] ?? '—') ?></span>
                                        <i class="bi bi-arrow-right text-primary mx-1"></i>
                                        <span class="fw-bold"><?= esc($h['valor_nuevo'] ?? '—') ?></span>
                                    </div>
                                    <?php if (!empty($h['comentario'])): ?>
                                        <div class="text-muted small mt-2 bg-white p-2 rounded-3 border"><i class="bi bi-chat-left-text me-1"></i> <?= esc($h['comentario']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-sm-end mt-2 mt-sm-0">
                                    <span class="small text-muted bg-white px-2 py-1 rounded-pill border fw-semibold">
                                        <i class="bi bi-calendar2-week me-1"></i> <?= date('d/m/Y H:i', strtotime($h['fecha'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div><div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 2rem;">
                
                <div class="text-center mb-4">
                    <?php if (!empty($caso['foto_beneficiario'])): ?>
                        <div class="position-relative d-inline-block">
                            <img src="../assets/uploads/fotos/<?= esc($caso['foto_beneficiario']) ?>"
                                 alt="Foto del beneficiario"
                                 class="rounded-circle object-fit-cover shadow-sm border border-4 border-white"
                                 style="width: 140px; height: 140px;">
                            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-2" title="Usuario Verificado"></span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm mx-auto border border-4 border-white text-secondary" style="width: 140px; height: 140px;">
                            <i class="bi bi-person-bounding-box display-4"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark mb-2" style="line-height: 1.4;">
                        <?= esc($caso['titulo_publico'] ?: $caso['titulo_caso']) ?>
                    </h5>
                    <p class="small text-secondary fw-semibold bg-light rounded-pill d-inline-block px-3 py-1 mb-0 border">
                        <i class="bi bi-building me-1 text-primary"></i> <?= esc($caso['nombre_ong']) ?>
                    </p>
                </div>

                <?php
                $clasificaciones = [
                    'salud'          => ['🏥', 'Salud',          'danger'],
                    'desastres'      => ['🌊', 'Desastres',      'info'],
                    'medio_ambiente' => ['🌿', 'Medio ambiente', 'success'],
                    'educacion'      => ['📚', 'Educación',      'warning'],
                ];
                $clf = $clasificaciones[$caso['clasificacion']] ?? ['📌', ucfirst($caso['clasificacion']), 'primary'];
                $colorClf = $clf[2];
                ?>
                <div class="text-center mb-4">
                    <span class="badge bg-<?= $colorClf ?> bg-opacity-10 text-<?= $colorClf ?> rounded-pill px-4 py-2 fs-6 border border-<?= $colorClf ?> border-opacity-25 shadow-sm">
                        <?= $clf[0] ?> <?= esc($clf[1]) ?>
                    </span>
                </div>

                <hr class="border-light mb-4">

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-geo-alt-fill me-2 text-secondary"></i>Ubicación</span>
                        <span class="fw-semibold text-dark text-end"><?= esc($caso['ubicacion'] ?? '—') ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-calendar-check-fill me-2 text-secondary"></i>Registro</span>
                        <span class="fw-semibold text-dark text-end"><?= date('d/m/Y', strtotime($caso['fecha_registro'])) ?></span>
                    </div>
                    <?php if ($caso['fecha_publicacion']): ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-megaphone-fill me-2 text-secondary"></i>Publicado</span>
                        <span class="fw-semibold text-dark text-end"><?= date('d/m/Y', strtotime($caso['fecha_publicacion'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold text-uppercase"><i class="bi bi-shield-check me-2 text-secondary"></i>Evaluación</span>
                        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill">
                            <?= esc($etiquetas_eval[$caso['estado_evaluacion']] ?? $caso['estado_evaluacion']) ?>
                        </span>
                    </div>
                </div>

                <div class="bg-light p-3 rounded-4 mb-4 border">
                    <p class="small fw-bold text-muted text-uppercase mb-2"><i class="bi bi-card-text me-1"></i> Resumen</p>
                    <p class="small text-secondary mb-0" style="line-height: 1.6;">
                        <?= nl2br(esc($caso['descripcion_publica'] ?: $caso['descripcion'])) ?>
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <a href="#actualizaciones" class="btn btn-primary rounded-pill py-2 fw-semibold shadow-sm transition-all hover-lift">
                        <i class="bi bi-bell-fill me-2"></i> Ver actualizaciones
                    </a>
                    <?php if ($caso['estado_proceso'] === 'finalizado'): ?>
                    <a href="#testimonio" class="btn btn-success rounded-pill py-2 fw-semibold shadow-sm transition-all hover-lift">
                        <i class="bi bi-chat-heart-fill me-2"></i> Escribir testimonio
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($caso['documento_solicitud'])): ?>
                    <a href="../assets/uploads/docs/<?= esc($caso['documento_solicitud']) ?>" target="_blank" class="btn btn-outline-dark rounded-pill py-2 fw-semibold transition-all hover-lift mt-2">
                        <i class="bi bi-file-earmark-pdf-fill me-2 text-danger"></i> Ver expediente técnico
                    </a>
                    <?php endif; ?>
                </div>

            </div></div></div><?php endif; /* fin if $caso */ ?>

</div><style>
    .tracking-wide { letter-spacing: 0.05em; }
    .transition-all { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-2px); }
    .hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important; border-color: var(--bs-primary) !important; }
    .shadow-inner { box-shadow: inset 0 1px 2px rgba(0,0,0,0.075); }
    .last-border-0:last-child { border-bottom: 0 !important; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(var(--bs-primary-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--bs-primary-rgb), 0); }
    }
    .animate-pulse { animation: pulse 2s infinite; }
</style>

<?php require_once 'layout/footer.php'; ?>
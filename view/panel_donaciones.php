<?php
require_once 'layout/header.php';
?>
 
<div class="container-fluid py-4 px-4 fade-up">
 
  <!-- Encabezado -->
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
      <span class="about-badge mb-2 d-inline-block">Administración</span>
      <h2 class="about-title mb-1">
        <i class="bi bi-bar-chart-line me-2"></i>Panel Financiero
      </h2>
      <p class="about-text">Control de ingresos, comisiones y donaciones en tiempo real</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="index.php?controller=donacion&action=exportarCSV"
         class="btn btn-outline-secondary btn-sm shadow-sm">
        <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
      </a>
      <a href="index.php?controller=donacion&action=registroIngreso"
         class="btn btn-primary btn-sm shadow-sm">
        <i class="bi bi-plus-circle"></i> Nuevo Ingreso
      </a>
    </div>
  </div>
 
  <!-- KPIs -->
  <div class="row g-3 mb-5">
    <?php
    $kpicards = [
      ['ico'=>'cash-stack',      'lab'=>'Total Captado',     'val'=>'S/ '.number_format($kpis['total_monto']       ?? 0, 2), 'color'=>'var(--primary)'],
      ['ico'=>'percent',         'lab'=>'Comisiones',        'val'=>'S/ '.number_format($kpis['total_comisiones']  ?? 0, 2), 'color'=>'#059669'],
      ['ico'=>'people',          'lab'=>'Total Donaciones',  'val'=>number_format($kpis['total_donaciones']        ?? 0),     'color'=>'var(--primary)'],
      ['ico'=>'briefcase-fill',  'lab'=>'Patrocinios',       'val'=>'S/ '.number_format($kpis['total_patrocinios'] ?? 0, 2), 'color'=>'#d97706'],
      ['ico'=>'calendar-week',   'lab'=>'Esta Semana',       'val'=>'S/ '.number_format($kpis['ingresos_semana']   ?? 0, 2), 'color'=>'var(--primary)'],
      ['ico'=>'calendar-month',  'lab'=>'Este Mes',          'val'=>'S/ '.number_format($kpis['ingresos_mes']      ?? 0, 2), 'color'=>'#059669'],
    ];
    foreach ($kpicards as $k): ?>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="stat-card text-center h-100 p-3" style="border-radius:18px;">
        <div class="d-flex justify-content-center mb-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
               style="width:42px;height:42px;background:rgba(255,255,255,.15);">
            <i class="bi bi-<?= $k['ico'] ?> fs-5 text-white"></i>
          </div>
        </div>
        <div class="stat-num fs-5"><?= $k['val'] ?></div>
        <p class="small mb-0 opacity-75"><?= $k['lab'] ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
 
  <!-- Tabla de donaciones -->
  <div class="card shadow-sm border-0 p-4" style="border-radius:20px;">
    <h5 class="fw-bold mb-3">
      <i class="bi bi-table me-2 text-primary"></i>Historial de Donaciones e Ingresos
    </h5>
 
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Donador</th>
            <th>Tipo</th>
            <th class="text-end">Monto</th>
            <th class="text-end">Comisión</th>
            <th class="text-end">Neto</th>
            <th>Estado</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($donaciones)): ?>
          <tr>
            <td colspan="8" class="text-center text-muted py-5">
              <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
              No hay registros disponibles
            </td>
          </tr>
        <?php else: foreach ($donaciones as $d): ?>
          <tr>
            <td class="text-muted small">#<?= $d['id'] ?? '' ?></td>
 
            <td>
              <div class="fw-semibold"><?= htmlspecialchars($d['donador_nombre'] ?? '—') ?></div>
              <small class="text-muted">
                <?= (($d['donador_tipo'] ?? 'persona') === 'empresa')
                    ? '<i class="bi bi-building"></i> Empresa'
                    : '<i class="bi bi-person"></i> Persona' ?>
              </small>
            </td>
 
            <td>
              <?php
              $tipo  = strtolower(trim($d['tipo_donacion'] ?? ''));
              $badge = match(true) {
                  str_contains($tipo, 'patrocinio')  => 'warning text-dark',
                  str_contains($tipo, 'donación') || str_contains($tipo, 'donacion') || str_contains($tipo, 'caso') => 'success',
                  str_contains($tipo, 'comisi')      => 'info text-dark',
                  str_contains($tipo, 'plataforma')  => 'primary',
                  default                            => 'secondary',
              };
              $label = $d['tipo_donacion'] ?? '—';
              ?>
              <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($label) ?></span>
            </td>
 
            <td class="text-end fw-semibold">
              S/ <?= number_format($d['monto_total'] ?? 0, 2) ?>
            </td>
 
            <td class="text-end text-danger small">
              <?php if (!empty($d['monto_comision'])): ?>
                - S/ <?= number_format($d['monto_comision'], 2) ?>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
 
            <td class="text-end fw-bold text-success">
              S/ <?= number_format($d['monto_neto'] ?? 0, 2) ?>
            </td>
 
            <td>
              <span class="badge bg-<?= ($d['estado'] ?? '') === 'completado' ? 'success' : 'warning text-dark' ?>">
                <?= ucfirst($d['estado'] ?? '—') ?>
              </span>
            </td>
 
            <td class="small text-muted">
              <?= !empty($d['fecha']) ? date('d/m/Y H:i', strtotime($d['fecha'])) : '—' ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
 
</div>
 
<?php require_once 'layout/footer.php'; ?>
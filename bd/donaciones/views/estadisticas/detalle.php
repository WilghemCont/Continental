<?php
// views/estadisticas/detalle.php
$pct    = min(100, (float)$caso['porcentaje']);
$falta  = max(0, $caso['meta_monto'] - $caso['monto_recaudado']);
?>

<a href="?page=estadisticas" class="btn btn-outline btn-sm mb-6">← Volver al Dashboard</a>

<!-- INFO CASO -->
<div class="grid-2 mb-6">
  <div class="card">
    <div class="card-header">
      <h3>📋 Información del Caso</h3>
      <span class="badge <?= $caso['estado']==='completado'?'badge-success':($caso['estado']==='cerrado'?'badge-warning':'badge-info') ?>">
        <?= ucfirst($caso['estado']) ?>
      </span>
    </div>
    <div class="card-body">
      <h2 style="font-size:18px;font-weight:700;margin-bottom:8px"><?= htmlspecialchars($caso['titulo']) ?></h2>
      <p class="text-gray mb-4" style="font-size:13px"><?= nl2br(htmlspecialchars($caso['descripcion'] ?? '')) ?></p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div><p class="text-sm text-gray">ONG Beneficiaria</p><p class="font-semibold"><?= htmlspecialchars($caso['ong_nombre']) ?></p></div>
        <div><p class="text-sm text-gray">Email ONG</p><p class="font-semibold"><?= htmlspecialchars($caso['ong_email']) ?></p></div>
        <div><p class="text-sm text-gray">Inicio</p><p class="font-semibold"><?= date('d/m/Y',strtotime($caso['fecha_inicio'])) ?></p></div>
        <div><p class="text-sm text-gray">Vencimiento</p><p class="font-semibold"><?= date('d/m/Y',strtotime($caso['fecha_fin'])) ?></p></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h3>📊 Progreso de Recaudación</h3></div>
    <div class="card-body">
      <div style="text-align:center;margin-bottom:20px">
        <div style="font-size:42px;font-weight:800;color:var(--primary)"><?= $pct ?>%</div>
        <div class="text-gray text-sm">de la meta alcanzado</div>
      </div>
      <div class="progress-bar" style="height:14px;margin-bottom:12px">
        <div class="progress-fill <?= $pct>=100?'green':($pct>=70?'':'orange') ?>"
             style="width:<?= $pct ?>%"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;text-align:center">
        <div style="padding:12px;background:var(--success-bg);border-radius:8px">
          <div style="font-size:16px;font-weight:700;color:var(--success)">S/ <?= number_format($caso['monto_recaudado'],2) ?></div>
          <div class="text-sm text-gray">Recaudado</div>
        </div>
        <div style="padding:12px;background:var(--warning-bg);border-radius:8px">
          <div style="font-size:16px;font-weight:700;color:var(--warning)">S/ <?= number_format($falta,2) ?></div>
          <div class="text-sm text-gray">Faltante</div>
        </div>
        <div style="padding:12px;background:var(--primary-light);border-radius:8px">
          <div style="font-size:16px;font-weight:700;color:var(--primary)">S/ <?= number_format($caso['meta_monto'],2) ?></div>
          <div class="text-sm text-gray">Meta Total</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- DONACIONES DEL CASO -->
<div class="card">
  <div class="card-header">
    <h3>💰 Donaciones Recibidas (<?= count($donaciones) ?>)</h3>
    <a href="?page=donaciones&action=nueva" class="btn btn-primary btn-sm">+ Nueva Donación</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Donador</th><th>Monto</th><th>Método</th>
          <th>Código Trans.</th><th>Estado</th><th>Fecha</th>
        </tr>
      </thead>
      <tbody>
      <?php if(empty($donaciones)): ?>
        <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--gray-400)">
          😔 Aún no hay donaciones para este caso
        </td></tr>
      <?php else: ?>
        <?php foreach($donaciones as $d): ?>
        <tr>
          <td class="text-gray">#<?= $d['id'] ?></td>
          <td>
            <div class="font-semibold"><?= htmlspecialchars($d['donador_nombre']) ?></div>
            <div class="text-sm text-gray"><?= htmlspecialchars($d['donador_email']) ?></div>
          </td>
          <td><strong style="color:var(--success)">S/ <?= number_format($d['monto'],2) ?></strong></td>
          <td><span class="badge badge-gray"><?= ucfirst($d['metodo_pago']) ?></span></td>
          <td class="text-sm text-gray"><?= htmlspecialchars($d['codigo_transaccion'] ?? '—') ?></td>
          <td><span class="badge <?= $d['estado']==='verificado'?'badge-success':($d['estado']==='rechazado'?'badge-danger':'badge-warning') ?>">
            <?= ucfirst($d['estado']) ?>
          </span></td>
          <td class="text-sm text-gray"><?= date('d/m/Y H:i',strtotime($d['fecha_donacion'])) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

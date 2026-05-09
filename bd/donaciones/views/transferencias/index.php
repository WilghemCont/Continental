<?php // views/transferencias/index.php ?>

<!-- KPIs -->
<div class="kpi-grid mb-6">
  <div class="kpi-card">
    <div class="kpi-icon purple">📦</div>
    <div class="kpi-info"><p>Total</p><h4><?= $kpis['total'] ?? 0 ?></h4></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon green">✅</div>
    <div class="kpi-info"><p>Completadas</p><h4><?= $kpis['completadas'] ?? 0 ?></h4></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon orange">⏳</div>
    <div class="kpi-info"><p>En Proceso</p><h4><?= $kpis['en_proceso'] ?? 0 ?></h4></div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon blue">💸</div>
    <div class="kpi-info">
      <p>Monto Transferido</p>
      <h4>S/ <?= number_format($kpis['monto_transferido'] ?? 0, 0) ?></h4>
    </div>
  </div>
</div>

<!-- CASOS SIN TRANSFERENCIA -->
<?php if(!empty($casos_pendientes)): ?>
<div class="card mb-6" style="border:2px solid var(--warning)">
  <div class="card-header" style="background:var(--warning-bg)">
    <h3>⚠️ Casos que Alcanzaron la Meta — Pendientes de Transferencia</h3>
  </div>
  <div class="card-body">
    <?php foreach($casos_pendientes as $cp): ?>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:var(--gray-50);border-radius:var(--radius-sm);margin-bottom:8px">
      <div>
        <div class="font-semibold"><?= htmlspecialchars($cp['titulo']) ?></div>
        <div class="text-sm text-gray">🏢 <?= htmlspecialchars($cp['ong_nombre']) ?>
          &nbsp;|&nbsp; 💰 S/ <?= number_format($cp['monto_recaudado'],2) ?>
        </div>
      </div>
      <form method="POST" action="?page=transferencias&action=iniciar" style="display:inline">
        <input type="hidden" name="caso_id" value="<?= $cp['id'] ?>">
        <button type="submit" class="btn btn-warning btn-sm"
          onclick="return confirm('¿Iniciar transferencia por S/ <?= number_format($cp['monto_recaudado'],2) ?>?')">
          💸 Iniciar Transferencia
        </button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- NOTIFICACIONES -->
<?php if(!empty($notificaciones)): ?>
<div class="card mb-6">
  <div class="card-header">
    <h3>🔔 Últimas Notificaciones</h3>
    <a href="?page=transferencias&action=leerNotificaciones" class="btn btn-outline btn-sm">Marcar leídas</a>
  </div>
  <div class="card-body">
    <?php foreach($notificaciones as $n): ?>
    <div class="notif-item <?= !$n['leida']?'unread':'' ?>">
      <div class="flex items-center justify-between">
        <h5><?= htmlspecialchars($n['titulo']) ?></h5>
        <time><?= date('d/m/Y H:i',strtotime($n['created_at'])) ?></time>
      </div>
      <p><?= htmlspecialchars($n['mensaje']) ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- TABLA TRANSFERENCIAS -->
<div class="card">
  <div class="card-header"><h3>🏦 Historial de Transferencias</h3></div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Caso Social</th><th>ONG</th><th>Monto</th><th>Estado</th><th>Documento</th><th>Fecha Trans.</th><th>Acción</th></tr>
      </thead>
      <tbody>
      <?php if(empty($transferencias)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-400)">
          Sin transferencias registradas
        </td></tr>
      <?php else: ?>
        <?php foreach($transferencias as $t): ?>
        <?php
          $badgeCl = match($t['estado']){
            'completado' => 'badge-success',
            'en_proceso' => 'badge-info',
            'pendiente'  => 'badge-warning',
            'rechazado'  => 'badge-danger',
            default      => 'badge-gray'
          };
          $estadoLabel = match($t['estado']){
            'completado' => '✅ Completado',
            'en_proceso' => '⏳ En Proceso',
            'pendiente'  => '🕐 Pendiente',
            'rechazado'  => '❌ Rechazado',
            default      => $t['estado']
          };
        ?>
        <tr>
          <td class="text-gray">#<?= $t['id'] ?></td>
          <td>
            <div class="font-semibold"><?= htmlspecialchars($t['caso_titulo']) ?></div>
            <div class="text-sm text-gray">S/ <?= number_format($t['monto_recaudado'],2) ?> / <?= number_format($t['meta_monto'],2) ?></div>
          </td>
          <td><?= htmlspecialchars($t['ong_nombre']) ?></td>
          <td><strong style="color:var(--primary)">S/ <?= number_format($t['monto'],2) ?></strong></td>
          <td><span class="badge <?= $badgeCl ?>"><?= $estadoLabel ?></span></td>
          <td>
            <?php if($t['documento']): ?>
              <a href="<?= UPLOAD_URL . htmlspecialchars($t['documento']) ?>" target="_blank" class="btn btn-outline btn-sm">📎 Ver</a>
            <?php else: ?>
              <span class="text-gray text-sm">—</span>
            <?php endif; ?>
          </td>
          <td class="text-sm text-gray">
            <?= $t['fecha_transferencia'] ? date('d/m/Y',strtotime($t['fecha_transferencia'])) : '—' ?>
          </td>
          <td>
            <a href="?page=transferencias&action=detalle&id=<?= $t['id'] ?>" class="btn btn-outline btn-sm">👁 Ver</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

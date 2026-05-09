<?php // views/donaciones/index.php ?>

<!-- KPIs -->
<div class="kpi-grid mb-6">
  <div class="kpi-card">
    <div class="kpi-icon purple">💰</div>
    <div class="kpi-info">
      <p>Total Donaciones</p>
      <h4><?= number_format($kpis['total_donaciones'] ?? 0) ?></h4>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon green">📈</div>
    <div class="kpi-info">
      <p>Monto Total</p>
      <h4>S/ <?= number_format($kpis['total_monto'] ?? 0, 0) ?></h4>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon blue">👥</div>
    <div class="kpi-info">
      <p>Donadores Únicos</p>
      <h4><?= $kpis['total_donadores'] ?? 0 ?></h4>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon orange">⚡</div>
    <div class="kpi-info">
      <p>Promedio</p>
      <h4>S/ <?= number_format($kpis['promedio_monto'] ?? 0, 0) ?></h4>
    </div>
  </div>
</div>

<div class="flex items-center justify-between mb-4">
  <h2 style="font-size:16px;font-weight:700">Registro de Donaciones</h2>
  <a href="?page=donaciones&action=nueva" class="btn btn-primary">➕ Nueva Donación</a>
</div>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Donador</th><th>Caso Social</th>
          <th>Monto</th><th>Método</th><th>Código</th>
          <th>Estado</th><th>Fecha</th>
        </tr>
      </thead>
      <tbody>
      <?php if(empty($donaciones)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-400)">
          Sin donaciones registradas
        </td></tr>
      <?php else: ?>
        <?php foreach($donaciones as $d): ?>
        <tr>
          <td class="text-gray">#<?= $d['id'] ?></td>
          <td>
            <div class="font-semibold"><?= htmlspecialchars($d['donador_nombre']) ?></div>
            <div class="text-sm text-gray"><?= htmlspecialchars($d['donador_email']) ?></div>
          </td>
          <td><?= htmlspecialchars($d['caso_titulo']) ?></td>
          <td><strong style="color:var(--success)">S/ <?= number_format($d['monto'],2) ?></strong></td>
          <td>
            <?php $icon=['yape'=>'💜','plin'=>'💙','tarjeta'=>'💳','transferencia'=>'🏦','efectivo'=>'💵'];?>
            <?= $icon[$d['metodo_pago']] ?? '💰' ?> <?= ucfirst($d['metodo_pago']) ?>
          </td>
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

  <?php if($total_pag > 1): ?>
  <div class="card-footer">
    <div class="pagination">
      <?php if($pagina>1): ?>
        <a href="?page=donaciones&pag=<?= $pagina-1 ?>">← Ant</a>
      <?php endif; ?>
      <?php for($p=1;$p<=$total_pag;$p++): ?>
        <?php if($p===$pagina): ?>
          <span class="current"><?= $p ?></span>
        <?php elseif(abs($p-$pagina)<=2): ?>
          <a href="?page=donaciones&pag=<?= $p ?>"><?= $p ?></a>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if($pagina<$total_pag): ?>
        <a href="?page=donaciones&pag=<?= $pagina+1 ?>">Sig →</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

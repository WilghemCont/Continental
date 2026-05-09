<?php
// views/estadisticas/index.php — Dashboard principal
$r  = $resumen   ?? [];
$kd = $kpi_donaciones ?? [];
$kt = $kpi_transferencias ?? [];
?>

<!-- KPI CARDS -->
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon purple">🎯</div>
    <div class="kpi-info">
      <p>Casos Activos</p>
      <h4><?= $r['activos'] ?? 0 ?></h4>
      <small><?= $r['cerrados'] ?? 0 ?> cerrados</small>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon green">💰</div>
    <div class="kpi-info">
      <p>Total Recaudado</p>
      <h4>S/ <?= number_format($r['total_recaudado'] ?? 0, 0) ?></h4>
      <small>Meta: S/ <?= number_format($r['total_meta'] ?? 0, 0) ?></small>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon blue">📋</div>
    <div class="kpi-info">
      <p>Total Donaciones</p>
      <h4><?= number_format($kd['total_donaciones'] ?? 0) ?></h4>
      <small><?= $kd['total_donadores'] ?? 0 ?> donadores</small>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon orange">⚡</div>
    <div class="kpi-info">
      <p>Donación Promedio</p>
      <h4>S/ <?= number_format($kd['promedio_monto'] ?? 0, 0) ?></h4>
      <small>por transacción</small>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon green">✅</div>
    <div class="kpi-info">
      <p>Casos Completados</p>
      <h4><?= $r['completados'] ?? 0 ?></h4>
      <small>Transferencias finalizadas</small>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon purple">🏦</div>
    <div class="kpi-info">
      <p>Monto Transferido</p>
      <h4>S/ <?= number_format($kt['monto_transferido'] ?? 0, 0) ?></h4>
      <small><?= $kt['completadas'] ?? 0 ?> transferencias OK</small>
    </div>
  </div>
</div>

<!-- ALERTAS CASOS PENDIENTES -->
<?php if(!empty($casos_pendientes)): ?>
<div class="flash flash-error mb-6">
  ⚠️ Hay <strong><?= count($casos_pendientes) ?></strong> caso(s) que alcanzaron su meta pero aún no tienen transferencia iniciada.
  <a href="?page=transferencias" style="color:inherit;text-decoration:underline;margin-left:8px">Gestionar →</a>
</div>
<?php endif; ?>

<!-- PROGRESO DE CASOS SOCIALES -->
<div class="mb-6">
  <div class="flex items-center justify-between mb-4">
    <h2 style="font-size:16px;font-weight:700">📈 Monitoreo de Recaudaciones en Tiempo Real</h2>
    <button onclick="recargarProgresos()" class="btn btn-outline btn-sm">🔄 Actualizar</button>
  </div>

  <div class="caso-grid" id="casos-grid">
  <?php foreach($casos as $caso): ?>
    <?php
      $pct     = min(100, (float)$caso['porcentaje']);
      $esComp  = $caso['estado'] === 'completado';
      $esCerr  = $caso['estado'] === 'cerrado';
      $colorBar = $pct >= 100 ? 'green' : ($pct >= 70 ? '' : 'orange');
      $badgeClass = match($caso['estado']) {
        'aprobado'   => 'badge-info',
        'cerrado'    => 'badge-warning',
        'completado' => 'badge-success',
        default      => 'badge-gray',
      };
      $estadoLabel = match($caso['estado']) {
        'aprobado'   => '🟢 Activo',
        'cerrado'    => '🟡 Meta Alcanzada',
        'completado' => '✅ Completado',
        default      => $caso['estado'],
      };
    ?>
    <div class="caso-card" data-id="<?= $caso['id'] ?>">
      <div class="caso-card-head">
        <div>
          <div class="caso-card-title"><?= htmlspecialchars($caso['titulo']) ?></div>
          <div class="caso-card-ong">🏢 <?= htmlspecialchars($caso['ong_nombre']) ?></div>
        </div>
        <span class="badge <?= $badgeClass ?>"><?= $estadoLabel ?></span>
      </div>
      <div class="caso-card-body">
        <div class="caso-montos">
          <span>Recaudado: <strong style="color:var(--success)">S/ <?= number_format($caso['monto_recaudado'],2) ?></strong></span>
          <span>Meta: <strong>S/ <?= number_format($caso['meta_monto'],2) ?></strong></span>
        </div>
        <div class="progress-wrap">
          <div class="progress-bar">
            <div class="progress-fill <?= $colorBar ?>" style="width:<?= $pct ?>%"></div>
          </div>
          <div class="progress-pct"><?= $pct ?>% alcanzado</div>
        </div>
        <div class="text-sm text-gray mt-4">📅 Vence: <?= date('d/m/Y', strtotime($caso['fecha_fin'])) ?></div>
      </div>
      <div class="caso-card-footer">
        <a href="?page=estadisticas&action=detalle&id=<?= $caso['id'] ?>" class="btn btn-outline btn-sm">👁 Ver detalle</a>
        <?php if($esCerr): ?>
          <a href="?page=transferencias" class="btn btn-warning btn-sm">💸 Transferir</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>

<!-- GRÁFICAS -->
<div class="grid-2 mb-6">
  <!-- Recaudación por mes -->
  <div class="card">
    <div class="card-header"><h3>📅 Recaudación Mensual</h3></div>
    <div class="card-body">
      <canvas id="chartMeses" height="200"></canvas>
    </div>
  </div>

  <!-- Métodos de pago -->
  <div class="card">
    <div class="card-header"><h3>💳 Métodos de Pago</h3></div>
    <div class="card-body">
      <canvas id="chartMetodos" height="200"></canvas>
    </div>
  </div>
</div>

<!-- INDICADORES -->
<div class="card mb-6">
  <div class="card-header"><h3>📌 Indicadores de Seguimiento</h3></div>
  <div class="card-body">
    <div class="grid-3">
      <div style="text-align:center;padding:16px;background:var(--gray-50);border-radius:var(--radius-sm)">
        <div style="font-size:28px;font-weight:700;color:var(--primary)">
          <?= $r['total_casos'] > 0 ? round(($r['completados']/$r['total_casos'])*100) : 0 ?>%
        </div>
        <div style="font-size:12px;color:var(--gray-500);margin-top:4px">Casos que alcanzan la meta</div>
      </div>
      <div style="text-align:center;padding:16px;background:var(--gray-50);border-radius:var(--radius-sm)">
        <div style="font-size:28px;font-weight:700;color:var(--success)">
          <?= round($kt['horas_promedio'] ?? 0) ?>h
        </div>
        <div style="font-size:12px;color:var(--gray-500);margin-top:4px">Tiempo prom. de transferencia</div>
      </div>
      <div style="text-align:center;padding:16px;background:var(--gray-50);border-radius:var(--radius-sm)">
        <div style="font-size:28px;font-weight:700;color:var(--warning)">
          <?= $kd['total_donaciones'] ?? 0 ?>
        </div>
        <div style="font-size:12px;color:var(--gray-500);margin-top:4px">Donaciones completadas</div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Datos PHP → JS
const mesMeses  = <?= json_encode(array_column($por_mes ?? [],  'mes'))   ?>;
const mesMontos = <?= json_encode(array_column($por_mes ?? [],  'total'))  ?>;
const metPago   = <?= json_encode(array_column($por_metodo ?? [],'metodo_pago')) ?>;
const metTotal  = <?= json_encode(array_column($por_metodo ?? [],'total'))  ?>;

// Gráfica línea — meses
new Chart(document.getElementById('chartMeses'),{
  type:'bar',
  data:{
    labels:mesMeses,
    datasets:[{label:'S/ Recaudado',data:mesMontos,
      backgroundColor:'rgba(108,71,255,.15)',borderColor:'#6C47FF',
      borderWidth:2,borderRadius:6,fill:true}]
  },
  options:{responsive:true,plugins:{legend:{display:false}},
    scales:{y:{beginAtZero:true,ticks:{callback:v=>'S/ '+Number(v).toLocaleString()}}}}
});

// Gráfica dona — métodos
const colors=['#6C47FF','#10B981','#F59E0B','#3B82F6','#EF4444'];
new Chart(document.getElementById('chartMetodos'),{
  type:'doughnut',
  data:{labels:metPago,datasets:[{data:metTotal,backgroundColor:colors,borderWidth:2,borderColor:'#fff'}]},
  options:{responsive:true,plugins:{legend:{position:'bottom'}}}
});

// Actualización en tiempo real
async function recargarProgresos(){
  const btn = document.querySelector('[onclick="recargarProgresos()"]');
  btn.textContent='⏳ Actualizando...';btn.disabled=true;
  const cards = document.querySelectorAll('.caso-card[data-id]');
  for(const card of cards){
    const id=card.dataset.id;
    const r=await fetch(`?page=estadisticas&action=progreso&id=${id}`).then(r=>r.json());
    if(r && !r.error){
      const fill=card.querySelector('.progress-fill');
      const pctEl=card.querySelector('.progress-pct');
      const monto=card.querySelector('.caso-montos strong');
      const pct=Math.min(100,parseFloat(r.porcentaje)||0);
      if(fill) fill.style.width=pct+'%';
      if(pctEl) pctEl.textContent=pct+'% alcanzado';
      if(monto) monto.textContent='S/ '+parseFloat(r.monto_recaudado).toLocaleString('es-PE',{minimumFractionDigits:2});
    }
  }
  btn.textContent='🔄 Actualizar';btn.disabled=false;
}
</script>

<?php
// views/transferencias/detalle.php
$t = $trans;

// Definir paso actual del stepper
$paso = match($t['estado']) {
  'pendiente'  => 2,
  'en_proceso' => 3,
  'completado' => 5,
  default      => 1,
};
$pasos = [
  ['num'=>1,'label'=>'Meta Alcanzada'],
  ['num'=>2,'label'=>'Notificación ONG'],
  ['num'=>3,'label'=>'Carga Comprobante'],
  ['num'=>4,'label'=>'Confirmación ONG'],
  ['num'=>5,'label'=>'Proceso Cerrado'],
];
?>

<a href="?page=transferencias" class="btn btn-outline btn-sm mb-6">← Volver a Transferencias</a>

<!-- STEPPER -->
<div class="card mb-6">
  <div class="card-body">
    <p style="font-size:12px;font-weight:600;color:var(--gray-500);margin-bottom:16px;text-transform:uppercase;letter-spacing:.5px">
      Proceso de Seguimiento de Donación
    </p>
    <div class="stepper">
      <?php foreach($pasos as $p): ?>
      <?php $cls = $p['num'] < $paso ? 'done' : ($p['num'] === $paso ? 'active' : ''); ?>
      <div class="step <?= $cls ?>">
        <div class="step-circle">
          <?= $p['num'] < $paso ? '✓' : $p['num'] ?>
        </div>
        <div class="step-label"><?= $p['label'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- DETALLE -->
<div class="grid-2 mb-6">
  <div class="card">
    <div class="card-header"><h3>📋 Información de la Transferencia</h3></div>
    <div class="card-body">
      <table style="width:100%">
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px;width:140px">Caso Social</td>
            <td class="font-semibold"><?= htmlspecialchars($t['caso_titulo']) ?></td></tr>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">ONG Beneficiaria</td>
            <td class="font-semibold"><?= htmlspecialchars($t['ong_nombre']) ?></td></tr>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Email ONG</td>
            <td><?= htmlspecialchars($t['ong_email']) ?></td></tr>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Monto a Transferir</td>
            <td><strong style="font-size:20px;color:var(--primary)">S/ <?= number_format($t['monto'],2) ?></strong></td></tr>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Estado</td>
            <td>
              <?php $bc=match($t['estado']){'completado'=>'badge-success','en_proceso'=>'badge-info','pendiente'=>'badge-warning',default=>'badge-gray'}; ?>
              <span class="badge <?= $bc ?>"><?= ucfirst($t['estado']) ?></span>
            </td></tr>
        <?php if($t['fecha_transferencia']): ?>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Fecha Transferencia</td>
            <td><?= date('d/m/Y H:i',strtotime($t['fecha_transferencia'])) ?></td></tr>
        <?php endif; ?>
        <?php if($t['fecha_confirmacion']): ?>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Confirmado el</td>
            <td><?= date('d/m/Y H:i',strtotime($t['fecha_confirmacion'])) ?></td></tr>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Confirmado por</td>
            <td><?= htmlspecialchars($t['confirmado_por'] ?? '—') ?></td></tr>
        <?php endif; ?>
        <?php if($t['notas']): ?>
        <tr><td style="padding:8px 0;color:var(--gray-500);font-size:12px">Notas</td>
            <td><?= nl2br(htmlspecialchars($t['notas'])) ?></td></tr>
        <?php endif; ?>
      </table>
    </div>
  </div>

  <!-- PANEL ACCIONES -->
  <div>
    <?php if($t['documento']): ?>
    <!-- DOCUMENTO SUBIDO -->
    <div class="card mb-4">
      <div class="card-header"><h3>📎 Comprobante de Transferencia</h3></div>
      <div class="card-body" style="text-align:center">
        <?php $ext = pathinfo($t['documento'], PATHINFO_EXTENSION); ?>
        <?php if(in_array($ext,['jpg','jpeg','png','webp'])): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($t['documento']) ?>"
               alt="Comprobante" style="max-width:100%;max-height:300px;border-radius:8px;margin-bottom:12px">
        <?php else: ?>
          <div style="font-size:48px;margin-bottom:12px">📄</div>
          <p class="text-gray mb-4">Documento PDF adjunto</p>
        <?php endif; ?>
        <a href="<?= UPLOAD_URL . htmlspecialchars($t['documento']) ?>"
           target="_blank" class="btn btn-outline">📥 Descargar comprobante</a>
      </div>
    </div>
    <?php endif; ?>

    <!-- SUBIR COMPROBANTE (si está pendiente o en_proceso) -->
    <?php if(in_array($t['estado'],['pendiente','en_proceso'])): ?>
    <div class="card mb-4">
      <div class="card-header"><h3>📤 Subir Comprobante de Pago</h3></div>
      <div class="card-body">
        <form method="POST" action="?page=transferencias&action=subirComprobante"
              enctype="multipart/form-data">
          <input type="hidden" name="transferencia_id" value="<?= $t['id'] ?>">

          <div class="form-group">
            <label>Documento / Foto de transferencia <span class="req">*</span></label>
            <div class="file-zone">
              <input type="file" name="documento" accept=".jpg,.jpeg,.png,.pdf,.webp" required>
              <div style="font-size:32px">📎</div>
              <p class="file-label">Arrastra o haz clic para seleccionar<br>
                <small>JPG, PNG o PDF — máx. 5 MB</small></p>
            </div>
          </div>

          <div class="form-group">
            <label>Notas de la transferencia</label>
            <textarea name="notas" class="form-control"
              placeholder="Banco utilizado, fecha de operación, número de cuenta destino..."><?= htmlspecialchars($t['notas'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary">📤 Subir Comprobante</button>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <!-- CONFIRMAR RECEPCIÓN (si está en_proceso) -->
    <?php if($t['estado']==='en_proceso'): ?>
    <div class="card" style="border:2px solid var(--success)">
      <div class="card-header" style="background:var(--success-bg)">
        <h3>✅ Confirmar Recepción de Fondos</h3>
      </div>
      <div class="card-body">
        <p class="text-gray mb-4" style="font-size:13px">
          Una vez que la ONG confirme haber recibido los fondos, el proceso se cerrará definitivamente.
        </p>
        <form method="POST" action="?page=transferencias&action=confirmar">
          <input type="hidden" name="transferencia_id" value="<?= $t['id'] ?>">
          <div class="form-group">
            <label>Confirmado por <span class="req">*</span></label>
            <input type="text" name="confirmado_por" class="form-control"
                   placeholder="Nombre del representante de la ONG" required>
          </div>
          <button type="submit" class="btn btn-success btn-lg"
            onclick="return confirm('¿Confirmar recepción de S/ <?= number_format($t['monto'],2) ?>?')">
            ✅ Confirmar Recepción y Cerrar Caso
          </button>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <!-- COMPLETADO -->
    <?php if($t['estado']==='completado'): ?>
    <div class="card" style="border:2px solid var(--success);text-align:center">
      <div class="card-body" style="padding:32px">
        <div style="font-size:48px;margin-bottom:12px">🎉</div>
        <h3 style="color:var(--success);font-size:18px;margin-bottom:8px">Proceso Completado</h3>
        <p class="text-gray">La transferencia fue confirmada y el caso social ha sido cerrado exitosamente.</p>
        <p style="margin-top:12px;font-size:13px;color:var(--gray-500)">
          Confirmado el <?= date('d/m/Y H:i',strtotime($t['fecha_confirmacion'])) ?>
          por <?= htmlspecialchars($t['confirmado_por'] ?? 'Sistema') ?>
        </p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

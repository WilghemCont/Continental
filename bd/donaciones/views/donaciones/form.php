<?php // views/donaciones/form.php ?>

<a href="?page=donaciones" class="btn btn-outline btn-sm mb-6">← Volver</a>

<div style="max-width:720px;margin:0 auto">
  <div class="card">
    <div class="card-header">
      <h3>💰 Registrar Nueva Donación</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="?page=donaciones&action=guardar">

        <!-- PASO 1: Caso Social -->
        <div style="background:var(--primary-light);padding:14px 18px;border-radius:var(--radius-sm);margin-bottom:20px">
          <p style="font-size:12px;font-weight:600;color:var(--primary);margin-bottom:8px">PASO 1 — Selecciona el Caso Social</p>
          <div class="form-group" style="margin:0">
            <select name="caso_social_id" class="form-control" required>
              <option value="">— Seleccionar caso social —</option>
              <?php foreach($casos as $c): ?>
              <option value="<?= $c['id'] ?>">
                <?= htmlspecialchars($c['titulo']) ?>
                — S/ <?= number_format($c['monto_recaudado'],0) ?> / <?= number_format($c['meta_monto'],0) ?>
                (<?= $c['porcentaje'] ?>%)
                [<?= htmlspecialchars($c['ong_nombre']) ?>]
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- PASO 2: Datos del donador -->
        <div style="background:var(--gray-50);padding:14px 18px;border-radius:var(--radius-sm);margin-bottom:20px">
          <p style="font-size:12px;font-weight:600;color:var(--gray-500);margin-bottom:14px">PASO 2 — Datos del Donador</p>
          <div class="form-row">
            <div class="form-group">
              <label>Nombre completo <span class="req">*</span></label>
              <input type="text" name="donador_nombre" class="form-control" placeholder="Ej: Carlos Mendoza" required>
            </div>
            <div class="form-group">
              <label>Correo electrónico <span class="req">*</span></label>
              <input type="email" name="donador_email" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>
          </div>
          <div class="form-group">
            <label>Teléfono (opcional)</label>
            <input type="tel" name="donador_telefono" class="form-control" placeholder="999 888 777">
          </div>
        </div>

        <!-- PASO 3: Detalles del pago -->
        <div style="background:var(--gray-50);padding:14px 18px;border-radius:var(--radius-sm);margin-bottom:20px">
          <p style="font-size:12px;font-weight:600;color:var(--gray-500);margin-bottom:14px">PASO 3 — Detalles del Pago</p>
          <div class="form-row">
            <div class="form-group">
              <label>Monto (S/) <span class="req">*</span></label>
              <input type="number" name="monto" class="form-control" step="0.01" min="1" placeholder="0.00" required>
            </div>
            <div class="form-group">
              <label>Método de pago <span class="req">*</span></label>
              <select name="metodo_pago" class="form-control" required>
                <option value="">— Seleccionar —</option>
                <option value="yape">💜 Yape</option>
                <option value="plin">💙 Plin</option>
                <option value="transferencia">🏦 Transferencia Bancaria</option>
                <option value="tarjeta">💳 Tarjeta de Crédito/Débito</option>
                <option value="efectivo">💵 Efectivo</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Código de transacción (opcional)</label>
            <input type="text" name="codigo_transaccion" class="form-control" placeholder="Ej: BCP20250501001">
            <p class="form-hint">Código de operación, número de transacción o referencia del pago</p>
          </div>
          <div class="form-group">
            <label>Notas adicionales</label>
            <textarea name="notas" class="form-control" placeholder="Observaciones sobre la donación..."></textarea>
          </div>
        </div>

        <div class="flex gap-3">
          <button type="submit" class="btn btn-primary btn-lg">✅ Registrar Donación</button>
          <a href="?page=donaciones" class="btn btn-outline btn-lg">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>

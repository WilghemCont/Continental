<?php
require_once 'layout/header.php';
?>

<div class="container py-5 fade-up">

  <!-- Volver -->
  <div class="mb-4">
    <a href="index.php?controller=donacion&action=panel"
       class="text-decoration-none text-muted hover-back px-4 py-2 rounded-pill d-inline-flex align-items-center bg-white shadow-sm">
      <i class="bi bi-arrow-left me-2"></i> Volver al Panel
    </a>
  </div>

  <!-- Hero -->
  <div class="row g-5 align-items-center mb-5">
    <div class="col-lg-5 d-none d-lg-block">
      <span class="about-badge mb-3 d-inline-block">Gestión Financiera</span>
      <h1 class="about-title mb-4" style="font-size:2.8rem;">Registro de Ingresos</h1>
      <p class="about-text fs-5 mb-4">
        Centraliza las comisiones, donaciones voluntarias y patrocinios corporativos
        que sostienen el funcionamiento de la plataforma SocialFunding.
      </p>
      <div class="row g-3">
        <div class="col-6">
          <div class="p-3 bg-white shadow-sm rounded-4 border-0">
            <i class="bi bi-graph-up-arrow text-primary fs-3"></i>
            <h6 class="fw-bold mt-2">Trazabilidad</h6>
            <p class="small text-muted mb-0">Historial detallado</p>
          </div>
        </div>
        <div class="col-6">
          <div class="p-3 bg-white shadow-sm rounded-4 border-0">
            <i class="bi bi-building-check text-success fs-3"></i>
            <h6 class="fw-bold mt-2">Patrocinios</h6>
            <p class="small text-muted mb-0">Empresas y personas</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-7 col-md-10 mx-auto">

      <!-- Selector de tipo -->
      <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius:20px;">
        <p class="small fw-bold text-muted text-uppercase mb-3">Selecciona el tipo de ingreso</p>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-outline-primary btn-sm tipo-btn"
                  onclick="mostrarBloque('plataforma')" id="btn-plataforma">
            <i class="bi bi-building me-1"></i> Donación Plataforma
          </button>
          <button class="btn btn-outline-success btn-sm tipo-btn"
                  onclick="mostrarBloque('caso_social')" id="btn-caso_social">
            <i class="bi bi-percent me-1"></i> Comisiones
          </button>
          <button class="btn btn-warning btn-sm tipo-btn active"
                  onclick="mostrarBloque('patrocinio')" id="btn-patrocinio">
            <i class="bi bi-briefcase-fill me-1"></i> Patrocinio
          </button>
        </div>
      </div>

      <!-- Bloque: Donaciones directas a plataforma -->
      <div id="bloque-plataforma" style="display:none;">
        <div class="card shadow-sm border-0 p-4" style="border-radius:20px;">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                 style="width:44px;height:44px;">
              <i class="bi bi-building text-primary fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0">Donaciones a la Plataforma</h5>
              <p class="small text-muted mb-0">Historial de ingresos directos</p>
            </div>
          </div>
          <p class="text-muted small">
            Este módulo muestra los ingresos directos registrados. Para añadir uno nuevo,
            usa el formulario de <strong>Patrocinio</strong> seleccionando tipo de aporte <em>Económico</em>.
          </p>
          <a href="ingresos.php" class="btn btn-outline-primary btn-sm mt-2">
            <i class="bi bi-eye me-1"></i> Ver gestión de ingresos completa
          </a>
        </div>
      </div>

      <!-- Bloque: Comisiones -->
      <div id="bloque-caso" style="display:none;">
        <div class="card shadow-sm border-0 p-4" style="border-radius:20px;">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                 style="width:44px;height:44px;">
              <i class="bi bi-percent text-success fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0">Comisiones por Casos</h5>
              <p class="small text-muted mb-0">Ingresos generados por casos sociales activos</p>
            </div>
          </div>
          <p class="text-muted small">
            Las comisiones se calculan automáticamente al procesar donaciones mediante MercadoPago.
            El porcentaje aplicado es del <strong>3 %</strong> para montos hasta S/ 10,000
            y del <strong>5 %</strong> para montos superiores.
          </p>
          <a href="estadistica.php" class="btn btn-outline-success btn-sm mt-2">
            <i class="bi bi-bar-chart me-1"></i> Ver estadísticas
          </a>
        </div>
      </div>

      <!-- Bloque: Registro de patrocinio -->
      <div id="bloque-patrocinio">
        <div class="card shadow-lg border-0 overflow-hidden" style="border-radius:25px;">

          <div class="p-4 text-center text-white" style="background:var(--grad);">
            <div class="display-5 mb-2"><i class="bi bi-briefcase-fill"></i></div>
            <h3 class="fw-bold text-white mb-1">Registro de Patrocinio</h3>
            <p class="opacity-75 mb-0 small">Registra un patrocinador nuevo o selecciona uno existente</p>
          </div>

          <div class="card-body p-4 bg-white">
            <form id="formPatrocinio" enctype="multipart/form-data">
              <input type="hidden" name="donador_id" id="donador_id_hidden">

              <!-- Buscador de donador -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Buscar por DNI / RUC</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="buscar_donador" class="form-control border-start-0"
                           placeholder="Escribe DNI o RUC...">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Donador registrado</label>
                  <select id="donador_existente" class="form-select" onchange="cargarDonador(this)">
                    <option value="">— Nuevo donador —</option>
                    <?php foreach ($donadores as $d): ?>
                    <option value="<?= $d['id'] ?>"
                            data-id="<?= $d['id'] ?>"
                            data-nombre="<?= htmlspecialchars($d['nombre']) ?>"
                            data-email="<?= htmlspecialchars($d['email']) ?>"
                            data-documento="<?= htmlspecialchars($d['documento'] ?? '') ?>"
                            data-telefono="<?= htmlspecialchars($d['telefono'] ?? '') ?>"
                            data-tipo="<?= htmlspecialchars($d['tipo']) ?>">
                      <?= htmlspecialchars($d['nombre']) ?> — <?= htmlspecialchars($d['documento'] ?? '') ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <hr class="my-4">

              <!-- Datos del donador -->
              <p class="small fw-bold text-muted text-uppercase mb-3">Datos del donador</p>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Nombre completo</label>
                  <input type="text" name="donador_nombre" id="donador_nombre"
                         class="form-control" placeholder="Ej: Juan Pérez García">
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Correo electrónico</label>
                  <input type="email" name="donador_email" id="donador_email"
                         class="form-control" placeholder="correo@ejemplo.com">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-bold text-muted text-uppercase">Documento / RUC</label>
                  <input type="text" name="donador_documento" id="donador_documento"
                         class="form-control" placeholder="12345678">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-bold text-muted text-uppercase">Teléfono</label>
                  <input type="text" name="donador_telefono" id="donador_telefono"
                         class="form-control" placeholder="+51 999 000 000">
                </div>
                <div class="col-md-4">
                  <label class="form-label small fw-bold text-muted text-uppercase">Tipo</label>
                  <select name="donador_tipo" id="donador_tipo" class="form-select">
                    <option value="persona">👤 Persona natural</option>
                    <option value="empresa">🏢 Empresa</option>
                  </select>
                </div>
              </div>

              <hr class="my-4">

              <!-- Detalles del patrocinio -->
              <p class="small fw-bold text-muted text-uppercase mb-3">Detalles del patrocinio</p>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Tipo de aporte</label>
                  <select name="tipo_aporte" class="form-select">
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="bienes">📦 Bienes</option>
                    <option value="servicios">⚙️ Servicios</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">
                    Monto o valor estimado <span class="text-danger">*</span>
                  </label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0 fw-bold text-muted">S/</span>
                    <input type="number" step="0.01" min="1" name="monto_total"
                           class="form-control border-start-0" required placeholder="0.00">
                  </div>
                </div>
                <div class="col-12">
                  <label class="form-label small fw-bold text-muted text-uppercase">Descripción</label>
                  <textarea name="descripcion_patrocinio" class="form-control" rows="3"
                            placeholder="Describe el propósito o condiciones del patrocinio..."></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Evidencia / Comprobante</label>
                  <input type="file" name="evidencia" class="form-control"
                         accept=".jpg,.jpeg,.png,.pdf,.webp">
                  <div class="form-text">Formatos: JPG, PNG, PDF, WEBP (máx. 5 MB)</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-muted text-uppercase">Notas adicionales</label>
                  <textarea name="notas" class="form-control" rows="2"
                            placeholder="Observaciones internas..."></textarea>
                </div>
              </div>

              <div id="msg-resultado" class="alert mt-3" style="display:none;"></div>

              <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                  <i class="bi bi-check2-circle me-2"></i>Registrar patrocinio
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div><!-- /col -->
  </div><!-- /row -->

</div>

<?php require_once 'layout/footer.php'; ?>

<script>
/* ── Selector de tipo ─────────────────────────────────── */
function mostrarBloque(tipo) {
  ['plataforma','caso','patrocinio'].forEach(b => {
    document.getElementById('bloque-' + b).style.display = 'none';
  });
  ['plataforma','caso_social','patrocinio'].forEach(b => {
    document.getElementById('btn-' + b).classList.remove('active','btn-warning','btn-primary','btn-success');
  });

  const bloqueMap  = { plataforma:'plataforma', caso_social:'caso', patrocinio:'patrocinio' };
  const claseMap   = { plataforma:'btn-primary', caso_social:'btn-success', patrocinio:'btn-warning' };

  document.getElementById('bloque-' + bloqueMap[tipo]).style.display = 'block';
  document.getElementById('btn-' + tipo).classList.add('active', claseMap[tipo]);
}

/* ── Buscador de donador ──────────────────────────────── */
const buscar   = document.getElementById('buscar_donador');
const selectDon = document.getElementById('donador_existente');

if (buscar) {
  buscar.addEventListener('input', function () {
    const txt = this.value.trim().toLowerCase();
    let encontrado = false;

    for (let i = 1; i < selectDon.options.length; i++) {
      const opt      = selectDon.options[i];
      const nombre   = (opt.dataset.nombre   || '').toLowerCase();
      const documento= (opt.dataset.documento|| '').toLowerCase();
      const coincide = nombre.includes(txt) || documento.includes(txt);
      opt.hidden = !coincide;

      if (!encontrado && txt && coincide) {
        selectDon.value = opt.value;
        cargarDonador(selectDon);
        encontrado = true;
      }
    }

    if (!encontrado && txt) {
      selectDon.value = '';
      limpiarDonador();
      document.getElementById('donador_documento').value = txt;
    }

    if (!txt) {
      selectDon.value = '';
      limpiarDonador();
      for (let i = 1; i < selectDon.options.length; i++) selectDon.options[i].hidden = false;
    }
  });
}

/* ── Cargar / limpiar donador ─────────────────────────── */
function cargarDonador(select) {
  const opt = select.options[select.selectedIndex];
  if (!opt || !opt.value) { limpiarDonador(); return; }

  document.getElementById('donador_id_hidden').value    = opt.dataset.id       || '';
  document.getElementById('donador_nombre').value       = opt.dataset.nombre   || '';
  document.getElementById('donador_email').value        = opt.dataset.email    || '';
  document.getElementById('donador_documento').value    = opt.dataset.documento|| '';
  document.getElementById('donador_telefono').value     = opt.dataset.telefono || '';
  document.getElementById('donador_tipo').value         = opt.dataset.tipo     || 'persona';
}

function limpiarDonador() {
  ['donador_id_hidden','donador_nombre','donador_email','donador_documento','donador_telefono']
    .forEach(id => { const el = document.getElementById(id); if(el) el.value = ''; });
  const tipo = document.getElementById('donador_tipo');
  if (tipo) tipo.value = 'persona';
}

/* ── Envío del formulario (AJAX) ──────────────────────── */
const form    = document.getElementById('formPatrocinio');
const msgBox  = document.getElementById('msg-resultado');

if (form) {
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = form.querySelector('[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';

    try {
      const resp = await fetch('index.php?controller=donacion&action=procesarPatrocinio', {
        method: 'POST',
        body:   new FormData(form)
      });
      const data = await resp.json();

      if (!resp.ok || !data.success) throw new Error(data.error || 'No se pudo registrar');

      msgBox.className = 'alert alert-success mt-3';
      msgBox.style.display = 'block';
      msgBox.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>
        <strong>Patrocinio registrado correctamente.</strong>
        Comisión aplicada: <strong>S/ ${parseFloat(data.comision).toFixed(2)}</strong> —
        Monto neto: <strong>S/ ${parseFloat(data.neto).toFixed(2)}</strong>`;
      form.reset();

    } catch (err) {
      msgBox.className = 'alert alert-danger mt-3';
      msgBox.style.display = 'block';
      msgBox.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>${err.message}`;

    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Registrar patrocinio';
    }
  });
}
</script>

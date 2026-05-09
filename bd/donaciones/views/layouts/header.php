<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titulo ?? 'Gestión de Donaciones') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── Reset & Base ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --primary:#6C47FF;--primary-dark:#5534E0;--primary-light:#EEE9FF;
  --success:#10B981;--success-bg:#ECFDF5;
  --warning:#F59E0B;--warning-bg:#FFFBEB;
  --danger:#EF4444; --danger-bg:#FEF2F2;
  --info:#3B82F6;   --info-bg:#EFF6FF;
  --gray-50:#F9FAFB;--gray-100:#F3F4F6;--gray-200:#E5E7EB;
  --gray-300:#D1D5DB;--gray-400:#9CA3AF;--gray-500:#6B7280;
  --gray-700:#374151;--gray-900:#111827;
  --sidebar-w:250px;--radius:12px;--radius-sm:8px;
  --shadow:0 1px 3px rgba(0,0,0,.1),0 1px 2px rgba(0,0,0,.06);
  --shadow-md:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06);
  --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);
}
body{font-family:'Inter',sans-serif;background:var(--gray-50);color:var(--gray-900);display:flex;min-height:100vh}

/* ── Sidebar ── */
.sidebar{width:var(--sidebar-w);background:#1E1B4B;min-height:100vh;position:fixed;top:0;left:0;display:flex;flex-direction:column;z-index:100}
.sidebar-brand{padding:24px 20px;border-bottom:1px solid rgba(255,255,255,.1)}
.sidebar-brand h2{color:#fff;font-size:16px;font-weight:700;line-height:1.3}
.sidebar-brand span{color:var(--primary);font-size:22px}
.sidebar-brand p{color:rgba(255,255,255,.5);font-size:11px;margin-top:4px}
.sidebar-nav{padding:16px 0;flex:1}
.nav-label{color:rgba(255,255,255,.3);font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:1px;padding:8px 20px 4px}
.sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;color:rgba(255,255,255,.7);text-decoration:none;font-size:13.5px;transition:.2s;border-left:3px solid transparent}
.sidebar-nav a:hover{background:rgba(255,255,255,.07);color:#fff}
.sidebar-nav a.active{background:rgba(108,71,255,.25);color:#fff;border-left-color:var(--primary)}
.sidebar-nav a .ico{font-size:16px;width:20px;text-align:center}
.sidebar-nav a .badge-nav{background:var(--danger);color:#fff;font-size:10px;padding:2px 6px;border-radius:20px;margin-left:auto;font-weight:600}
.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1)}
.sidebar-footer p{color:rgba(255,255,255,.4);font-size:11px}

/* ── Main ── */
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{background:#fff;border-bottom:1px solid var(--gray-200);padding:14px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.topbar h1{font-size:17px;font-weight:600;color:var(--gray-900)}
.topbar-actions{display:flex;align-items:center;gap:12px}
.content{padding:28px;flex:1}

/* ── Botones ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:var(--radius-sm);font-size:13.5px;font-weight:500;cursor:pointer;border:none;text-decoration:none;transition:.2s;white-space:nowrap}
.btn-primary{background:var(--primary);color:#fff}.btn-primary:hover{background:var(--primary-dark)}
.btn-success{background:var(--success);color:#fff}.btn-success:hover{background:#059669}
.btn-warning{background:var(--warning);color:#fff}.btn-warning:hover{background:#D97706}
.btn-danger{background:var(--danger);color:#fff}.btn-danger:hover{background:#DC2626}
.btn-outline{background:#fff;color:var(--gray-700);border:1px solid var(--gray-200)}.btn-outline:hover{background:var(--gray-50)}
.btn-sm{padding:6px 12px;font-size:12px}
.btn-lg{padding:12px 24px;font-size:15px}

/* ── Cards ── */
.card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
.card-header{padding:18px 22px;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between}
.card-header h3{font-size:15px;font-weight:600;color:var(--gray-900)}
.card-body{padding:22px}
.card-footer{padding:14px 22px;border-top:1px solid var(--gray-100);background:var(--gray-50)}

/* ── KPI Cards ── */
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px;margin-bottom:24px}
.kpi-card{background:#fff;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);display:flex;align-items:center;gap:16px}
.kpi-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.kpi-icon.purple{background:var(--primary-light);color:var(--primary)}
.kpi-icon.green{background:var(--success-bg);color:var(--success)}
.kpi-icon.orange{background:var(--warning-bg);color:var(--warning)}
.kpi-icon.blue{background:var(--info-bg);color:var(--info)}
.kpi-icon.red{background:var(--danger-bg);color:var(--danger)}
.kpi-info p{font-size:11px;color:var(--gray-500);font-weight:500;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px}
.kpi-info h4{font-size:22px;font-weight:700;color:var(--gray-900)}
.kpi-info small{font-size:11px;color:var(--gray-400)}

/* ── Progress bar ── */
.progress-wrap{margin:8px 0}
.progress-bar{height:8px;background:var(--gray-100);border-radius:99px;overflow:hidden}
.progress-fill{height:100%;border-radius:99px;transition:width .5s ease;background:var(--primary)}
.progress-fill.green{background:var(--success)}
.progress-fill.orange{background:var(--warning)}
.progress-pct{font-size:12px;font-weight:600;color:var(--gray-700);margin-top:4px}

/* ── Tabla ── */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13.5px}
th{background:var(--gray-50);color:var(--gray-500);font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;text-align:left;border-bottom:1px solid var(--gray-200)}
td{padding:11px 14px;border-bottom:1px solid var(--gray-100);color:var(--gray-700)}
tr:last-child td{border-bottom:none}
tr:hover td{background:var(--gray-50)}

/* ── Badges ── */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600}
.badge-success{background:var(--success-bg);color:#065F46}
.badge-warning{background:var(--warning-bg);color:#92400E}
.badge-danger{background:var(--danger-bg);color:#991B1B}
.badge-info{background:var(--info-bg);color:#1E40AF}
.badge-gray{background:var(--gray-100);color:var(--gray-600)}
.badge-purple{background:var(--primary-light);color:var(--primary-dark)}

/* ── Alertas flash ── */
.flash{padding:12px 18px;border-radius:var(--radius-sm);margin-bottom:20px;font-size:13.5px;font-weight:500;display:flex;align-items:center;gap:8px;animation:slideIn .3s ease}
.flash-success{background:var(--success-bg);color:#065F46;border:1px solid #A7F3D0}
.flash-error{background:var(--danger-bg);color:#991B1B;border:1px solid #FECACA}
@keyframes slideIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}

/* ── Grid Layout helpers ── */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.mb-4{margin-bottom:16px}.mb-6{margin-bottom:24px}.mt-4{margin-top:16px}
.flex{display:flex}.items-center{align-items:center}.justify-between{justify-content:space-between}
.gap-3{gap:12px}.gap-2{gap:8px}
.text-sm{font-size:12px}.text-gray{color:var(--gray-500)}
.text-right{text-align:right}
.font-bold{font-weight:700}.font-semibold{font-weight:600}

/* ── Form ── */
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:13px;font-weight:500;color:var(--gray-700);margin-bottom:6px}
.form-group label span.req{color:var(--danger)}
.form-control{width:100%;padding:9px 13px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-size:13.5px;font-family:inherit;transition:.2s;background:#fff}
.form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(108,71,255,.1)}
.form-control::placeholder{color:var(--gray-400)}
select.form-control{cursor:pointer}
textarea.form-control{resize:vertical;min-height:90px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-hint{font-size:11px;color:var(--gray-400);margin-top:4px}

/* ── Caso card (estadísticas) ── */
.caso-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:18px;margin-bottom:24px}
.caso-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:.2s}
.caso-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px)}
.caso-card-head{padding:16px 18px;display:flex;align-items:flex-start;justify-content:space-between;gap:8px}
.caso-card-title{font-size:14px;font-weight:600;color:var(--gray-900);margin-bottom:3px}
.caso-card-ong{font-size:11px;color:var(--gray-400)}
.caso-card-body{padding:0 18px 16px}
.caso-montos{display:flex;justify-content:space-between;font-size:12px;color:var(--gray-500);margin-bottom:6px}
.caso-montos strong{color:var(--gray-900)}
.caso-card-footer{padding:12px 18px;background:var(--gray-50);border-top:1px solid var(--gray-100);display:flex;gap:8px}

/* ── Stepper proceso ── */
.stepper{display:flex;gap:0;margin-bottom:28px;overflow-x:auto;padding-bottom:4px}
.step{flex:1;min-width:130px;display:flex;flex-direction:column;align-items:center;position:relative;padding:0 8px}
.step::before{content:'';position:absolute;top:16px;left:-50%;right:50%;height:2px;background:var(--gray-200);z-index:0}
.step:first-child::before{display:none}
.step.done::before,.step.active::before{background:var(--primary)}
.step-circle{width:32px;height:32px;border-radius:50%;border:2px solid var(--gray-200);background:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;z-index:1;position:relative;font-weight:600;transition:.3s}
.step.done .step-circle{background:var(--primary);border-color:var(--primary);color:#fff}
.step.active .step-circle{border-color:var(--primary);color:var(--primary);box-shadow:0 0 0 4px rgba(108,71,255,.15)}
.step-label{font-size:11px;font-weight:500;color:var(--gray-400);margin-top:6px;text-align:center}
.step.done .step-label,.step.active .step-label{color:var(--primary);font-weight:600}

/* ── Paginación ── */
.pagination{display:flex;gap:4px;justify-content:center;margin-top:20px}
.pagination a,.pagination span{padding:7px 13px;border-radius:var(--radius-sm);font-size:13px;border:1px solid var(--gray-200);color:var(--gray-700);text-decoration:none;transition:.2s}
.pagination a:hover{background:var(--primary-light);border-color:var(--primary);color:var(--primary)}
.pagination .current{background:var(--primary);color:#fff;border-color:var(--primary)}

/* ── File upload ── */
.file-zone{border:2px dashed var(--gray-300);border-radius:var(--radius);padding:32px;text-align:center;cursor:pointer;transition:.2s;background:var(--gray-50)}
.file-zone:hover{border-color:var(--primary);background:var(--primary-light)}
.file-zone input{display:none}
.file-zone p{font-size:13px;color:var(--gray-500);margin-top:8px}

/* ── Notificaciones ── */
.notif-item{padding:12px 16px;border-left:3px solid var(--primary);background:#fff;border-radius:0 var(--radius-sm) var(--radius-sm) 0;margin-bottom:8px;box-shadow:var(--shadow)}
.notif-item.unread{border-left-color:var(--warning);background:var(--warning-bg)}
.notif-item h5{font-size:13px;font-weight:600;margin-bottom:3px}
.notif-item p{font-size:12px;color:var(--gray-500)}
.notif-item time{font-size:11px;color:var(--gray-400)}

@media(max-width:768px){
  .sidebar{width:60px}.sidebar-brand h2,.sidebar-brand p,.nav-label,.sidebar-nav a span:not(.ico),.sidebar-footer{display:none}
  .main{margin-left:60px}.form-row{grid-template-columns:1fr}.grid-2,.grid-3{grid-template-columns:1fr}
}
</style>
</head>
<body>

<?php
$currentPage = $_GET['page'] ?? 'estadisticas';
$notifCount  = $notif_count ?? 0;
?>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <h2><span>💙</span> Sistema de<br>Donaciones</h2>
    <p>Panel Administrativo</p>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Principal</div>
    <a href="?page=estadisticas" class="<?= $currentPage==='estadisticas'?'active':'' ?>">
      <span class="ico">📊</span><span>Dashboard</span>
    </a>
    <a href="?page=donaciones" class="<?= $currentPage==='donaciones'?'active':'' ?>">
      <span class="ico">💰</span><span>Donaciones</span>
    </a>
    <a href="?page=transferencias" class="<?= $currentPage==='transferencias'?'active':'' ?>">
      <span class="ico">🏦</span><span>Transferencias</span>
      <?php if($notifCount>0): ?>
        <span class="badge-nav"><?= $notifCount ?></span>
      <?php endif; ?>
    </a>

    <div class="nav-label">Acciones</div>
    <a href="?page=donaciones&action=nueva">
      <span class="ico">➕</span><span>Nueva Donación</span>
    </a>
  </nav>
  <div class="sidebar-footer">
    <p>v1.0 — XAMPP/PHP</p>
  </div>
</aside>

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <h1><?= htmlspecialchars($titulo ?? '') ?></h1>
    <div class="topbar-actions">
      <?php if($notifCount>0): ?>
        <a href="?page=transferencias" class="btn btn-outline btn-sm">
          🔔 <?= $notifCount ?> notif.
        </a>
      <?php endif; ?>
      <span class="text-sm text-gray">📅 <?= date('d/m/Y') ?></span>
    </div>
  </div>

  <div class="content">

    <?php /* Flash messages */
    if (!empty($_SESSION['flash'])):
      $f = $_SESSION['flash']; unset($_SESSION['flash']);
    ?>
    <div class="flash flash-<?= $f['type'] ?>">
      <?= $f['type']==='success' ? '✅' : '⚠️' ?>
      <?= htmlspecialchars($f['msg']) ?>
    </div>
    <?php endif; ?>

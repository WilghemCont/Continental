<?php
// ============================================================
// index.php — Router principal MVC (raíz del proyecto)
// ============================================================
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/CasoSocialModel.php';
require_once __DIR__ . '/models/DonacionModel.php';
require_once __DIR__ . '/models/TransferenciaModel.php';
require_once __DIR__ . '/models/NotificacionModel.php';
require_once __DIR__ . '/controllers/DonacionController.php';
require_once __DIR__ . '/controllers/EstadisticaController.php';
require_once __DIR__ . '/controllers/TransferenciaController.php';

// ── Router simple por ?page=xxx ──────────────────────────────
$page   = $_GET['page']   ?? 'estadisticas';
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'donaciones':
        $ctrl = new DonacionController();
        break;
    case 'transferencias':
        $ctrl = new TransferenciaController();
        break;
    case 'estadisticas':
    default:
        $ctrl = new EstadisticaController();
        break;
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    $ctrl->index();
}

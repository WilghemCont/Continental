<?php
/**
 * Controllers/BeneficiarioController.php
 * Controlador AJAX para la página del beneficiario.
 * Recibe operaciones via ?op=... y devuelve JSON o texto plano.
 */
session_start();
require_once("../config/conexion.php");
require_once("../models/BeneficiarioModel.php");

// ── Seguridad: solo usuarios BENEFICIARIO autenticados ──
if (!isset($_SESSION['idlogin']) || ($_SESSION['tipo'] ?? '') !== 'BENEFICIARIO') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado']);
    exit;
}

$model = new BeneficiarioModel();
$op    = $_GET['op'] ?? '';

switch ($op) {

    // ── Guardar o actualizar testimonio ──
    case 'guardar_testimonio':
        $caso_id   = (int) ($_POST['caso_id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if ($caso_id <= 0 || $contenido === '') {
            echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
            exit;
        }

        // Verificar que el caso pertenece a este beneficiario
        $caso = $model->obtenerCasoPorBeneficiario((int) $_SESSION['idusuario']);
        if (!$caso || (int) $caso['id'] !== $caso_id) {
            echo json_encode(['ok' => false, 'msg' => 'Caso no válido']);
            exit;
        }

        $res = $model->guardarTestimonio($caso_id, $contenido);
        echo json_encode(['ok' => $res, 'msg' => $res ? 'Testimonio guardado' : 'Error al guardar']);
        break;

    default:
        http_response_code(400);
        echo json_encode(['ok' => false, 'msg' => 'Operación no reconocida']);
}

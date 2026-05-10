<?php
// Controllers/DonacionController.php
require_once __DIR__ . "/../models/donaciones.php";
require_once __DIR__ . "/../models/CasoSocial.php";

class DonacionController {
    private $model;
    private $casoModel;

    public function __construct() {
        $this->model     = new DonacionModel();
        $this->casoModel = new CasoSocial();
    }

    // ── Vista pública: formulario para donar a un caso ──────────────────────
    public function crear() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            header("Location: index.php?controller=caso&action=catalogo&msg=id_invalido");
            exit;
        }

        $caso = $this->casoModel->obtenerPorId($id);

        if (!$caso || $caso['estado_evaluacion'] !== 'publicado' || $caso['publicado'] != 1) {
            header("Location: index.php?controller=caso&action=catalogo&msg=caso_no_disponible");
            exit;
        }

        require_once __DIR__ . "/../view/donar.php";
    }

    // ── Callback de éxito de MercadoPago ────────────────────────────────────
    public function success() {
        $casoId = isset($_GET['caso_id']) ? (int) $_GET['caso_id'] : 0;
        $monto  = isset($_GET['monto'])   ? number_format((float) $_GET['monto'], 2) : '0.00';
        $nombre = isset($_GET['nombre'])  ? urldecode($_GET['nombre']) : 'Donante';
        $metodo = isset($_GET['metodo'])  ? urldecode($_GET['metodo']) : 'Mercado Pago';
        $caso   = $casoId ? $this->casoModel->obtenerPorId($casoId) : null;

        require_once __DIR__ . "/../view/donacion_exito.php";
    }

    // ── Historial de donaciones del usuario autenticado ─────────────────────
    public function historial() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $donaciones = $this->model->obtenerPorUsuario($_SESSION['idusuario']);
        require_once __DIR__ . "/../view/donaciones_historial.php";
    }

    // ── Certificado de donación ──────────────────────────────────────────────
    public function certificado() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $idDonacion = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if (!$idDonacion) {
            header("Location: index.php?controller=donacion&action=historial");
            exit;
        }

        $donacion = $this->model->obtenerPorId($idDonacion);
        if (!$donacion || $donacion['id_usuario'] != $_SESSION['idusuario']) {
            header("Location: index.php?controller=donacion&action=historial");
            exit;
        }

        require_once __DIR__ . "/../view/certificado_donacion.php";
    }

    // ── Historia del donador (pública) ───────────────────────────────────────
    public function historia() {
        $estilo_pagina = 'home';
        require_once __DIR__ . "/../view/historia_donador.php";
    }

    // ════════════════════════════════════════════════════════════════════════
    // NUEVO MÓDULO DE INGRESOS / PATROCINIOS (integrado desde donaciones.zip)
    // ════════════════════════════════════════════════════════════════════════

    // ── Panel financiero (admin) ─────────────────────────────────────────────
    public function panel() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $kpis       = $this->model->getKPIsPanel();
        $donaciones = $this->model->listarCompleto();
        require_once __DIR__ . "/../view/panel_donaciones.php";
    }

    // ── Formulario de registro de nuevos ingresos / patrocinios (admin) ─────
    public function registroIngreso() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $donadores = $this->model->obtenerDonadores();
        require_once __DIR__ . "/../view/registro_ingreso.php";
    }

    // ── Procesar registro de patrocinio vía AJAX ─────────────────────────────
    public function procesarPatrocinio() {
        header('Content-Type: application/json');

        try {
            $donadorId = $this->resolverDonador();

            $monto = (float) ($_POST['monto_total'] ?? 0);
            if ($monto <= 0) {
                throw new Exception("El monto debe ser mayor a 0.");
            }

            // Los patrocinios no llevan comisión
            $comision = 0;
            $neto     = $monto;

            $data = [
                'donador_id'    => $donadorId,
                'tipo_donacion' => 'patrocinio',
                'monto_total'   => $monto,
                'comision'      => 0,
                'monto_neto'    => $monto,
                'notas'         => strip_tags($_POST['notas'] ?? ''),
                'patrocinio'    => [
                    'tipo_aporte'    => $_POST['tipo_aporte']                ?? 'efectivo',
                    'descripcion'    => strip_tags($_POST['descripcion_patrocinio'] ?? ''),
                    'valor_estimado' => (float) ($_POST['valor_estimado']    ?? 0),
                    'archivo'        => $this->subirArchivo()
                ]
            ];

            $id = $this->model->registrarPatrocinio($data);

            echo json_encode([
                'success'     => true,
                'donacion_id' => $id,
                'comision'    => $comision,
                'neto'        => $neto
            ]);

        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ── Exportar historial de donaciones como CSV ────────────────────────────
    public function exportarCSV() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $donaciones = $this->model->listarCompleto();
        $csv = "ID,Donador,Tipo,Monto,Comisión,Neto,Estado,Fecha\n";

        foreach ($donaciones as $d) {
            $csv .= implode(',', [
                $d['id'],
                '"' . ($d['donador_nombre'] ?? '') . '"',
                $d['tipo_donacion'],
                $d['monto_total'],
                $d['monto_comision'] ?? 0,
                $d['monto_neto'],
                $d['estado'],
                $d['fecha']
            ]) . "\n";
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="donaciones_' . date('Y-m-d') . '.csv"');
        echo "\xEF\xBB\xBF" . $csv;
        exit;
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    private function resolverDonador(): int {
        if (!empty($_POST['donador_id'])) {
            return (int) $_POST['donador_id'];
        }

        return $this->model->crearDonador([
            'nombre'    => strip_tags($_POST['donador_nombre']    ?? ''),
            'email'     => filter_var($_POST['donador_email']     ?? '', FILTER_SANITIZE_EMAIL),
            'documento' => strip_tags($_POST['donador_documento'] ?? ''),
            'telefono'  => strip_tags($_POST['donador_telefono']  ?? ''),
            'tipo'      => $_POST['donador_tipo'] ?? 'persona'
        ]);
    }

    private function subirArchivo(): ?string {
        if (empty($_FILES['evidencia']['tmp_name'])) {
            return null;
        }

        $ext        = strtolower(pathinfo($_FILES['evidencia']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];

        if (!in_array($ext, $permitidos)) {
            throw new Exception('Tipo de archivo no permitido.');
        }
        if ($_FILES['evidencia']['size'] > 5 * 1024 * 1024) {
            throw new Exception('El archivo supera el tamaño máximo permitido (5 MB).');
        }

        $carpeta = __DIR__ . '/../assets/uploads/patrocinios/';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nombre  = uniqid('ev_') . '.' . $ext;
        $destino = $carpeta . $nombre;

        if (!move_uploaded_file($_FILES['evidencia']['tmp_name'], $destino)) {
            throw new Exception('No se pudo guardar el archivo.');
        }

        return $nombre;
    }
}
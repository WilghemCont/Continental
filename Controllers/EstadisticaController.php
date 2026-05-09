<?php
/**
 * Controllers/EstadisticaController.php
 */
require_once __DIR__ . "/../models/CasoSocial.php";
//require_once __DIR__ . "/../models/Estadistica.php";
// Asumimos que estos modelos existen en tu estructura actual
require_once __DIR__ . "/../models/donaciones.php"; 
// require_once __DIR__ . "/../models/Transferencia.php";

class EstadisticaController {
    private $CasoSocial;
    private $estModel;
    private $DonacionModel;
    // private $transModel;

    public function __construct() {
        // En tu arquitectura, los modelos heredan de Conectar
        $this->CasoSocial = new CasoSocial();
        //$this->estModel  = new Estadistica();
        $this->DonacionModel = new DonacionModel();
        // $this->transModel = new Transferencia();
    }

    /** Dashboard Principal de Estadísticas */
    public function index() {
        // Obtenemos los datos consolidados para el Dashboard
        $donaciones          = $this->DonacionModel->getKPIs(); // Debes tener este método en el modelo
        //$por_mes          = $this->estModel->obtenerRecaudacionMensual(); 
        //$por_metodo       = $this->estModel->obtenerDistribucionMetodos();
        $casos            = $this->CasoSocial->getActivos();
        //$casos_pendientes = $this->estModel->obtenerCasosSinTransferencia();
        $total_recaudado_global = array_sum(array_column($casos, 'monto_recaudado'));
        $resumen = [
        'activos'         => count($casos),
        'cerrados'        => 0, // Puedes implementar esta lógica luego
        'monto_recaudado' => $total_recaudado_global,//$datos_db['total_monto'] ?? 0,
        'total_meta'      => 100000 // Valor base
    ];
        // Variables de apoyo para los KPI Cards
        $kpi_donaciones = [
            'total_donaciones' => $donaciones['total_donaciones'] ?? 0,
            'total_donadores'  => $donaciones['total_donadores'] ?? 0,
            'promedio_monto'   => $donaciones['promedio_monto'] ?? 0
        ];

        // Carga de la vista Dashboard
        require_once __DIR__ . "/../view/estadistica_dashboard.php";
    }

    /** Detalle de un caso específico */
    public function detalle() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $this->redirect("bandeja");
            return;
        }

        // Recuperar datos consolidados del caso
        $caso = $this->estModel->obtenerResumenCaso($id);
        $donaciones = $this->estModel->obtenerDonacionesPorCaso($id);

        if (!$caso) {
            $this->redirect("bandeja", "El caso solicitado no existe.");
            return;
        }

        // Carga de la vista de detalle
        require_once __DIR__ . "/../view/estadisticas_detalle.php";
    }

    /** API JSON: Progreso en tiempo real para el Dashboard */
    public function progreso_json() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $data = $this->estModel->obtenerResumenCaso($id);

            if ($data) {
                echo json_encode([
                    'ok' => true,
                    'monto_recaudado' => $data['monto_recaudado'],
                    'porcentaje'      => $data['porcentaje']
                ]);
            } else {
                echo json_encode(['ok' => false, 'error' => 'Caso no encontrado']);
            }
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }

    /** Método de redirección interna */
    private function redirect($action, $msg = "") {
        $url = "index.php?controller=caso&action=" . $action;
        if ($msg) $url .= "&msg=" . urlencode($msg);
        header("Location: " . $url);
        exit();
    }
}
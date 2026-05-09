<?php
// ============================================================
// controllers/EstadisticaController.php
// ============================================================
class EstadisticaController {
    private CasoSocialModel   $casoModel;
    private DonacionModel     $donacionModel;
    private TransferenciaModel $transModel;
    private NotificacionModel  $notiModel;

    public function __construct() {
        $this->casoModel     = new CasoSocialModel();
        $this->donacionModel = new DonacionModel();
        $this->transModel    = new TransferenciaModel();
        $this->notiModel     = new NotificacionModel();
    }

    public function index(): void {
        $data = [
            'titulo'           => 'Dashboard — Monitoreo de Recaudaciones',
            'casos'            => $this->casoModel->getActivos(),
            'resumen'          => $this->casoModel->getResumenGlobal(),
            'kpi_donaciones'   => $this->donacionModel->getKPIs(),
            'kpi_transferencias'=> $this->transModel->getKPIs(),
            'por_mes'          => $this->casoModel->getProgresoPorMes(),
            'por_metodo'       => $this->donacionModel->getPorMetodoPago(),
            'notif_count'      => $this->notiModel->countNoLeidas(),
            'casos_pendientes' => $this->transModel->getCasosSinTransferencia(),
        ];
        $this->render('estadisticas/index', $data);
    }

    /** Vista de detalle de un caso */
    public function detalle(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $caso = $this->casoModel->getById($id);
        if (!$caso) { $this->redirect('estadisticas'); return; }

        $data = [
            'titulo'      => 'Detalle del Caso — ' . htmlspecialchars($caso['titulo']),
            'caso'        => $caso,
            'donaciones'  => $this->donacionModel->getPorCaso($id),
            'notif_count' => $this->notiModel->countNoLeidas(),
        ];
        $this->render('estadisticas/detalle', $data);
    }

    /** API JSON: progreso en tiempo real */
    public function progreso(): void {
        header('Content-Type: application/json');
        $id   = (int)($_GET['id'] ?? 0);
        $caso = $this->casoModel->getById($id);
        echo json_encode($caso ?: ['error' => 'No encontrado']);
        exit;
    }

    protected function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $page, string $msg = '', string $type = 'success'): void {
        if ($msg) $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
        header('Location: ' . BASE_URL . '?page=' . $page);
        exit;
    }
}

<?php
// ============================================================
// controllers/DonacionController.php
// ============================================================
class DonacionController {
    private DonacionModel     $donacionModel;
    private CasoSocialModel   $casoModel;
    private NotificacionModel $notiModel;

    public function __construct() {
        $this->donacionModel = new DonacionModel();
        $this->casoModel     = new CasoSocialModel();
        $this->notiModel     = new NotificacionModel();
    }

    /** Listado de donaciones */
    public function index(): void {
        $limite  = 20;
        $pagina  = max(1, (int)($_GET['pag'] ?? 1));
        $offset  = ($pagina - 1) * $limite;
        $total   = $this->donacionModel->countTodas();

        $data = [
            'titulo'      => 'Gestión de Donaciones',
            'donaciones'  => $this->donacionModel->getTodas($limite, $offset),
            'kpis'        => $this->donacionModel->getKPIs(),
            'metodos'     => $this->donacionModel->getPorMetodoPago(),
            'total'       => $total,
            'pagina'      => $pagina,
            'total_pag'   => ceil($total / $limite),
            'notif_count' => $this->notiModel->countNoLeidas(),
        ];
        $this->render('donaciones/index', $data);
    }

    /** Formulario nueva donación */
    public function nueva(): void {
        $data = [
            'titulo'      => 'Registrar Donación',
            'casos'       => $this->casoModel->getActivos(),
            'notif_count' => $this->notiModel->countNoLeidas(),
        ];
        $this->render('donaciones/form', $data);
    }

    /** Guardar donación */
    public function guardar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('donaciones'); return; }

        $campos = ['caso_social_id','donador_nombre','donador_email','monto','metodo_pago'];
        foreach ($campos as $c) {
            if (empty($_POST[$c])) {
                $this->redirect('donaciones&action=nueva', 'Completa todos los campos obligatorios.', 'error');
                return;
            }
        }

        $monto = (float)$_POST['monto'];
        if ($monto <= 0) {
            $this->redirect('donaciones&action=nueva', 'El monto debe ser mayor a 0.', 'error');
            return;
        }

        try {
            $id = $this->donacionModel->registrar([
                'caso_social_id'    => (int)$_POST['caso_social_id'],
                'donador_nombre'    => trim($_POST['donador_nombre']),
                'donador_email'     => trim($_POST['donador_email']),
                'donador_telefono'  => trim($_POST['donador_telefono'] ?? ''),
                'monto'             => $monto,
                'metodo_pago'       => $_POST['metodo_pago'],
                'codigo_transaccion'=> trim($_POST['codigo_transaccion'] ?? ''),
                'notas'             => trim($_POST['notas'] ?? ''),
            ]);

            // Actualizar monto del caso y verificar meta
            $resultado = $this->casoModel->actualizarMonto((int)$_POST['caso_social_id']);

            // Notificar si se alcanzó la meta
            if ($resultado['meta_alcanzada'] && $resultado['caso']) {
                $caso = $resultado['caso'];
                $this->notiModel->crear(
                    (int)$caso['ong_id'],
                    $caso['id'],
                    'meta_alcanzada',
                    '🎯 ¡Meta Alcanzada! — ' . $caso['titulo'],
                    "El caso social \"{$caso['titulo']}\" ha alcanzado su meta de S/ " .
                    number_format($caso['meta_monto'], 2) .
                    ". El monto recaudado es S/ " . number_format($caso['monto_recaudado'], 2) . "."
                );
            }

            $this->redirect('donaciones', "Donación #$id registrada exitosamente.");
        } catch (Exception $e) {
            $this->redirect('donaciones&action=nueva', 'Error: ' . $e->getMessage(), 'error');
        }
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

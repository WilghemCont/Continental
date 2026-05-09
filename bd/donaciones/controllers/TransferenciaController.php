<?php
// ============================================================
// controllers/TransferenciaController.php
// ============================================================
class TransferenciaController {
    private TransferenciaModel $transModel;
    private NotificacionModel  $notiModel;
    private CasoSocialModel    $casoModel;

    public function __construct() {
        $this->transModel = new TransferenciaModel();
        $this->notiModel  = new NotificacionModel();
        $this->casoModel  = new CasoSocialModel();
    }

    /** Listado de transferencias */
    public function index(): void {
        $data = [
            'titulo'           => 'Gestión de Transferencias',
            'transferencias'   => $this->transModel->getTodas(),
            'kpis'             => $this->transModel->getKPIs(),
            'casos_pendientes' => $this->transModel->getCasosSinTransferencia(),
            'notificaciones'   => $this->notiModel->getTodas(5),
            'notif_count'      => $this->notiModel->countNoLeidas(),
        ];
        $this->render('transferencias/index', $data);
    }

    /** Ver detalle de una transferencia */
    public function detalle(): void {
        $id = (int)($_GET['id'] ?? 0);
        $t  = $this->transModel->getById($id);
        if (!$t) { $this->redirect('transferencias'); return; }

        $data = [
            'titulo'      => 'Detalle de Transferencia #' . $id,
            'trans'       => $t,
            'notif_count' => $this->notiModel->countNoLeidas(),
        ];
        $this->render('transferencias/detalle', $data);
    }

    /** Iniciar transferencia para un caso (POST) */
    public function iniciar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('transferencias'); return; }
        $casoId = (int)($_POST['caso_id'] ?? 0);
        try {
            $tid = $this->transModel->iniciar($casoId);
            // Notificar a la ONG
            $caso = $this->casoModel->getById($casoId);
            if ($caso) {
                $this->notiModel->crear(
                    (int)$caso['ong_id'], $casoId,
                    'transferencia_iniciada',
                    '💸 Transferencia Iniciada — ' . $caso['titulo'],
                    "Se ha iniciado el proceso de transferencia de S/ " .
                    number_format($caso['monto_recaudado'], 2) . " para el caso \"{$caso['titulo']}\"."
                );
            }
            $this->redirect('transferencias&action=detalle&id=' . $tid, 'Transferencia iniciada. Ahora sube el comprobante.');
        } catch (Exception $e) {
            $this->redirect('transferencias', $e->getMessage(), 'error');
        }
    }

    /** Subir comprobante de transferencia (POST con archivo) */
    public function subirComprobante(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('transferencias'); return; }
        $id    = (int)($_POST['transferencia_id'] ?? 0);
        $notas = trim($_POST['notas'] ?? '');

        if (empty($_FILES['documento']['name'])) {
            $this->redirect("transferencias&action=detalle&id=$id", 'Debes seleccionar un archivo.', 'error');
            return;
        }

        $archivo   = $_FILES['documento'];
        $ext       = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg','jpeg','png','pdf','webp'];

        if (!in_array($ext, $permitidos)) {
            $this->redirect("transferencias&action=detalle&id=$id", 'Formato no permitido. Usa JPG, PNG o PDF.', 'error');
            return;
        }

        if ($archivo['size'] > 5 * 1024 * 1024) {
            $this->redirect("transferencias&action=detalle&id=$id", 'El archivo supera 5 MB.', 'error');
            return;
        }

        $nombreFinal = 'trans_' . $id . '_' . time() . '.' . $ext;
        $rutaDest    = UPLOAD_PATH . $nombreFinal;

        if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDest)) {
            $this->redirect("transferencias&action=detalle&id=$id", 'Error al subir el archivo.', 'error');
            return;
        }

        $this->transModel->subirDocumento($id, $nombreFinal, $notas);
        $this->redirect("transferencias&action=detalle&id=$id", 'Comprobante subido. En espera de confirmación de la ONG.');
    }

    /** Confirmar recepción por la ONG (POST) */
    public function confirmar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('transferencias'); return; }
        $id            = (int)($_POST['transferencia_id'] ?? 0);
        $confirmadoPor = trim($_POST['confirmado_por'] ?? 'Sistema');

        $ok = $this->transModel->confirmar($id, $confirmadoPor);
        if ($ok) {
            $t = $this->transModel->getById($id);
            if ($t) {
                $this->notiModel->crear(
                    (int)$t['ong_id'], (int)$t['caso_social_id'],
                    'transferencia_completada',
                    '✅ Transferencia Confirmada — ' . $t['caso_titulo'],
                    "La ONG \"{$t['ong_nombre']}\" confirmó la recepción de S/ " .
                    number_format($t['monto'], 2) . ". El caso ha sido cerrado exitosamente."
                );
            }
            $this->redirect('transferencias', 'Transferencia confirmada. El caso ha sido cerrado.');
        } else {
            $this->redirect('transferencias', 'Error al confirmar la transferencia.', 'error');
        }
    }

    /** API: notificaciones no leídas (JSON) */
    public function notificaciones(): void {
        header('Content-Type: application/json');
        echo json_encode([
            'count'  => $this->notiModel->countNoLeidas(),
            'items'  => $this->notiModel->getTodas(10),
        ]);
        exit;
    }

    /** Marcar notificaciones como leídas */
    public function leerNotificaciones(): void {
        $this->notiModel->marcarTodasLeidas();
        $this->redirect('transferencias', 'Notificaciones marcadas como leídas.');
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

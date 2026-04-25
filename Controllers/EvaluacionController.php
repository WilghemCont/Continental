<?php
/**
 * app/controllers/EvaluacionController.php
 * Controller — Acciones AJAX: evaluar, publicar, proceso
 * Todas las acciones reciben y responden JSON
 */

require_once __DIR__ . '/../models/Evaluacion.php';

class EvaluacionController
{
    private Evaluacion $model;

    public function __construct()
    {
        $this->model = new Evaluacion();
    }

    // -------------------------------------------------------
    // POST ?controller=evaluacion&action=cambiarEstado
    // Body JSON: { id, estado, comentario }
    // -------------------------------------------------------
   public function cambiarEstado(): void
    {
        header('Content-Type: application/json');

        try {
            if (ob_get_length()) ob_clean();

            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $casoId = (int)($data['id'] ?? 0);
            $estado = trim($data['estado'] ?? '');
            $comentario = trim($data['comentario'] ?? '');
            $usuario = $_SESSION['nombre'] ?? 'Admin';

            $this->model->cambiarEstado($casoId, $estado, $comentario, $usuario);

            echo json_encode(['ok' => true]);
        } catch (Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    // -------------------------------------------------------
    // POST ?controller=evaluacion&action=publicar
    // Body JSON: { id, publicar }
    // -------------------------------------------------------
    public function publicar(): void
    {
        header('Content-Type: application/json');
        $data    = json_decode(file_get_contents('php://input'), true) ?? [];
        $casoId  = (int)($data['id']       ?? 0);
        $publicar = (bool)($data['publicar'] ?? false);
        $usuario = $_SESSION['nombre'] ?? 'Administrador';

        try {
            $this->model->publicar($casoId, $publicar, $usuario);
            $msg = $publicar ? 'Caso publicado en la web' : 'Caso retirado de la web';
            echo json_encode(['ok' => true, 'mensaje' => $msg]);
        } catch (Throwable $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // -------------------------------------------------------
    // POST ?controller=evaluacion&action=cambiarProceso
    // Body JSON: { id, estado, comentario }
    // -------------------------------------------------------
    public function cambiarProceso(): void
    {
        header('Content-Type: application/json');
        $data       = json_decode(file_get_contents('php://input'), true) ?? [];
        $casoId     = (int)($data['id']        ?? 0);
        $estado     = trim($data['estado']     ?? '');
        $comentario = trim($data['comentario'] ?? '');
        $usuario    = $_SESSION['nombre'] ?? 'Administrador';

        try {
            $this->model->cambiarProceso($casoId, $estado, $comentario, $usuario);
            echo json_encode(['ok' => true, 'mensaje' => 'Estado del proceso actualizado']);
        } catch (Throwable $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

<?php
/**
 * controller/CasoController.php
 */
require_once "../models/CasoSocial.php";
require_once "../models/historial.php";

class CasoController
{
    private $model;

    public function __construct()
    {
        $this->model = new CasoSocial();
    }

    public function bandeja(): void
    {
        $filtros = [
            'clasificacion'     => trim($_GET['clasificacion']     ?? ''),
            'estado_evaluacion' => trim($_GET['estado_evaluacion'] ?? ''),
            'estado_proceso'    => trim($_GET['estado_proceso']    ?? ''),
            'buscar'            => trim($_GET['buscar']            ?? ''),
        ];

        $casos = $this->model->listar($filtros);
        $stats = $this->model->estadisticas();

        // Rutas relativas desde el index.php de la raíz
        require_once "../view/layout/header.php";
        require_once "../view/bandeja.php";
        require_once "../view/layout/footer.php";
    }

    public function listar(): void
    {
        header('Content-Type: application/json');

        $filtros = [
            'clasificacion'     => trim($_GET['clasificacion']     ?? ''),
            'estado_evaluacion' => trim($_GET['estado_evaluacion'] ?? ''),
            'estado_proceso'    => trim($_GET['estado_proceso']    ?? ''),
            'buscar'            => trim($_GET['buscar']            ?? ''),
        ];

        $casos = $this->model->listar($filtros);
        $stats = $this->model->estadisticas();

        // Limpiamos cualquier salida previa para enviar un JSON puro
        ob_clean();
        echo json_encode(compact('casos', 'stats'));
        exit;
    }

    public function detalle(): void
    {
        // 1. Forzamos que la salida sea JSON
        header('Content-Type: application/json');

        try {
            $id = (int)($_GET['id'] ?? 0);
            
            // Verificamos que el modelo exista
            if (!$this->model) {
                $this->model = new CasoSocial();
            }

            $caso = $this->model->obtenerPorId($id);

            if (!$caso) {
                echo json_encode(['error' => 'Caso no encontrado en la BD']);
                exit;
            }

            // 2. Limpiamos cualquier "basura" o notices que PHP haya escupido antes
            if (ob_get_length()) ob_clean();

            // Enviamos solo el objeto del caso por ahora para asegurar que funcione
            echo json_encode(['caso' => $caso]);
            exit;

        } catch (Exception $e) {
            // Si algo falla, lo devolvemos como JSON, no como error de PHP
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
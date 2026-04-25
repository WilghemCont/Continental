<?php
require_once __DIR__ . '/../models/Donante.php';

class DonanteController {
    private $model;

    public function __construct() {
        $this->model = new Donante();
    }

    public function listar() {
        header('Content-Type: application/json');
        echo json_encode($this->model->listar());
        exit;
    }

    public function detalle() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? 0;
        echo json_encode($this->model->obtenerPorId($id));
        exit;
    }

    public function guardar() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->guardar($data['nombre'], $data['email']);
        echo json_encode(['ok' => $res]);
        exit;
    }

    public function actualizar() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->actualizar($data['id'], $data['nombre'], $data['email']);
        echo json_encode(['ok' => $res]);
        exit;
    }
}
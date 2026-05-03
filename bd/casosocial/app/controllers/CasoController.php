<?php
require_once __DIR__ . "/../models/CasoSocial.php";

class CasoController {

    private $model;

    public function __construct() {
        global $pdo;
        $this->model = new CasoSocial($pdo);
    }

    public function dashboard() {
        $stats = $this->model->obtenerEstadisticas();
        $casos = $this->model->listar();
        require_once __DIR__ . "/../views/dashboard.php";
    }

    public function listar() {
        $casos = $this->model->listar();
        require_once __DIR__ . "/../views/lista_casos.php";
    }

    public function ver() {

    $id = $_GET['id'];

    $caso = $this->model->obtener($id);

    // 🔽 cargar documentos
    require_once __DIR__ . "/../models/Documento.php";
    $docModel = new Documento($this->model->getPDO());
    $documentos = $docModel->obtenerPorCaso($id);

    require_once __DIR__ . "/../views/ver_caso.php";
    }

    public function listarAjax() {

    $estado = $_GET['estado'] ?? null;

    // 🔥 SOLUCIÓN: manejar "todos"
    if ($estado === 'todos' || $estado === '') {
        $estado = null;
    }
    $busqueda = $_GET['busqueda'] ?? null;

    $casos = $this->model->listar($estado, $busqueda);

    foreach ($casos as $c) {
        echo "<tr>
            <td>{$c['id']}</td>
            <td>{$c['titulo_caso']}</td>
            <td>{$c['ong_nombre']}</td>
            <td>{$c['nombre_beneficiario']}</td>
            <td><span class='badge bg-info'>{$c['estado']}</span></td>
            <td>
                <a href='index.php?controller=caso&action=ver&id={$c['id']}' class='btn btn-sm btn-primary'>Ver</a>
            </td>
        </tr>";
    }
    }

    private function generarAcciones($caso) {

    $id = $caso['id'];
    $estado = $caso['estado'];

    $acciones = "";

    // VER (siempre)
    $acciones .= "<a href='index.php?controller=caso&action=ver&id=$id' 
                    class='btn btn-sm btn-primary'>Ver</a> ";

    switch ($estado) {

        case 'pendiente':
        case 'observado':
            $acciones .= "<a href='index.php?controller=evaluacion&action=ver&id=$id' 
                            class='btn btn-sm btn-warning'>Evaluar</a>";
            break;

        case 'aprobado':
            $acciones .= "<a href='index.php?controller=publicacion&action=crear&id=$id' 
                            class='btn btn-sm btn-success'>Publicar</a>";
            break;

        case 'publicado':
            $acciones .= "<a href='index.php?controller=cierre&action=ver&id=$id' 
                            class='btn btn-sm btn-secondary'>Cerrar</a>";
            break;

        case 'cerrado':
            // solo ver → no agregar nada
            break;
    }

    return $acciones;
    }

    
}
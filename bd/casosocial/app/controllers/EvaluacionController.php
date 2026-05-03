<?php

require_once __DIR__ . "/../models/CasoSocial.php";
require_once __DIR__ . "/../models/Checklist.php";

class EvaluacionController {

    private $casoModel;
    private $checkModel;

    public function __construct() {
        global $pdo;

        $this->casoModel = new CasoSocial($pdo);
        $this->checkModel = new Checklist($pdo);
    }

    public function ver() {

        $id = $_GET['id'];

        $caso = $this->casoModel->obtener($id);
        $checklist = $this->checkModel->obtenerPorTipo('evaluacion');

        require_once __DIR__ . "/../views/evaluar.php";
    }

    public function guardar() {

    try {

        $idCaso = $_GET['id'];

        if (!isset($_POST['check'])) {
            throw new Exception("Checklist incompleto");
        }

        $checks = $_POST['check'];
        $comentarios = $_POST['comentario'] ?? [];

        // 🔒 TRANSACCIÓN
        $this->casoModel->beginTransaction();

        // 1. Eliminar respuestas previas (si existen)
        $this->checkModel->eliminarPorCaso($idCaso);

        $hayNo = false;

        // 2. Guardar cada respuesta
        foreach ($checks as $id_item => $estado) {

            $comentario = $comentarios[$id_item] ?? null;

            if ($estado === 'NO') {
                if (empty($comentario)) {
                    throw new Exception("Debe ingresar comentario en los ítems NO");
                }
                $hayNo = true;
            }

            $this->checkModel->guardarRespuesta(
                $idCaso,
                $id_item,
                $estado,
                $comentario
            );
        }

        // 3. Determinar estado final
        $nuevoEstado = $hayNo ? 'observado' : 'aprobado';

        // 4. Cambiar estado (usa modelo → incluye historial)
        $this->casoModel->cambiarEstado($idCaso, $nuevoEstado, $_SESSION['idlogin'] ?? null);

        // 5. Commit
        $this->casoModel->commit();

        header("Location: index.php?controller=caso&action=dashboard");
        exit();

    } catch (Exception $e) {

        $this->casoModel->rollBack();

        echo "<script>alert('Error: " . $e->getMessage() . "'); history.back();</script>";
    }
    }

}
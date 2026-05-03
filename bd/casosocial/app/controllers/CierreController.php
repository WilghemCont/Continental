<?php

require_once __DIR__ . "/../models/CasoSocial.php";
require_once __DIR__ . "/../models/Checklist.php";
require_once __DIR__ . "/../models/Documento.php";

class CierreController {

    private $casoModel;
    private $checkModel;
    private $docModel;

    public function __construct() {
        global $pdo;

        $this->casoModel = new CasoSocial($pdo);
        $this->checkModel = new Checklist($pdo);
        $this->docModel = new Documento($pdo);
    }

    public function ver() {

        $id = $_GET['id'];

        $caso = $this->casoModel->obtener($id);

        if ($caso['estado'] !== 'publicado') {
            die("Solo se pueden cerrar casos publicados");
        }

        $checklist = $this->checkModel->obtenerPorTipo('cierre');

        require_once __DIR__ . "/../views/cerrar.php";
    }

    public function guardar() {

        try {

            $idCaso = $_GET['id'];

            $checks = $_POST['check'];
            $comentarios = $_POST['comentario'] ?? [];

            $this->casoModel->beginTransaction();

            // limpiar checklist anterior
            $this->checkModel->eliminarPorCaso($idCaso);

            $hayNo = false;

            foreach ($checks as $id_item => $estado) {

                $comentario = $comentarios[$id_item] ?? null;

                if ($estado === 'NO') {
                    if (empty($comentario)) {
                        throw new Exception("Comentario requerido en ítems NO");
                    }
                    $hayNo = true;
                }

                $this->checkModel->guardarRespuesta($idCaso, $id_item, $estado, $comentario);
            }

            // 📎 SUBIDA DE ARCHIVOS
            if (!empty($_FILES['documentos']['name'][0])) {

                foreach ($_FILES['documentos']['tmp_name'] as $key => $tmp) {

                    $nombre = $_FILES['documentos']['name'][$key];
                    $ruta = "uploads/" . time() . "_" . $nombre;

                    move_uploaded_file($tmp, __DIR__ . "/../../" . $ruta);

                    $this->docModel->guardar([
                        'id_caso' => $idCaso,
                        'tipo' => 'cierre',
                        'nombre_archivo' => $nombre,
                        'ruta_archivo' => $ruta,
                        'tipo_mime' => $_FILES['documentos']['type'][$key],
                        'tamanio' => $_FILES['documentos']['size'][$key]
                    ]);
                }
            }

            // 🔥 CAMBIAR ESTADO SOLO SI TODO OK
            if (!$hayNo) {
                $this->casoModel->cambiarEstado($idCaso, 'cerrado', $_SESSION['idlogin'] ?? null);
            }

            $this->casoModel->commit();

            header("Location: index.php?controller=caso&action=dashboard");
            exit();

        } catch (Exception $e) {

            $this->casoModel->rollBack();

            echo "<script>alert('Error: {$e->getMessage()}'); history.back();</script>";
        }
    }
}
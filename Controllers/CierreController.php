<?php

require_once "../models/CasoSocial.php";
require_once "../models/Checklist.php";

class CierreController
{
    public static function ver()
    {
        $id = $_GET['id'];

        $casoModel = new CasoSocial();
        $checklistModel = new Checklist();

        $caso = $casoModel->obtenerPorId($id);

        // checklist tipo cierre
        $items = $checklistModel->obtenerPorTipo('cierre');

        require "../view/cerrar.php";
    }

    public static function guardar()
    {
        $id = $_GET['id'];

        $checklistModel = new Checklist();
        $casoModel = new CasoSocial();

        // eliminar checklist previo si existe
        $checklistModel->eliminarPorCasoYTipo($id, 'cierre');

        foreach ($_POST['check'] as $itemId => $estado) {

            $comentario = $_POST['comentario'][$itemId] ?? '';

            $checklistModel->guardarRespuesta(
                $id,
                $itemId,
                $estado,
                $comentario
            );
        }

        // =========================
        // SUBIR PDF
        // =========================

        $archivo = null;

        if (!empty($_FILES['documento']['name'])) {

            $nombre = time() . "_" . $_FILES['documento']['name'];

            move_uploaded_file(
                $_FILES['documento']['tmp_name'],
                "../assets/uploads/cierres/" . $nombre
            );

            $archivo = $nombre;
        }

        // =========================
        // CERRAR CASO
        // =========================

        $casoModel->cerrarCaso($id, $archivo);

        header("Location:index.php?controller=caso&action=ver&id=$id");
    }
}
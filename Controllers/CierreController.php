<?php

require_once "../models/CasoSocial.php";
require_once "../models/Checklist.php";

class CierreController
{
    public static function ver()
    {
        if (!isset($_GET['id'])) {
            die("ID de caso no válido.");
        }

        $id = (int) $_GET['id'];

        $casoModel = new CasoSocial();
        $checklistModel = new Checklist();

        $caso = $casoModel->obtenerPorId($id);

        if (!$caso) {
            die("Caso no encontrado.");
        }

        // =========================================
        // VALIDAR QUE EL CASO ESTÉ PUBLICADO
        // =========================================

        if ($caso['estado_evaluacion'] !== 'publicado') {
            die("Solo se pueden cerrar casos publicados.");
        }

        // =========================================
        // VALIDAR META ALCANZADA
        // =========================================

        $meta = (float) $caso['meta_total'];
        $recaudado = (float) $caso['monto_recaudado'];

        if ($recaudado < $meta) {
            die("El caso aún no alcanzó la meta requerida.");
        }

        // =========================================
        // OBTENER CHECKLIST
        // =========================================

        $items = $checklistModel->obtenerPorTipo('cierre');

        require "../view/cerrar.php";
    }

    public static function guardar()
    {
        if (!isset($_GET['id'])) {
            die("ID inválido.");
        }

        $id = (int) $_GET['id'];

        $casoModel = new CasoSocial();
        $checklistModel = new Checklist();

        $caso = $casoModel->obtenerPorId($id);

        if (!$caso) {
            die("Caso no encontrado.");
        }

        // =========================================
        // VALIDAR DOCUMENTO DE CIERRE
        // =========================================

        if (empty($caso['documento_cierre'])) {

            $_SESSION['error'] = "La ONG aún no adjuntó el documento sustento final.";

            header("Location:index.php?controller=cierre&action=ver&id=$id");
            exit;
        }

        // =========================================
        // VALIDAR CHECKLIST
        // =========================================

        if (empty($_POST['check'])) {

            $_SESSION['error'] = "Debe completar el checklist de cierre.";

            header("Location:index.php?controller=cierre&action=ver&id=$id");
            exit;
        }

        // =========================================
        // ELIMINAR RESPUESTAS PREVIAS
        // =========================================

        $checklistModel->eliminarPorCasoYTipo($id, 'cierre');

        // =========================================
        // GUARDAR NUEVAS RESPUESTAS
        // =========================================

        foreach ($_POST['check'] as $itemId => $estado) {

            $comentario = $_POST['comentario'][$itemId] ?? '';

            $checklistModel->guardarRespuesta(
                $id,
                $itemId,
                $estado,
                $comentario
            );
        }

        // =========================================
        // CERRAR CASO
        // =========================================

        $casoModel->cerrarCaso(
            $id,
            $caso['documento_cierre']
        );

        $_SESSION['success'] = "El caso social fue cerrado correctamente.";

        header("Location:index.php?controller=caso&action=ver&id=$id");
        exit;
    }
}
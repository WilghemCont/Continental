<?php
require_once("../models/ingresos.php");

if (isset($_GET['id'])) {
    $model = new IngresoModel();
    $id = intval($_GET['id']);

    if ($model->eliminar($id)) {
        echo "OK";
    } else {
        echo "Error al eliminar";
    }
}
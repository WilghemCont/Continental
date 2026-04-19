<?php
require_once("../models/ingresos.php");

$ingresoModel = new IngresoModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del FormData
    $data = [
        'tipo'           => $_POST['tipo'] ?? '',
        'monto'          => floatval($_POST['monto'] ?? 0),
        'fecha'          => date('Y-m-d'), // O puedes agregar un input de fecha
        'subtipo'        => $_POST['subtipo'] ?? null,
        'empresa'        => $_POST['empresa'] ?? '',
        'descripcion'    => $_POST['descripcion'] ?? '',
        'valor_estimado' => floatval($_POST['monto'] ?? 0) // Para patrocinios en especie
    ];

    if ($ingresoModel->registrar($data)) {
        echo "OK";
    } else {
        echo "Error al registrar en la base de datos";
    }
}
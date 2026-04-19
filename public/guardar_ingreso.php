<?php
require_once("../models/ingresos.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model = new IngresoModel();

    $tipo = $_POST['tipo'] ?? '';
    $monto = floatval($_POST['monto'] ?? 0);
    
    // Calculamos el porcentaje solo si es comisión
    $porcentaje = ($tipo === 'Comisión por donación') ? $model->calcularComision($monto) : null;

    $data = [
        'tipo'           => $tipo,
        'subtipo'        => $_POST['subtipo'] ?? null,
        'empresa'        => $_POST['empresa'] ?? '',
        'descripcion'    => $_POST['descripcion'] ?? '',
        'monto'          => $monto,
        'porcentaje'     => $porcentaje,
        'valor_estimado' => floatval($_POST['valor'] ?? 0),
        'fecha'          => $_POST['fecha'] ?? date('Y-m-d')
    ];

    if ($model->registrar($data)) {
        echo "OK";
    } else {
        echo "Error al registrar en la base de datos";
    }
}
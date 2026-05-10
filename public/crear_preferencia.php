<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

MercadoPagoConfig::setAccessToken("APP_USR-6673681174165750-041115-22243f674e111caabadf88a2514ec954-3330160340");
$nombre = $_POST['nombre'] ?? 'Donante Anónimo';
$monto  = isset($_POST['monto']) ? (float)$_POST['monto'] : 0;
$idcaso = isset($_POST['idcaso']) ? (int) $_POST['idcaso'] : 0;
$client = new PreferenceClient();

try {
    $preference = $client->create([
        "items" => [
            [
                "title" => "Donación de " . $nombre,
                "quantity" => 1,
                "unit_price" => $monto,
                "currency_id" => "PEN"
            ]
        ],
        "external_reference" => json_encode([
            'idcaso'  => $idcaso,
            'nombre'  => $nombre,
            'email'   => $_POST['email'] ?? '',
            'mensaje' => $_POST['mensaje'] ?? '',
            'monto'   => $monto,
            'metodo'  => $_POST['metodo'] ?? 'Mercado Pago'
        ]),
        "back_urls" => [
            "success" => "http://localhost/Continental/public/guardar_donacion.php?status=success",
            "failure" => "http://localhost/Continental/public/failure.php",
        ],
        "auto_return" => "approved",
    ]);

    header('Content-Type: application/json');
    echo json_encode([
        'id' => $preference->id,
        'init_point' => $preference->init_point 
    ]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
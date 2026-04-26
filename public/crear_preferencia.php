<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

MercadoPagoConfig::setAccessToken("APP_USR-6673681174165750-041115-22243f674e111caabadf88a2514ec954-3330160340");
$nombre = $_POST['nombre'] ?? 'Donante Anónimo';
$monto  = isset($_POST['monto']) ? (float)$_POST['monto'] : 0;
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
        "back_urls" => [
            "success" => "https://tu-dominio.com/success",
            "failure" => "https://tu-dominio.com/failure",
        ],
        "auto_return" => "approved",
    ]);

    header('Content-Type: application/json');
    // Enviamos el ID y el init_point
    echo json_encode([
        'id' => $preference->id,
        'init_point' => $preference->init_point 
    ]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
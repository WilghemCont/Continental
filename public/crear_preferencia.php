<?php
require_once '../vendor/autoload.php';

MercadoPago\SDK::setAccessToken('APP_USR-6673681174165750-041115-22243f674e111caabadf88a2514ec954-3330160340');

$nombre  = $_POST['nombre'];
$email   = $_POST['email'];
$monto   = $_POST['monto'];

$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->title = "Donación - SocialFunding";
$item->quantity = 1;
$item->unit_price = (float)$monto;

$preference->items = array($item);

// URLs de retorno
$preference->back_urls = array(
    "success" => "http://localhost/tu_proyecto/public/gracias.php",
    "failure" => "http://localhost/tu_proyecto/public/error.php",
    "pending" => "http://localhost/tu_proyecto/public/pendiente.php"
);

$preference->auto_return = "approved";

$preference->save();

echo json_encode([
    "id" => $preference->id
]);
<?php
// Necesitarás instalar el SDK de Mercado Pago vía Composer o descargar el .php
// require_once 'vendor/autoload.php'; 

class DonacionController {
    
    public function crearPreferenciaMP() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // Aquí configurarías tu Access Token de Mercado Pago
        // MercadoPago\SDK::setAccessToken("TU_ACCESS_TOKEN");

        // 1. Creamos la preferencia
        // $preference = new MercadoPago\Preference();

        // 2. Creamos el ítem (la donación)
        // $item = new MercadoPago\Item();
        // $item->title = "Donación SocialFunding";
        // $item->quantity = 1;
        // $item->unit_price = (float)$data['monto'];
        // $item->currency_id = "PEN"; // Soles Peruanos

        // $preference->items = array($item);
        // $preference->save();

        // Simulamos la respuesta para el ejemplo de integración
        echo json_encode([
            'id' => '123456789-PREFERENCE-ID', // Este ID lo genera el SDK de MP
            'init_point' => 'https://www.mercadopago.com.pe/checkout/v1/redirect?pref_id=...'
        ]);
        exit;
    }
}
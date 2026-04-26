<?php
require_once("../config/conexion.php");

$conectar = new Conectar();
$db = $conectar->Conexion();
$conectar->set_names();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['status']) && $_GET['status'] == 'success') {
    
    // Desempaquetamos los datos que Mercado Pago nos devuelve
    $datos_extra = isset($_GET['external_reference']) ? json_decode($_GET['external_reference'], true) : [];
    
    $nombre  = $datos_extra['nombre'] ?? 'Desconocido';
    $email   = $datos_extra['email'] ?? '';
    $mensaje = $datos_extra['mensaje'] ?? '';
    $monto   = $datos_extra['monto'] ?? 0;
    $metodo  = 'Mercado Pago'; 
    
    $sql = "INSERT INTO donaciones (nombre, email, monto, metodo, mensaje, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$nombre, $email, $monto, $metodo, $mensaje])) {
        // Redirigir al inicio con un mensaje de éxito (Ajusta home.php si es necesario)
        header("Location: home.php?pago=ok");
        exit();
    } else {
        echo "❌ Error al registrar la donación en la base de datos.";
    }
}
?>
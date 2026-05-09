<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';

$conectar = new Conectar();
$db = $conectar->Conexion();
$conectar->set_names();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'success') {
    $datos_extra = isset($_GET['external_reference']) ? json_decode($_GET['external_reference'], true) : [];

    $nombre  = $datos_extra['nombre'] ?? 'Donante Anónimo';
    $email   = $datos_extra['email'] ?? '';
    $mensaje = $datos_extra['mensaje'] ?? '';
    $monto   = isset($datos_extra['monto']) ? (float)$datos_extra['monto'] : 0.00;
    $metodo  = $datos_extra['metodo'] ?? 'Mercado Pago';
    $idCaso  = isset($datos_extra['idcaso']) ? (int)$datos_extra['idcaso'] : 0;
    $idUsuario = isset($_SESSION['idusuario']) ? (int)$_SESSION['idusuario'] : null;

    $sql = "INSERT INTO donaciones (nombre, email, monto, metodo, mensaje, idcaso, idusuario, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$nombre, $email, $monto, $metodo, $mensaje, $idCaso, $idUsuario])) {
        if ($idCaso > 0) {
            $update = $db->prepare("UPDATE casos_sociales SET monto_recaudado = COALESCE(monto_recaudado, 0) + ? WHERE id = ?");
            $update->execute([$monto, $idCaso]);
        }

        $redirect = "index.php?controller=donacion&action=success&status=success";
        $redirect .= "&caso_id=" . $idCaso;
        $redirect .= "&monto=" . urlencode(number_format($monto, 2));
        $redirect .= "&nombre=" . urlencode($nombre);
        $redirect .= "&metodo=" . urlencode($metodo);

        header("Location: $redirect");
        exit();
    }

    echo "❌ Error al registrar la donación en la base de datos.";
}
?>
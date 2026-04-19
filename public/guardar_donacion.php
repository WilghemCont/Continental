<?php
require_once("../config/conexion.php");

// Instanciamos la clase y conectamos
$conectar = new Conectar();
$db = $conectar->Conexion(); // Esto ejecuta el método protegido/public según lo ajustes
$conectar->set_names();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre  = $_POST['nombre'];
    $email   = $_POST['email'];
    $monto   = $_POST['monto'];
    $metodo  = $_POST['metodo'];
    $mensaje = $_POST['mensaje'];

    // Usamos la sintaxis de PDO (Prepare con marcadores :name)
    $sql = "INSERT INTO donaciones (nombre, email, monto, metodo, mensaje, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $db->prepare($sql);
    
    // En PDO puedes pasar los valores directamente en el execute
    if ($stmt->execute([$nombre, $email, $monto, $metodo, $mensaje])) {
        header("Location: ../public/index.php?donacion=ok");
        exit();
    } else {
        echo "❌ Error al registrar.";
    }
}
?>
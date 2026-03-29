<?php

$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "bdsocial"; 

$conn = new mysqli($host, $usuario, $password, $base_datos);

// Validar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
} else {
    echo "✅ Conexión exitosa a la base de datos";
}

?>
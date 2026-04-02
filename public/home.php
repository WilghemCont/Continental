<?php
// ... después de validar session_start() ...

$rol = $_SESSION["rol"];
$tipo = $_SESSION["tipo_doc"];

echo "<h1>Bienvenido " . $_SESSION["nombre"] . "</h1>";

if ($tipo == 'RUC') {
    echo "<div>Módulo: Gestión de Facturación y Empresa (ADMIN)</div>";
} else {
    echo "<div>Módulo: Perfil de Usuario y Postulaciones</div>";
}

if ($rol == 'ADMIN') {
    echo "<button class='btn btn-danger'>Panel de Configuración Maestra</button>";
}
?>
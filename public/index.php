<?php
// Iniciamos la sesión para verificar si el usuario ya entró
session_start();

// Definimos la ruta base del proyecto para evitar problemas de rutas
define("BASE_URL", "/CONTINENTAL/public/"); 

// LÓGICA DE ACCESO
if (isset($_SESSION["idlogin"])) {
    // Si ya está logueado, lo mandamos al home
    header("Location: ../view/index.html");
} else {
    // Si no hay sesión, lo mandamos al login por defecto
    header("Location: ../view/index.html");
}
exit();
?>
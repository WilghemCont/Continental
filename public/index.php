<?php
// public/index.php
session_start();

// 1. Rutas hacia atrás (subimos a la raíz)
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../config/constants.php"; 

$controllerReq = isset($_GET['controller']) ? $_GET['controller'] : '';
$actionReq     = isset($_GET['action'])     ? $_GET['action']     : '';

if ($controllerReq != '' && $actionReq != '') {
    $className = ucfirst(strtolower($controllerReq)) . "Controller";
    $controllerFile = __DIR__ . "/../Controllers/" . $className . ".php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        if (class_exists($className)) {
            $controllerObject = new $className();
            
            if (method_exists($controllerObject, $actionReq)) {
                // LLAMADA A LA ACCIÓN
                $controllerObject->$actionReq();
                
                // CRÍTICO: Detener el script aquí para que no llegue a las redirecciones de abajo
                exit(); 
            }
        }
    }
}

// ESTA PARTE SOLO DEBE EJECUTARSE SI NO SE ENTRÓ AL IF ANTERIOR
if (isset($_SESSION["idlogin"])) {
    header("Location: ../view/home.php");
} else {
    header("Location: ../view/login.php");
}
exit();
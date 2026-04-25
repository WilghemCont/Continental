<?php
// public/index.php
session_start();

// 1. Rutas hacia atrás (subimos a la raíz)
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../config/constants.php"; 

$controllerReq = isset($_GET['controller']) ? $_GET['controller'] : '';
$actionReq     = isset($_GET['action'])     ? $_GET['action']     : '';

if ($controllerReq != '' && $actionReq != '') {
    
    // Según tu imagen, los archivos se llaman: CasoController.php
    // Construimos el nombre exacto
    $className = ucfirst(strtolower($controllerReq)) . "Controller";
    
    // IMPORTANTE: Cambiamos a "../Controllers/" con C mayúscula y S al final
    $controllerFile = __DIR__ . "/../Controllers/" . $className . ".php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        if (class_exists($className)) {
            $controllerObject = new $className();
            
            if (method_exists($controllerObject, $actionReq)) {
                $controllerObject->$actionReq();
                exit(); 
            }
        }
    } else {
        // Descomenta la siguiente línea si quieres ver en pantalla qué ruta está fallando:
        // die("No se encontró el archivo en: " . $controllerFile);
    }
}

// 3. Si no hay petición de controlador, mandamos al home
if (isset($_SESSION["idlogin"])) {
    header("Location: ../view/home.php");
} else {
    header("Location: ../view/login.php");
}
exit();
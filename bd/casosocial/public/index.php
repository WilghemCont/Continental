<?php
session_start();

// CONFIGURACIÓN
require_once __DIR__ . "/../config/conexion.php";

// ROUTER
$controllerReq = $_GET['controller'] ?? 'caso';
$actionReq     = $_GET['action'] ?? 'dashboard';

// Nombre del controlador
$className = ucfirst($controllerReq) . "Controller";

// Ruta correcta (NUEVA)
$controllerFile = __DIR__ . "/../app/controllers/" . $className . ".php";

// VALIDACIÓN
if (file_exists($controllerFile)) {

    require_once $controllerFile;

    if (class_exists($className)) {

        $controllerObject = new $className();

        if (method_exists($controllerObject, $actionReq)) {
            $controllerObject->$actionReq();
            exit();
        }
    }
}

// DEFAULT (fallback)
header("Location: index.php?controller=caso&action=dashboard");
exit();
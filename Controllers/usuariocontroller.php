<?php
session_start();
require_once("../config/conexion.php");
require_once("../models/usuariomodel.php");

$usuario = new Usuario();

switch($_GET["op"]) {
    
    case "guardar_registro":
        // Recibimos datos del formulario
        $datos = [
            "tipo_documento" => $_POST["tipo_doc"], 
            "documento"      => $_POST["num_doc"],
            "contrasena"     => $_POST["password"],
            "correo"         => $_POST["email"],
            "nombres"        => $_POST["nombres"], // Si es RUC, esto es la Razón Social
            "apepat"         => $_POST["apepat"] ?? null,
            "apemat"         => $_POST["apemat"] ?? null,
            "fechanac"       => $_POST["fecha_nac"],
            "direccion"      => $_POST["direccion"]
        ];

        $res = $usuario->registrar_usuario($datos);
        echo $res ? "1" : "0";
        break;

    case "acceso":
        $correo = $_POST["correo"];
        $password = $_POST["password"];

        $datos = $usuario->login_acceso($correo, $password);

        if($datos) {
            // Guardamos todo lo necesario en la sesión
            $_SESSION["idlogin"]    = $datos["idlogin"];
            $_SESSION["idusuario"]  = $datos["idusuario"];
            $_SESSION["nombre"]     = $datos["nombres"];
            $_SESSION["tipo_doc"]   = $datos["tipo_documento"];
            // Si no tiene rol en la tabla intermedia, le asignamos 'USER' por defecto
            $_SESSION["rol"]        = ($datos["rol"]) ? $datos["rol"] : 'USER';
            
            echo "1";
        } else {
            echo "0";
        }
        break;
    
    case "combo_tipodoc":
        $datos = $usuario->get_tipos_documento();
        if(is_array($datos) == true && count($datos) > 0) {
            $html = "<option value='' selected>Seleccione</option>";
            foreach($datos as $row) {
                // Usamos la abreviatura en mayúsculas como value para que tu lógica de "RUC" funcione
                $html .= "<option value='".$row['abreviatura']."'>".$row['nombre']."</option>";
            }
            echo $html;
        }
        break;

    case "logout":
        session_destroy();
        header("Location: ../view/login.php");
        break;
}
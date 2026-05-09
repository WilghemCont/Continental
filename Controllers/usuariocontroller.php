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
            $_SESSION["idlogin"]   = $datos["idlogin"];
            $_SESSION["idusuario"] = $datos["idusuario"];
            $_SESSION["nombre"]    = $datos["nombres"];   // De tabla usuario
            $_SESSION["apepat"]    = $datos["apepat"];    // De tabla usuario
            $_SESSION["tipo"]      = $datos["tipo"];      // El campo 'tipo' de la tabla usuario           
            $_SESSION["correo"]      = $datos["correo"];
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
    
    case "combo_pais":
        $datos = $usuario->get_paises();
        $html = "";
        foreach($datos as $row) {
            $html .= "<option value='".$row['idpais']."'>".$row['nombre']."</option>";
        }
        echo $html;
        break;

    case "combo_departamento":
        $datos = $usuario->get_departamentos();
        $html = "<option value=''>Seleccione Departamento</option>";
        foreach($datos as $row) {
            $html .= "<option value='".$row['iddepartamento']."'>".$row['nombre']."</option>";
        }
        echo $html;
        break;

    case "combo_provincia":
        // Recibimos el ID del departamento por POST
        $datos = $usuario->get_provincias($_POST["iddepartamento"]);
        $html = "<option value=''>Seleccione Provincia</option>";
        foreach($datos as $row) {
            $html .= "<option value='".$row['idprovincia']."'>".$row['nombre']."</option>";
        }
        echo $html;
        break;

    case "combo_distrito":
        $datos = $usuario->get_distritos($_POST["idprovincia"]);
        $html = "<option value=''>Seleccione Distrito</option>";
        foreach($datos as $row) {
            $html .= "<option value='".$row['iddistrito']."'>".$row['nombre']."</option>";
        }
        echo $html;
        break;

    case "logout":
        session_destroy();
        header("Location: ../view/home.php");
        break;
}
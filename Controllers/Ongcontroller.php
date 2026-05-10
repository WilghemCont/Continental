<?php

require_once "../models/Ong.php";

class OngController {

    private $model;

    public function __construct(){
        session_start();

        // Seguridad
        if(!isset($_SESSION["idlogin"])){
            header("Location: ../login.php");
            exit;
        }

        if($_SESSION["tipo"] != "ONG"){
            die("Acceso denegado");
        }

        $this->model = new Ong();
    }

    // Vista formulario
    public function registrar(){

        require_once "../views/registrarCaso.php";
    }

    // Guardar caso
    public function guardar(){

        $documento = "";
        $foto = "";

        // Subir PDF
        if($_FILES["documento_solicitud"]["name"] != ""){

            $documento = time() . "_" . $_FILES["documento_solicitud"]["name"];

            move_uploaded_file(
                $_FILES["documento_solicitud"]["tmp_name"],
                "../uploads/documentos/" . $documento
            );
        }

        // Subir foto
        if($_FILES["foto_beneficiario"]["name"] != ""){

            $foto = time() . "_" . $_FILES["foto_beneficiario"]["name"];

            move_uploaded_file(
                $_FILES["foto_beneficiario"]["tmp_name"],
                "../uploads/beneficiarios/" . $foto
            );
        }

        $data = [
            "nombre_ong" => $_POST["nombre_ong"],
            "ruc_ong" => $_POST["ruc_ong"],
            "contacto_ong" => $_POST["contacto_ong"],
            "email_ong" => $_POST["email_ong"],
            "titulo_caso" => $_POST["titulo_caso"],
            "titulo_publico" => $_POST["titulo_publico"],
            "clasificacion" => $_POST["clasificacion"],
            "descripcion" => $_POST["descripcion"],
            "descripcion_publica" => $_POST["descripcion_publica"],
            "monto_requerido" => $_POST["monto_requerido"],
            "ubicacion" => $_POST["ubicacion"],
            "nombre_beneficiario" => $_POST["nombre_beneficiario"],
            "dni_beneficiario" => $_POST["dni_beneficiario"],
            "edad_beneficiario" => $_POST["edad_beneficiario"],
            "documento_solicitud" => $documento,
            "foto_beneficiario" => $foto,
            "beneficiario_id" => $_SESSION["idusuario"]
        ];

        $this->model->registrarCaso($data);

        header("Location: Ongcontroller.php?action=misCasos");
    }

    // Listar casos
    public function misCasos(){

        $casos = $this->model->listarCasos($_SESSION["idusuario"]);

        require_once "../views/misCasos.php";
    }

}

$controller = new OngController();

if(isset($_GET["action"])){

    switch($_GET["action"]){

        case "registrar":
            $controller->registrar();
        break;

        case "guardar":
            $controller->guardar();
        break;

        case "misCasos":
            $controller->misCasos();
        break;
    }
}
?>
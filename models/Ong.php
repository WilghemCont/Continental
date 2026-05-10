<?php

require_once __DIR__ . "/../config/conexion.php";
class Ong extends Conectar {

     private $db;

    public function __construct()
    {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }
    // Registrar caso social
    public function registrarCaso($data){

        try {

            $sql = "INSERT INTO casos_sociales(
                        nombre_ong,
                        ruc_ong,
                        contacto_ong,
                        email_ong,
                        titulo_caso,
                        titulo_publico,
                        clasificacion,
                        descripcion,
                        descripcion_publica,
                        monto_requerido,
                        ubicacion,
                        nombre_beneficiario,
                        dni_beneficiario,
                        edad_beneficiario,
                        documento_solicitud,
                        foto_beneficiario,
                        beneficiario_id,
                        estado_evaluacion,
                        publicado,
                        estado_proceso
                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'pendiente',0,'sin_proceso')";

            $query = $this->pdo->prepare($sql);

            return $query->execute([
                $data["nombre_ong"],
                $data["ruc_ong"],
                $data["contacto_ong"],
                $data["email_ong"],
                $data["titulo_caso"],
                $data["titulo_publico"],
                $data["clasificacion"],
                $data["descripcion"],
                $data["descripcion_publica"],
                $data["monto_requerido"],
                $data["ubicacion"],
                $data["nombre_beneficiario"],
                $data["dni_beneficiario"],
                $data["edad_beneficiario"],
                $data["documento_solicitud"],
                $data["foto_beneficiario"],
                $data["beneficiario_id"]
            ]);

        } catch(Exception $e){
            die($e->getMessage());
        }
    }

    // Listar casos
    public function listarCasos($beneficiario_id){

        try {

            $sql = "SELECT * FROM casos_sociales
                    WHERE beneficiario_id = ?
                    ORDER BY fecha_registro DESC";

            $query = $this->pdo->prepare($sql);
            $query->execute([$beneficiario_id]);

            return $query->fetchAll(PDO::FETCH_ASSOC);

        } catch(Exception $e){
            die($e->getMessage());
        }
    }

}
?>
<?php
/**
 * models/Publicacion.php
 */
require_once("../config/conexion.php");

class Publicacion extends Conectar {
    private $db;

    public function __construct() {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }

    public function obtenerDatosPublicacion($idCaso) {
        // Obtenemos los datos actuales del caso para el formulario de publicación
        $sql = "SELECT id, titulo_caso, descripcion, foto_beneficiario, documento_solicitud, 
                       titulo_publico, descripcion_publica, publicado 
                FROM casos_sociales WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCaso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($idCaso, $data, $accion) {
        // Determinamos si se publica (1) o se queda en borrador (0)
        $publicado = ($accion === 'publicar') ? 1 : 0;
        $fechaPub = ($accion === 'publicar') ? "NOW()" : "NULL";

        $sql = "UPDATE casos_sociales SET 
                    titulo_publico = :titulo,
                    descripcion_publica = :descripcion,                    
                    publicado = :publicado,
                    fecha_publicacion = $fechaPub,
                    estado_proceso = :estado_proc,
                    estado_evaluacion = :estado_eval
                WHERE id = :id";

        $estadoProceso = ($accion === 'publicar') ? 'en_proceso' : 'sin_proceso';
        $estadoEvaluacion = ($accion === 'publicar') ? 'publicado' : 'no publicado';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'      => $data['titulo'],
            ':descripcion' => $data['descripcion'],            
            ':publicado'   => $publicado,
            ':estado_proc' => $estadoProceso,
            ':estado_eval' => $estadoEvaluacion,
            ':id'          => $idCaso
        ]);
    }
}
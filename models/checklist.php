<?php
require_once __DIR__ . "/../config/conexion.php";

class Checklist extends Conectar {
    private $db;

    public function __construct() {
        $this->db = parent::Conexion();
    }

    public function obtenerPorTipo($tipo) {
        $sql = "SELECT * FROM checklist_items WHERE tipo = ? AND estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPorCasoYTipo($idCaso, $tipo)
    {
        $sql = "DELETE cr
                FROM checklist_respuestas cr
                INNER JOIN checklist_items ci
                    ON ci.id = cr.item_id
                WHERE cr.caso_id = ?
                AND ci.tipo = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$idCaso, $tipo]);
    }

    public function guardarRespuesta($idCaso, $idItem, $estado, $comentario) {
        $sql = "INSERT INTO checklist_respuestas (caso_id, item_id, estado, comentario) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCaso, $idItem, $estado, $comentario]);
    }
}
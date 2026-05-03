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

    public function eliminarPorCaso($idCaso) {
        $sql = "DELETE FROM checklist_respuestas WHERE caso_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCaso]);
    }

    public function guardarRespuesta($idCaso, $idItem, $estado, $comentario) {
        $sql = "INSERT INTO checklist_respuestas (caso_id, item_id, estado, comentario) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCaso, $idItem, $estado, $comentario]);
    }
}
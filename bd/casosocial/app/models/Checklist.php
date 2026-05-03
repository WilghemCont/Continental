<?php

class Checklist {

    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function obtenerPorTipo($tipo){

        $sql = "SELECT * FROM checklist_items WHERE tipo = :tipo";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':tipo' => $tipo]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar respuestas anteriores
    public function eliminarPorCaso($idCaso) {

        $sql = "DELETE FROM checklist_respuestas WHERE id_caso = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idCaso]);
    }


    // Guardar respuesta
    public function guardarRespuesta($idCaso, $idItem, $estado, $comentario) {

        $sql = "INSERT INTO checklist_respuestas 
                (id_caso, id_item, estado, comentario)
                VALUES (:caso, :item, :estado, :comentario)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':caso' => $idCaso,
            ':item' => $idItem,
            ':estado' => $estado,
            ':comentario' => $comentario
        ]);
    }

}
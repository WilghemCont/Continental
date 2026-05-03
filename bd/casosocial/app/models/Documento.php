<?php

class Documento {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorCaso($idCaso) {

        $sql = "SELECT * FROM documentos_caso WHERE id_caso = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idCaso]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($data) {

    $sql = "INSERT INTO documentos_caso
            (id_caso, tipo, nombre_archivo, ruta_archivo, tipo_mime, tamanio)
            VALUES (:caso, :tipo, :nombre, :ruta, :mime, :size)";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':caso' => $data['id_caso'],
        ':tipo' => $data['tipo'],
        ':nombre' => $data['nombre_archivo'],
        ':ruta' => $data['ruta_archivo'],
        ':mime' => $data['tipo_mime'],
        ':size' => $data['tamanio']
    ]);
}
}
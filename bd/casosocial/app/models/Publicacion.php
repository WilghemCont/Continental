<?php

class Publicacion {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerPorCaso($idCaso) {

        $sql = "SELECT * FROM publicaciones WHERE id_caso = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idCaso]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($idCaso, $data, $accion) {

        $estado = ($accion === 'publicar') ? 'publicado' : 'borrador';

        $existe = $this->obtenerPorCaso($idCaso);

        if ($existe) {

            $sql = "UPDATE publicaciones SET
                    titulo_publico = :titulo,
                    descripcion_publica = :descripcion,
                    imagen_portada = :imagen,
                    archivo_pdf = :pdf,
                    estado = :estado,
                    fecha_publicacion = NOW()
                    WHERE id_caso = :id";

        } else {

            $sql = "INSERT INTO publicaciones
                    (id_caso, titulo_publico, descripcion_publica, imagen_portada, archivo_pdf, estado, fecha_publicacion)
                    VALUES (:id, :titulo, :descripcion, :imagen, :pdf, :estado, NOW())";
        }

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $idCaso,
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'],
            ':imagen' => $data['imagen'],
            ':pdf' => $data['pdf'],
            ':estado' => $estado
        ]);
    }
}
<?php

require_once __DIR__ . "/../models/CasoSocial.php";
require_once __DIR__ . "/../models/Publicacion.php";

class PublicacionController {

    private $casoModel;
    private $pubModel;

    public function __construct() {
        global $pdo;

        $this->casoModel = new CasoSocial($pdo);
        $this->pubModel  = new Publicacion($pdo);
    }

    // VER FORMULARIO
    public function crear() {

        $id = $_GET['id'];

        $caso = $this->casoModel->obtener($id);

        // 🔒 SOLO APROBADOS
        if ($caso['estado'] !== 'aprobado') {
            die("Solo se pueden publicar casos aprobados");
        }

        $publicacion = $this->pubModel->obtenerPorCaso($id);

        require_once __DIR__ . "/../views/publicar.php";
    }

    // GUARDAR
    public function guardar() {

        try {

            $idCaso = $_GET['id'];

            $data = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'imagen' => $_POST['imagen'] ?? null,
                'pdf' => $_POST['pdf'] ?? null
            ];

            $accion = $_POST['accion']; // borrador o publicar

            $this->pubModel->guardar($idCaso, $data, $accion);

            // 🔥 SI PUBLICA → CAMBIA ESTADO
            if ($accion === 'publicar') {
                $this->casoModel->cambiarEstado($idCaso, 'publicado', $_SESSION['idlogin'] ?? null);
            }

            header("Location: index.php?controller=caso&action=dashboard");
            exit();

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
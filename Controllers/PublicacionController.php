<?php
/**
 * Controllers/PublicacionController.php
 */
require_once __DIR__ . "/../models/CasoSocial.php";
require_once __DIR__ . "/../models/Publicacion.php";

class PublicacionController {
    private $casoModel;
    private $pubModel;

    public function __construct() {
        // En tu arquitectura, los modelos heredan de Conectar, no necesitan $pdo global
        $this->casoModel = new CasoSocial();
        $this->pubModel  = new Publicacion();
    }

    public function crear() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Obtenemos los datos completos del caso
        $caso = $this->casoModel->obtenerPorId($id);

        if (!$caso) {
            header("Location: index.php?controller=caso&action=bandeja");
            exit();
        }

        // 🔒 SEGURIDAD: Solo casos aprobados pueden publicarse
        if ($caso['estado_evaluacion'] !== 'aprobado') {
            echo "<script>alert('El caso debe estar APROBADO para ser configurado.'); history.back();</script>";
            exit();
        }

        // Cargamos los datos específicos de publicación
        $publicacion = $this->pubModel->obtenerDatosPublicacion($id);

        require_once __DIR__ . "/../view/publicar.php";
    }

    public function guardar() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            $idCaso = (int)$_GET['id'];
            $accion = $_POST['accion_tipo'] ?? 'borrador'; // borrador o publicar

            $data = [
                'titulo'      => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'imagen'      => $_POST['imagen'] ?? null,
                'pdf'         => $_POST['pdf'] ?? null
            ];

            $this->casoModel->beginTransaction();

            // 1. Guardar datos en la tabla principal
            $this->pubModel->guardar($idCaso, $data, $accion);

            // 2. Registrar en Historial de Auditoría
            $usuario = $_SESSION['nombre'] ?? 'Admin';
            $comentario = ($accion === 'publicar') ? "Caso publicado oficialmente" : "Borrador de publicación actualizado";
            
            // Usamos el método de historial que ya tienes en CasoSocial
            $db = (new Conectar())->Conexion();
            $sqlHist = "INSERT INTO historial_casos (caso_id, tipo_cambio, valor_nuevo, usuario, comentario) 
                        VALUES (?, 'publicacion', ?, ?, ?)";
            $db->prepare($sqlHist)->execute([$idCaso, $accion, $usuario, $comentario]);

            $this->casoModel->commit();

            echo json_encode([
                'ok' => true, 
                'mensaje' => ($accion === 'publicar') ? '¡El caso ha sido publicado con éxito!' : 'Borrador guardado.'
            ]);

        } catch (Exception $e) {
            if($this->casoModel) $this->casoModel->rollBack();
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }
}
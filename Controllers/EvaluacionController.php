
<?php
require_once __DIR__ . "/../models/CasoSocial.php";
require_once __DIR__ . "/../models/Checklist.php";

class EvaluacionController {
    private $casoModel;
    private $checkModel;

    public function __construct() {
        $this->casoModel = new CasoSocial();
        $this->checkModel = new Checklist();
    }

    public function ver() {
        // 1. Validar el ID
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header("Location: index.php?controller=caso&action=bandeja");
            exit();
        }

        // 2. OBTENER EL CASO (Asegúrate de que el nombre del método sea obtenerPorId)
        // Guardamos el resultado en la variable $caso, que es la que busca la vista
        $caso = $this->casoModel->obtenerPorId($id); 

        // 3. Validar que el caso exista en la base de datos
        if (!$caso) {
            header("Location: index.php?controller=caso&action=bandeja&msg=no_encontrado");
            exit();
        }

        // 4. Obtener el checklist
        $checklist = $this->checkModel->obtenerPorTipo('evaluacion');

        // 5. Cargar la vista (Al estar aquí, $caso ya es visible para evaluacion.php)
        require_once __DIR__ . "/../view/evaluacion.php";
    }

    public function guardar() {
        // Limpiar cualquier eco o espacio en blanco previo
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        try {
            $idCaso = (int)$_GET['id'];
            if (!isset($_POST['check'])) {
                throw new Exception("Debe responder todos los ítems del checklist.");
            }

            $checks = $_POST['check'];
            $comentarios = $_POST['comentario'] ?? [];
            $usuarioNombre = $_SESSION['nombre'] ?? 'Sistema';

            // INICIAR TRANSACCIÓN (Vía CasoModel ya que heredan de Conectar)
            $this->casoModel->beginTransaction();

            $hayNo = false;
            $this->checkModel->eliminarPorCaso($idCaso);

            foreach ($checks as $id_item => $estado) {
                $comentario = $comentarios[$id_item] ?? null;

                if ($estado === 'NO') {
                    if (empty($comentario)) {
                        throw new Exception("Debe ingresar un motivo para los ítems NO cumplidos.");
                    }
                    $hayNo = true;
                }
                $this->checkModel->guardarRespuesta($idCaso, $id_item, $estado, $comentario);
            }

            // Determinar estado final
            $nuevoEstado = $hayNo ? 'observado' : 'aprobado';
            
            // Actualizar tabla principal y log
            $this->actualizarEstadoYLog($idCaso, $nuevoEstado, $usuarioNombre);

            // COMMIT DE LOS CAMBIOS
            $this->casoModel->commit();

            echo json_encode([
                'ok' => true, 
                'mensaje' => 'La evaluación se ha guardado correctamente. El caso ahora está: ' . strtoupper($nuevoEstado)
            ]);

        } catch (Exception $e) {
            // REVERTIR CAMBIOS SI ALGO FALLÓ
            if ($this->casoModel) $this->casoModel->rollBack();
            
            echo json_encode([
                'ok' => false, 
                'error' => $e->getMessage()
            ]);
        }
        exit();
    }

    private function actualizarEstadoYLog($id, $estado, $usuario) {
        $db = (new Conectar())->Conexion();
        
        // 1. Obtener valor anterior para el historial
        $stmt = $db->prepare("SELECT estado_evaluacion FROM casos_sociales WHERE id = ?");
        $stmt->execute([$id]);
        $anterior = $stmt->fetchColumn();

        // 2. Actualizar Caso
        $sqlUpd = "UPDATE casos_sociales SET estado_evaluacion = ?, fecha_evaluacion = NOW() WHERE id = ?";
        $db->prepare($sqlUpd)->execute([$estado, $id]);

        // 3. Registrar en historial_casos (Tu tabla de log)
        $sqlLog = "INSERT INTO historial_casos (caso_id, tipo_cambio, valor_anterior, valor_nuevo, usuario, comentario) 
                   VALUES (?, 'evaluacion_checklist', ?, ?, ?, 'Evaluación mediante checklist finalizada')";
        $db->prepare($sqlLog)->execute([$id, $anterior, $estado, $usuario]);
    }
}
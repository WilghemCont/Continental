<?php
/**
 * controller/CasoController.php
 */
require_once "../models/CasoSocial.php";
require_once "../models/historial.php";

class CasoController
{
    private $model;

    public function __construct()
    {
        $this->model = new CasoSocial();
    }

    public function bandeja(): void
    {
        $filtros = [
            'clasificacion'     => trim($_GET['clasificacion']     ?? ''),
            'estado_evaluacion' => trim($_GET['estado_evaluacion'] ?? ''),
            'estado_proceso'    => trim($_GET['estado_proceso']    ?? ''),
            'buscar'            => trim($_GET['buscar']            ?? ''),
        ];

        $casos = $this->model->listar($filtros);
        $stats = $this->model->estadisticas();

        // Rutas relativas desde el index.php de la raíz
        
        require_once "../view/bandeja.php";
        
    }

    public function listar(): void
    {
        header('Content-Type: application/json');

        $filtros = [
            'clasificacion'     => trim($_GET['clasificacion']     ?? ''),
            'estado_evaluacion' => trim($_GET['estado_evaluacion'] ?? ''),
            'estado_proceso'    => trim($_GET['estado_proceso']    ?? ''),
            'buscar'            => trim($_GET['buscar']            ?? ''),
        ];

        $casos = $this->model->listar($filtros);
        $stats = $this->model->estadisticas();

        // Limpiamos cualquier salida previa para enviar un JSON puro
        ob_clean();
        echo json_encode(compact('casos', 'stats'));
        exit;
    }

    public function ver() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            header("Location: index.php?controller=caso&action=bandeja&msg=id_invalido");
            exit;
        }

        $caso = $this->model->obtenerPorId($id);

        if (!$caso) {
            header("Location: index.php?controller=caso&action=bandeja&msg=no_encontrado");
            exit;
        }

        // 🔥 IMPORTANTE: cargar layout completo
       
        require_once "../view/ver_caso.php";
       
    }

    public function detalle(): void
    {
        // 1. Forzamos que la salida sea JSON
        header('Content-Type: application/json');

        try {
            $id = (int)($_GET['id'] ?? 0);
            
            // Verificamos que el modelo exista
            if (!$this->model) {
                $this->model = new CasoSocial();
            }

            $caso = $this->model->obtenerPorId($id);

            if (!$caso) {
                echo json_encode(['error' => 'Caso no encontrado en la BD']);
                exit;
            }

            // 2. Limpiamos cualquier "basura" o notices que PHP haya escupido antes
            if (ob_get_length()) ob_clean();

            // Enviamos solo el objeto del caso por ahora para asegurar que funcione
            echo json_encode(['caso' => $caso]);
            exit;

        } catch (Exception $e) {
            // Si algo falla, lo devolvemos como JSON, no como error de PHP
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    public function guardar()
    {
        // 1. Limpiar cualquier salida previa (espacios, errores, etc.)
        if (ob_get_level()) ob_clean(); 
        header('Content-Type: application/json');

        try {
            $uploadDir = __DIR__ . "/../assets/uploads/";
            $documento = $this->subirArchivo($_FILES['documento_solicitud'] ?? null, $uploadDir . "docs/");
            $foto = $this->subirArchivo($_FILES['foto_beneficiario'] ?? null, $uploadDir . "fotos/");

            $datos = [
                'nombre_ong'          => $_POST['nombre_ong'] ?? '',
                'ruc_ong'             => $_POST['ruc_ong'] ?? '',
                'email_ong'           => $_POST['email_ong'] ?? '',
                'contacto_ong'        => $_POST['contacto_ong'] ?? '',
                'titulo_caso'         => $_POST['titulo_caso'] ?? '',
                'clasificacion'       => $_POST['clasificacion'] ?? '',
                'monto_requerido'     => $_POST['monto_requerido'] ?? 0,
                'descripcion'         => $_POST['descripcion'] ?? '',
                'nombre_beneficiario' => $_POST['nombre_beneficiario'] ?? '',
                'dni_beneficiario'    => $_POST['dni_beneficiario'] ?? '',
                'edad_beneficiario'   => !empty($_POST['edad_beneficiario']) ? $_POST['edad_beneficiario'] : null,
                'ubicacion'           => $_POST['ubicacion'] ?? '',
                'documento'           => $documento,
                'foto'                => $foto
            ];

            $res = $this->model->insertar($datos);
            
            // 2. Respuesta JSON estricta
            echo json_encode(['ok' => $res]);
            exit;

        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    private function subirArchivo($file, $dir)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) return null;
        
        if (!is_dir($dir)) mkdir($dir, 0777, true); // Crea la carpeta si no existe

        $nombre = time() . "_" . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $dir . $nombre)) {
            return $nombre;
        }
        return null;
    }

    public function catalogo() {

        $casos = $this->model->obtenerPublicados();

        
        require_once "../view/Catalogo.php";
    }

        public function vistaCaso()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            die("Caso inválido");
        }

        $caso = $this->model->obtenerPorId($id);

        if (!$caso) {
            die("Caso no encontrado");
        }

        // compatibilidad con tu vista actual
        $publicacion = $caso;

        // ejemplo temporal
        $cantidadDonaciones = 0;

        
        require_once "../view/vista_caso.php";
        
    }

    public function historia()
    {
        // Traer campañas publicadas
        $casos = $this->model->obtenerPublicados();
        // Tomamos una (la primera como "campaña activa")
        $campaniaActiva = $casos[0] ?? null;
        require_once "../view/historia_donador.php";        
    }

    public function vista()
    {
        $id = $_GET['id'] ?? 0;

        $caso = $this->model->obtenerPorId($id);

        if (!$caso) {
            die("Caso no encontrado");
        }

        // SOLO PUBLICADOS
        if (
            $caso['estado_evaluacion'] !== 'publicado'
            && $caso['publicado'] != 1
        ) {
            die("Caso no disponible");
        }

        $cantidadDonaciones = 0;

        require_once "../view/vista_caso.php";
    }
}
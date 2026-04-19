<?php
class Conectar {
    protected $dbh;

    public function Conexion() {
        try {
            // 1. Añadimos opciones para manejo de errores y emulación de preparados
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            // 2. Conexión (Asegúrate que el dbname sea 'bdsocial' o el que creaste)
            $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=bdsocial", "root", "", $opciones);
            
            return $conectar;
        } catch (Exception $e) {
            die("¡Error BD!: " . $e->getMessage());
        }
    }

    public function set_names() {
        return $this->dbh->query("SET NAMES 'utf8'");
    }

    // 3. Función para la URL base (útil para assets)
    public static function ruta() {
        // Ajusta esto a la carpeta de tu proyecto
        return "http://localhost/CONTINENTAL/";
    }
}

// 4. Definimos la constante global si no existe
if (!defined('BASE_URL')) {
    define('BASE_URL', Conectar::ruta());
}
?>
<?php
// ============================================================
// config/database.php — Conexión a la base de datos (XAMPP)
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'donaciones_db');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/donaciones/');
define('BASE_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', BASE_PATH . 'uploads/transferencias/');
define('UPLOAD_URL',  BASE_URL  . 'uploads/transferencias/');

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                die('<div style="font-family:sans-serif;background:#fee;padding:20px;border-radius:8px;margin:20px;">
                    <h3>❌ Error de conexión a la base de datos</h3>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p>Verifica que XAMPP esté activo y la BD <strong>' . DB_NAME . '</strong> exista.</p>
                </div>');
            }
        }
        return self::$instance;
    }
}

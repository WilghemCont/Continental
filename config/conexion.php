<?php
class Conectar {
    protected $dbh;

    protected function Conexion() {
        try {
            // Usamos PDO para que sea compatible con el Modelo
            $conectar = $this->dbh = new PDO("mysql:host=localhost;dbname=bdsocial", "root", "");
            return $conectar;
        } catch (Exception $e) {
            die("¡Error BD!: " . $e->getMessage());
        }
    }

    public function set_names() {
        return $this->dbh->query("SET NAMES 'utf8'");
    }
}
?>
<?php
require_once __DIR__ . "/../config/conexion.php";

class DonacionModel extends Conectar {
    private $db;

    public function __construct() {
        $this->db = parent::Conexion();
    }
    
  public function obtenerTotalesPorMetodo() {
       // $db = parent::Conexion();
        // Agrupamos por método de pago (Yape, Plin, Transferencia, etc.)
        $sql = "SELECT metodo, SUM(monto) as total FROM donaciones GROUP BY metodo";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSumaTotal() {
       // $db = parent::Conexion();
        $sql = "SELECT SUM(monto) as total_general FROM donaciones";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_general'] ?? 0;
    }
    public function obtenerDonacionesPorMes() {
       // $db = parent::Conexion();
        $sql = "SELECT DATE_FORMAT(fecha,'%Y-%m') as mes, SUM(monto) as total 
                FROM donaciones 
                GROUP BY mes 
                ORDER BY mes ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTopDonantes() {
       // $db = parent::Conexion();
        $sql = "SELECT nombre, SUM(monto) as total 
                FROM donaciones 
                GROUP BY nombre 
                ORDER BY total DESC 
                LIMIT 5";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKPIs(): array {
        // Quitamos el WHERE estado_evaluacion='observado' para ver datos de casos aprobados/activos
        $sql = "SELECT
                    COUNT(*) AS total_donaciones,
                    IFNULL(SUM(d.monto), 0) AS total_monto,
                    IFNULL(AVG(d.monto), 0) AS promedio_monto,
                    COUNT(DISTINCT d.id) AS total_donadores,
                    COUNT(DISTINCT d.idcasosocial) AS casos_con_donacion
                FROM donaciones d
                LEFT JOIN casos_sociales cs ON d.idcasosocial = cs.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}
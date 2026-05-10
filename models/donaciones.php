<?php
require_once __DIR__ . "/../config/conexion.php";

class DonacionModel extends Conectar {
    private $db;

    public function __construct() {
        $this->db = parent::Conexion();
    }

    public function obtenerTotalesPorMetodo() {
        $sql = "SELECT metodo, SUM(monto) as total FROM donaciones GROUP BY metodo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSumaTotal() {
        $sql = "SELECT SUM(monto) as total_general FROM donaciones";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_general'] ?? 0;
    }

    public function obtenerDonacionesPorMes() {
        $sql = "SELECT DATE_FORMAT(fecha,'%Y-%m') as mes, SUM(monto) as total 
                FROM donaciones 
                GROUP BY mes 
                ORDER BY mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTopDonantes() {
        $sql = "SELECT nombre, SUM(monto) as total 
                FROM donaciones 
                GROUP BY nombre 
                ORDER BY total DESC 
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar(array $data) {
        $sql = "INSERT INTO donaciones (nombre, email, monto, metodo, mensaje, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['monto'],
            $data['metodo'],
            $data['mensaje']
        ]);
    }

    public function actualizarMontoCaso(int $casoId, float $monto) {
        $sql = "UPDATE casos_sociales SET monto_recaudado = COALESCE(monto_recaudado, 0) + ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$monto, $casoId]);
    }

    public function getKPIs(): array {
        $sql = "SELECT
                    COUNT(*) AS total_donaciones,
                    IFNULL(SUM(monto), 0) AS total_monto,
                    IFNULL(AVG(monto), 0) AS promedio_monto
                FROM donaciones";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPorUsuario(int $idUsuario): array {

    $sql = "SELECT d.*, cs.titulo_publico, cs.nombre_beneficiario
            FROM donaciones d
            LEFT JOIN casos_sociales cs 
                ON d.id = cs.id
            WHERE d.idusuario = ?
            ORDER BY d.fecha DESC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([$idUsuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT d.*, cs.titulo_publico, cs.nombre_beneficiario
                FROM donaciones d
                LEFT JOIN casos_sociales cs ON d.id = cs.id
                WHERE d.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}

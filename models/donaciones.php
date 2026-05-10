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

    public function getKPIsPanel(): array {
        // KPIs de donaciones por caso (tabla donaciones existente)
        $kpiBase = $this->getKPIs();
 
        // KPIs de ingresos / patrocinios (tabla ingresos existente)
        $sqlIngresos = "SELECT
            IFNULL(SUM(CASE WHEN tipo = 'Patrocinio' THEN monto_final END), 0)          AS total_patrocinios,
            IFNULL(SUM(CASE WHEN tipo = 'Comisión por donación' THEN monto_final END), 0) AS total_comisiones,
            IFNULL(SUM(monto_final), 0)                                                   AS total_ingresos,
            IFNULL(SUM(CASE WHEN fecha = CURDATE() THEN monto_final END), 0)              AS ingresos_hoy,
            IFNULL(SUM(CASE WHEN fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN monto_final END), 0) AS ingresos_semana,
            IFNULL(SUM(CASE WHEN fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN monto_final END), 0) AS ingresos_mes
        FROM ingresos";
        $stmt = $this->db->prepare($sqlIngresos);
        $stmt->execute();
        $kpiIngresos = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
 
        return array_merge($kpiBase, $kpiIngresos);
    }

    public function obtenerDonadores(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM donadores ORDER BY nombre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

     public function obtenerComisionesPorCaso(): array {
        $sql = "SELECT
                    nombre,
                    SUM(monto) AS total_donado,
                    SUM(
                        CASE
                            WHEN monto <= 10000 THEN monto * 0.03
                            ELSE monto * 0.05
                        END
                    ) AS comision,
                    CASE
                        WHEN SUM(monto) / COUNT(*) <= 10000 THEN 3
                        ELSE 5
                    END AS porcentaje
                FROM donaciones
                GROUP BY nombre
                ORDER BY total_donado DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



     public function listarCompleto(): array {
        // Patrocinios e ingresos (tabla ingresos existente en Continental)
        $sqlIngresos = "SELECT
                            i.id,
                            i.empresa          AS donador_nombre,
                            'empresa'          AS donador_tipo,
                            i.tipo             AS tipo_donacion,
                            i.monto_base       AS monto_total,
                            i.monto_final      AS monto_neto,
                            GREATEST(0, i.monto_base - i.monto_final) AS monto_comision,
                            i.porcentaje       AS porcentaje_comision,
                            'completado'       AS estado,
                            i.fecha,
                            i.descripcion      AS notas
                        FROM ingresos i
                        ORDER BY i.fecha DESC";
        $stmt = $this->db->prepare($sqlIngresos);
        $stmt->execute();
        $patrocinios = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        // Donaciones directas por caso (MercadoPago / Yape / Efectivo / etc.)
        $sqlDonaciones = "SELECT
                              d.id,
                              d.nombre           AS donador_nombre,
                              'persona'          AS donador_tipo,
                              'Donación'         AS tipo_donacion,
                              d.monto            AS monto_total,
                              d.monto            AS monto_neto,
                              0                  AS monto_comision,
                              0                  AS porcentaje_comision,
                              'completado'       AS estado,
                              d.fecha,
                              d.mensaje          AS notas
                          FROM donaciones d
                          ORDER BY d.fecha DESC";
        $stmtD = $this->db->prepare($sqlDonaciones);
        $stmtD->execute();
        $donacionesCaso = $stmtD->fetchAll(PDO::FETCH_ASSOC);
 
        // Unir ambos conjuntos ordenados por fecha descendente
        $todos = array_merge($patrocinios, $donacionesCaso);
        usort($todos, fn($a, $b) => strtotime((string)$b['fecha']) - strtotime((string)$a['fecha']));
 
        return $todos;
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

<?php
// ============================================================
// models/DonacionModel.php
// ============================================================
class DonacionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Donaciones de un caso social */
    public function getPorCaso(int $casoId): array {
        $stmt = $this->db->prepare("
            SELECT d.*, dn.nombre AS donador_nombre, dn.email AS donador_email
            FROM donaciones d
            JOIN donadores dn ON d.donador_id = dn.id
            WHERE d.caso_social_id = ?
            ORDER BY d.fecha_donacion DESC
        ");
        $stmt->execute([$casoId]);
        return $stmt->fetchAll();
    }

    /** Todas las donaciones con paginación */
    public function getTodas(int $limite = 50, int $offset = 0): array {
        $stmt = $this->db->prepare("
            SELECT d.*, dn.nombre AS donador_nombre,
                   cs.titulo AS caso_titulo
            FROM donaciones d
            JOIN donadores dn ON d.donador_id = dn.id
            JOIN casos_sociales cs ON d.caso_social_id = cs.id
            ORDER BY d.fecha_donacion DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limite, $offset]);
        return $stmt->fetchAll();
    }

    /** Total de donaciones */
    public function countTodas(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM donaciones")->fetchColumn();
    }

    /** Registrar nueva donación */
    public function registrar(array $data): int {
        // Buscar o crear donador
        $donadorId = $this->buscarOCrearDonador($data);

        $stmt = $this->db->prepare("
            INSERT INTO donaciones
                (caso_social_id, donador_id, monto, metodo_pago, codigo_transaccion, estado, notas)
            VALUES (?, ?, ?, ?, ?, 'verificado', ?)
        ");
        $stmt->execute([
            $data['caso_social_id'],
            $donadorId,
            $data['monto'],
            $data['metodo_pago'],
            $data['codigo_transaccion'] ?? null,
            $data['notas'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    private function buscarOCrearDonador(array $data): int {
        $stmt = $this->db->prepare("SELECT id FROM donadores WHERE email=? LIMIT 1");
        $stmt->execute([$data['donador_email']]);
        $row = $stmt->fetch();
        if ($row) return (int)$row['id'];

        $stmt = $this->db->prepare("
            INSERT INTO donadores (nombre, email, telefono) VALUES (?,?,?)
        ");
        $stmt->execute([$data['donador_nombre'], $data['donador_email'], $data['donador_telefono'] ?? '']);
        return (int)$this->db->lastInsertId();
    }

    /** KPIs para estadísticas */
    public function getKPIs(): array {
        $stmt = $this->db->query("
            SELECT
                COUNT(*) AS total_donaciones,
                SUM(monto) AS total_monto,
                AVG(monto) AS promedio_monto,
                COUNT(DISTINCT donador_id) AS total_donadores,
                COUNT(DISTINCT caso_social_id) AS casos_con_donacion
            FROM donaciones WHERE estado='verificado'
        ");
        return $stmt->fetch();
    }

    /** Donaciones por método de pago */
    public function getPorMetodoPago(): array {
        $stmt = $this->db->query("
            SELECT metodo_pago, COUNT(*) AS cantidad, SUM(monto) AS total
            FROM donaciones WHERE estado='verificado'
            GROUP BY metodo_pago ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }
}

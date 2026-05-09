<?php
// ============================================================
// models/CasoSocialModel.php
// ============================================================
class CasoSocialModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Todos los casos aprobados o completados */
    public function getActivos(): array {
        $stmt = $this->db->query("
            SELECT cs.*, o.nombre AS ong_nombre, o.email AS ong_email,
                   ROUND((cs.monto_recaudado / cs.meta_monto) * 100, 1) AS porcentaje
            FROM casos_sociales cs
            JOIN ongs o ON cs.ong_id = o.id
            WHERE cs.estado IN ('aprobado','completado','cerrado')
            ORDER BY cs.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /** Un caso por ID */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT cs.*, o.nombre AS ong_nombre, o.email AS ong_email,
                   ROUND((cs.monto_recaudado / cs.meta_monto) * 100, 1) AS porcentaje
            FROM casos_sociales cs
            JOIN ongs o ON cs.ong_id = o.id
            WHERE cs.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Actualizar monto recaudado y verificar si alcanzó la meta */
    public function actualizarMonto(int $id): array {
        $stmt = $this->db->prepare("
            UPDATE casos_sociales
            SET monto_recaudado = (
                SELECT COALESCE(SUM(monto), 0) FROM donaciones
                WHERE caso_social_id = ? AND estado = 'verificado'
            )
            WHERE id = ?
        ");
        $stmt->execute([$id, $id]);

        $caso = $this->getById($id);
        $metaAlcanzada = $caso && $caso['monto_recaudado'] >= $caso['meta_monto'];

        if ($metaAlcanzada && $caso['estado'] === 'aprobado') {
            $this->db->prepare("UPDATE casos_sociales SET estado='cerrado' WHERE id=?")->execute([$id]);
        }

        return ['caso' => $caso, 'meta_alcanzada' => $metaAlcanzada];
    }

    /** Resumen global para dashboard */
    public function getResumenGlobal(): array {
        $stmt = $this->db->query("
            SELECT
                COUNT(*) AS total_casos,
                SUM(meta_monto) AS total_meta,
                SUM(monto_recaudado) AS total_recaudado,
                SUM(CASE WHEN estado='completado' THEN 1 ELSE 0 END) AS completados,
                SUM(CASE WHEN estado='aprobado'   THEN 1 ELSE 0 END) AS activos,
                SUM(CASE WHEN estado='cerrado'    THEN 1 ELSE 0 END) AS cerrados
            FROM casos_sociales
            WHERE estado IN ('aprobado','completado','cerrado')
        ");
        return $stmt->fetch();
    }

    /** Progreso por mes (últimos 6 meses) */
    public function getProgresoPorMes(): array {
        $stmt = $this->db->query("
            SELECT DATE_FORMAT(fecha_donacion, '%Y-%m') AS mes,
                   SUM(monto) AS total
            FROM donaciones
            WHERE estado='verificado'
              AND fecha_donacion >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mes
            ORDER BY mes ASC
        ");
        return $stmt->fetchAll();
    }
}

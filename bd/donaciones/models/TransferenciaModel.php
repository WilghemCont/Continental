<?php
// ============================================================
// models/TransferenciaModel.php
// ============================================================
class TransferenciaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Todas las transferencias */
    public function getTodas(): array {
        $stmt = $this->db->query("
            SELECT t.*, cs.titulo AS caso_titulo, cs.meta_monto,
                   cs.monto_recaudado, o.nombre AS ong_nombre, o.email AS ong_email
            FROM transferencias t
            JOIN casos_sociales cs ON t.caso_social_id = cs.id
            JOIN ongs o ON t.ong_id = o.id
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /** Una transferencia por ID */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT t.*, cs.titulo AS caso_titulo, cs.meta_monto,
                   cs.monto_recaudado, o.nombre AS ong_nombre, o.email AS ong_email
            FROM transferencias t
            JOIN casos_sociales cs ON t.caso_social_id = cs.id
            JOIN ongs o ON t.ong_id = o.id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Iniciar transferencia para un caso cerrado */
    public function iniciar(int $casoId): int {
        $stmt = $this->db->prepare("
            SELECT cs.id, cs.ong_id, cs.monto_recaudado
            FROM casos_sociales cs WHERE cs.id = ? AND cs.estado IN ('cerrado','aprobado')
        ");
        $stmt->execute([$casoId]);
        $caso = $stmt->fetch();
        if (!$caso) throw new Exception('Caso no válido para transferencia.');

        // Verificar que no exista ya una transferencia pendiente
        $dup = $this->db->prepare("SELECT id FROM transferencias WHERE caso_social_id=? AND estado NOT IN ('rechazado')");
        $dup->execute([$casoId]);
        if ($dup->fetch()) throw new Exception('Ya existe una transferencia para este caso.');

        $stmt = $this->db->prepare("
            INSERT INTO transferencias (caso_social_id, ong_id, monto, estado)
            VALUES (?, ?, ?, 'pendiente')
        ");
        $stmt->execute([$caso['id'], $caso['ong_id'], $caso['monto_recaudado']]);
        return (int)$this->db->lastInsertId();
    }

    /** Subir documento de transferencia */
    public function subirDocumento(int $id, string $rutaArchivo, string $notas): bool {
        $stmt = $this->db->prepare("
            UPDATE transferencias
            SET documento=?, notas=?, estado='en_proceso', fecha_transferencia=NOW()
            WHERE id=?
        ");
        return $stmt->execute([$rutaArchivo, $notas, $id]);
    }

    /** Confirmar recepción por la ONG */
    public function confirmar(int $id, string $confirmadoPor): bool {
        $stmt = $this->db->prepare("
            UPDATE transferencias
            SET estado='completado', fecha_confirmacion=NOW(), confirmado_por=?
            WHERE id=?
        ");
        $ok = $stmt->execute([$confirmadoPor, $id]);

        if ($ok) {
            // Cerrar el caso social como completado
            $t = $this->getById($id);
            if ($t) {
                $this->db->prepare("UPDATE casos_sociales SET estado='completado' WHERE id=?")->execute([$t['caso_social_id']]);
            }
        }
        return $ok;
    }

    /** KPIs de transferencias */
    public function getKPIs(): array {
        $stmt = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estado='completado' THEN 1 ELSE 0 END) AS completadas,
                SUM(CASE WHEN estado='en_proceso' THEN 1 ELSE 0 END) AS en_proceso,
                SUM(CASE WHEN estado='pendiente'  THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estado='completado' THEN monto ELSE 0 END) AS monto_transferido,
                AVG(CASE WHEN estado='completado'
                    THEN TIMESTAMPDIFF(HOUR, fecha_transferencia, fecha_confirmacion)
                    ELSE NULL END) AS horas_promedio
            FROM transferencias
        ");
        return $stmt->fetch();
    }

    /** Casos cerrados sin transferencia iniciada */
    public function getCasosSinTransferencia(): array {
        $stmt = $this->db->query("
            SELECT cs.id, cs.titulo, cs.monto_recaudado, cs.meta_monto,
                   o.nombre AS ong_nombre
            FROM casos_sociales cs
            JOIN ongs o ON cs.ong_id = o.id
            WHERE cs.estado = 'cerrado'
              AND cs.id NOT IN (
                  SELECT caso_social_id FROM transferencias WHERE estado NOT IN ('rechazado')
              )
        ");
        return $stmt->fetchAll();
    }
}

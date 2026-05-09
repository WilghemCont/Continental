<?php
// ============================================================
// models/NotificacionModel.php
// ============================================================
class NotificacionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getTodas(int $limite = 20): array {
        $stmt = $this->db->prepare("
            SELECT n.*, o.nombre AS ong_nombre, cs.titulo AS caso_titulo
            FROM notificaciones n
            JOIN ongs o ON n.ong_id = o.id
            LEFT JOIN casos_sociales cs ON n.caso_social_id = cs.id
            ORDER BY n.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }

    public function countNoLeidas(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM notificaciones WHERE leida=0")->fetchColumn();
    }

    public function crear(int $ongId, ?int $casoId, string $tipo, string $titulo, string $mensaje): int {
        $stmt = $this->db->prepare("
            INSERT INTO notificaciones (ong_id, caso_social_id, tipo, titulo, mensaje)
            VALUES (?,?,?,?,?)
        ");
        $stmt->execute([$ongId, $casoId, $tipo, $titulo, $mensaje]);
        return (int)$this->db->lastInsertId();
    }

    public function marcarLeida(int $id): void {
        $this->db->prepare("UPDATE notificaciones SET leida=1 WHERE id=?")->execute([$id]);
    }

    public function marcarTodasLeidas(): void {
        $this->db->query("UPDATE notificaciones SET leida=1");
    }
}

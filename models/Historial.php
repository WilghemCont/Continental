<?php
/**
 * app/models/Historial.php
 * Model — Registro de auditoría de cambios en casos
 */

require_once __DIR__ . "/../config/conexion.php";

class Historial
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function registrar(
        int    $casoId,
        string $tipo,
        string $anterior,
        string $nuevo,
        string $comentario,
        string $usuario
    ): void {
        $stmt = $this->db->prepare("
            INSERT INTO historial_casos
                (caso_id, tipo_cambio, valor_anterior, valor_nuevo, comentario, usuario, fecha)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$casoId, $tipo, $anterior, $nuevo, $comentario, $usuario]);
    }

    public function obtenerPorCaso(int $casoId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM historial_casos
            WHERE  caso_id = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$casoId]);
        return $stmt->fetchAll();
    }
}

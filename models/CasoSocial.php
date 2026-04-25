<?php
/**
 * models/CasoSocial.php
 */
require_once("../config/conexion.php");

class CasoSocial extends Conectar // Heredamos de Conectar para usar la base de datos
{
    private $db;

    public function __construct()
    {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }

    public function listar(array $filtros = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filtros['clasificacion'])) {
            $where[]  = 'clasificacion = ?';
            $params[] = $filtros['clasificacion'];
        }
        if (!empty($filtros['estado_evaluacion'])) {
            $where[]  = 'estado_evaluacion = ?';
            $params[] = $filtros['estado_evaluacion'];
        }
        if (!empty($filtros['estado_proceso'])) {
            $where[]  = 'estado_proceso = ?';
            $params[] = $filtros['estado_proceso'];
        }
        if (!empty($filtros['buscar'])) {
            $where[]  = '(titulo_caso LIKE ? OR nombre_ong LIKE ? OR nombre_beneficiario LIKE ?)';
            $like     = '%' . $filtros['buscar'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql = "SELECT * FROM casos_sociales";
        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY fecha_registro DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function estadisticas(): array
    {
        // Usamos FETCH_ASSOC para que el JS reciba los nombres de columnas correctos
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado_evaluacion = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN estado_evaluacion = 'aprobado' THEN 1 ELSE 0 END) AS aprobados,
                    SUM(CASE WHEN estado_evaluacion = 'observado' THEN 1 ELSE 0 END) AS observados,
                    SUM(CASE WHEN estado_evaluacion = 'rechazado' THEN 1 ELSE 0 END) AS rechazados,
                    SUM(CASE WHEN publicado = 1 THEN 1 ELSE 0 END) AS publicados
                FROM casos_sociales";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM casos_sociales WHERE id = ?");
        $stmt->execute([$id]);
        $caso = $stmt->fetch(PDO::FETCH_ASSOC);
        return $caso ?: null;
    }

    public function buscarDuplicados(string $dni, string $clasificacion, int $excluirId = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT id, titulo_caso, fecha_registro
            FROM   casos_sociales
            WHERE  dni_beneficiario  = ?
              AND  clasificacion     = ?
              AND  estado_evaluacion != 'rechazado'
              AND  id                != ?
            ORDER BY fecha_registro DESC
            LIMIT 5
        ");
        $stmt->execute([$dni, $clasificacion, $excluirId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
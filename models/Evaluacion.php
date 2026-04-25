<?php
/**
 * app/models/Evaluacion.php
 * Model — Lógica de evaluación, publicación y proceso
 */
require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../config/constants.php";
require_once __DIR__ . "/Historial.php"; // Está en la misma carpeta

class Evaluacion
{
    private PDO      $db;
    private Historial $historial;

    public function __construct()
    {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion(); 
        $this->historial = new Historial($this->db);
    }

    // -------------------------------------------------------
    // Cambiar estado de evaluación: pendiente|aprobado|observado|rechazado
        // -------------------------------------------------------
    public function cambiarEstado(int $casoId, string $nuevoEstado, string $comentario, string $usuario): void
    {
        if (!array_key_exists($nuevoEstado, ESTADOS_EVALUACION)) {
            throw new InvalidArgumentException("Estado de evaluación no válido: {$nuevoEstado}");
        }

        // 🔹 1. LEER ANTES (fuera de la transacción)
        $stmt = $this->db->prepare("SELECT estado_evaluacion FROM casos_sociales WHERE id = ?");
        $stmt->execute([$casoId]);
        $anterior = $stmt->fetchColumn();

        // 🔹 2. TRANSACCIÓN SOLO PARA ESCRITURA
        $this->db->beginTransaction();

        try {
            // UPDATE principal
            $this->db->prepare("
                UPDATE casos_sociales
                SET estado_evaluacion     = ?,
                    comentario_evaluacion = ?,
                    fecha_evaluacion      = NOW()
                WHERE id = ?
            ")->execute([$nuevoEstado, $comentario, $casoId]);

            // Historial
            $this->historial->registrar(
                $casoId,
                'evaluacion',
                $anterior,
                $nuevoEstado,
                $comentario,
                $usuario
            );

            $this->db->commit();

        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // -------------------------------------------------------
    // Publicar o retirar caso de la web pública
    // -------------------------------------------------------
    public function publicar(int $casoId, bool $publicar, string $usuario): void
    {
        $stmt = $this->db->prepare("SELECT estado_evaluacion, publicado FROM casos_sociales WHERE id = ?");
        $stmt->execute([$casoId]);
        $caso = $stmt->fetch();

        if ($publicar && $caso['estado_evaluacion'] !== 'aprobado') {
            throw new LogicException("Solo se pueden publicar casos con estado 'aprobado'.");
        }

        $this->db->beginTransaction();
        try {
            $this->db->prepare("
                UPDATE casos_sociales
                SET    publicado         = ?,
                       fecha_publicacion = IF(?, NOW(), NULL)
                WHERE  id = ?
            ")->execute([(int)$publicar, (int)$publicar, $casoId]);

            $anterior = $caso['publicado'] ? 'publicado' : 'no_publicado';
            $nuevo    = $publicar          ? 'publicado' : 'no_publicado';
            $this->historial->registrar($casoId, 'publicacion', $anterior, $nuevo, '', $usuario);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // -------------------------------------------------------
    // Cambiar estado de proceso: en_proceso|cancelado|finalizado
    // -------------------------------------------------------
    public function cambiarProceso(int $casoId, string $nuevoEstado, string $comentario, string $usuario): void
    {
        if (!array_key_exists($nuevoEstado, ESTADOS_PROCESO)) {
            throw new InvalidArgumentException("Estado de proceso no válido: {$nuevoEstado}");
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("SELECT estado_proceso FROM casos_sociales WHERE id = ?");
            $stmt->execute([$casoId]);
            $anterior = $stmt->fetchColumn();

            $this->db->prepare("
                UPDATE casos_sociales
                SET    estado_proceso   = ?,
                       fecha_ult_cambio = NOW()
                WHERE  id = ?
            ")->execute([$nuevoEstado, $casoId]);

            $this->historial->registrar($casoId, 'proceso', $anterior, $nuevoEstado, $comentario, $usuario);
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}

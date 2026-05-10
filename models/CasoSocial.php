<?php
/**
 * models/CasoSocial.php
 */
require_once("../config/conexion.php");

class CasoSocial extends Conectar 
{
    private $db;

    public function __construct()
    {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }

    // ===============================================
    // 🔍 CONSULTAS
    // ===============================================

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM casos_sociales WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado_evaluacion = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN estado_evaluacion = 'aprobado' THEN 1 ELSE 0 END) AS aprobados,
                    SUM(CASE WHEN estado_evaluacion = 'observado' THEN 1 ELSE 0 END) AS observados,
                    SUM(CASE WHEN estado_evaluacion = 'rechazado' THEN 1 ELSE 0 END) AS rechazados,
                    SUM(CASE WHEN estado_evaluacion = 'publicado' THEN 1 ELSE 0 END) AS publicados,
                    SUM(CASE WHEN estado_evaluacion = 'cerrado' THEN 1 ELSE 0 END) AS cerrados
                FROM casos_sociales";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================================
    // 🔄 GESTIÓN DE ESTADOS E HISTORIAL
    // ===============================================

    public function cambiarEstado($id, $nuevoEstado, $usuarioNombre = null)
    {
        $caso = $this->obtenerPorId($id);
        if (!$caso) throw new Exception("Caso no encontrado");

        $estadoActual = $caso['estado_evaluacion'];

        // Validar si el cambio es permitido según el flujo de negocio
        if (!$this->validarTransicion($estadoActual, $nuevoEstado)) {
            throw new Exception("Transición de estado de '$estadoActual' a '$nuevoEstado' no permitida");
        }

        // Actualizar el estado en la tabla principal
        $sql = "UPDATE casos_sociales SET estado_evaluacion = ?, fecha_evaluacion = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nuevoEstado, $id]);

        // Registrar automáticamente en historial_casos
        $this->guardarHistorial($id, $estadoActual, $nuevoEstado, $usuarioNombre);

        return true;
    }

    private function validarTransicion($actual, $nuevo)
    {
        $flujo = [
            'pendiente' => ['observado', 'aprobado', 'rechazado'],
            'observado' => ['pendiente', 'aprobado', 'rechazado'],
            'aprobado'  => ['pendiente'], // Permitir revertir si hubo error
            'rechazado' => [] // Estado final
        ];
        return in_array($nuevo, $flujo[$actual] ?? []);
    }

    private function guardarHistorial($id_caso, $anterior, $nuevo, $usuario)
    {
        $sql = "INSERT INTO historial_casos (caso_id, tipo_cambio, valor_anterior, valor_nuevo, usuario, comentario, fecha)
                VALUES (?, 'evaluacion', ?, ?, ?, 'Cambio de estado vía sistema de evaluación', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_caso, $anterior, $nuevo, $usuario]);
    }

    // ===============================================
    // 🛠 OPERACIONES DE DATOS
    // ===============================================

    public function insertar($data)
        {
            // =====================================
            // CALCULAR COMISIÓN
            // =====================================

            $monto = (float)$data['monto_requerido'];

            // Comisión según monto
            if ($monto < 10000) {
                $porcentaje = 3;
            } else {
                $porcentaje = 7;
            }

            // Calcular comisión
            $comision = $monto * ($porcentaje / 100);

            // Meta final pública
            $metaTotal = $monto + $comision;

            // =====================================
            // INSERT
            // =====================================

            $sql = "INSERT INTO casos_sociales (
                nombre_ong,
                ruc_ong,
                email_ong,
                contacto_ong,
                titulo_caso,
                clasificacion,
                monto_requerido,
                porcentaje_comision,
                monto_comision,
                meta_total,
                descripcion,
                nombre_beneficiario,
                dni_beneficiario,
                edad_beneficiario,
                ubicacion,
                documento_solicitud,
                foto_beneficiario,
                estado_evaluacion,
                estado_proceso,
                fecha_registro
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                'pendiente',
                'sin_proceso',
                NOW()
            )";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                $data['nombre_ong'],
                $data['ruc_ong'],
                $data['email_ong'],
                $data['contacto_ong'],
                $data['titulo_caso'],
                $data['clasificacion'],

                // MONTOS
                $monto,
                $porcentaje,
                $comision,
                $metaTotal,

                // RESTO
                $data['descripcion'],
                $data['nombre_beneficiario'],
                $data['dni_beneficiario'],
                $data['edad_beneficiario'],
                $data['ubicacion'],
                $data['documento'],
                $data['foto']
            ]);
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

    // ===============================================
    // 🔒 TRANSACCIONES (Proxy para el Controlador)
    // ===============================================

    public function beginTransaction() { $this->db->beginTransaction(); }
    public function commit() { $this->db->commit(); }
    public function rollBack() { if ($this->db->inTransaction()) $this->db->rollBack(); }


    public function obtenerPublicados() {
        $sql = "SELECT 
                    id, 
                    titulo_publico, 
                    descripcion_publica, 
                    foto_beneficiario, monto_recaudado, monto_requerido
                FROM casos_sociales 
                WHERE estado_evaluacion = 'publicado'
                ORDER BY fecha_publicacion DESC";

        $stmt = $this->db->prepare($sql);   
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivos(): array {

         $sql = "SELECT cs.*, o.nombre AS ong_nombre, o.email AS ong_email,
                   ROUND((cs.monto_recaudado / cs.monto_requerido) * 100, 1) AS porcentaje
            FROM casos_sociales cs
            JOIN ongs o ON cs.ong_id = o.id
            WHERE cs.estado_evaluacion IN ('aprobado','publicado','cerrado')
            ORDER BY cs.fecha_registro DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function cerrarCaso($id, $documento)
    {
        // Si el documento llega vacío o como null, se guarda explícitamente como NULL en la BD
        $documentoFinal = !empty($documento) ? $documento : null;

        $sql = "UPDATE casos_sociales
                SET
                    estado_proceso = 'finalizado',
                    estado_evaluacion = 'cerrado',
                    documento_cierre = ?,
                    checklist_cierre_completo = 1,
                    fecha_cierre = NOW()
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $documentoFinal,
            $id
        ]);
    }
}
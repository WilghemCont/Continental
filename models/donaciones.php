<?php
// models/donaciones.php
require_once __DIR__ . "/../config/conexion.php";
 
class DonacionModel extends Conectar {
    private $db;
 
    public function __construct() {
        $this->db = parent::Conexion();
    }
 
    // ════════════════════════════════════════════════════════════════════════
    // MÉTODOS ORIGINALES (donaciones por caso social / MercadoPago)
    // ════════════════════════════════════════════════════════════════════════
 
    public function obtenerTotalesPorMetodo() {
        $sql  = "SELECT metodo, SUM(monto) as total FROM donaciones GROUP BY metodo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function obtenerSumaTotal() {
        $sql  = "SELECT SUM(monto) as total_general FROM donaciones";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_general'] ?? 0;
    }
 
    public function obtenerDonacionesPorMes() {
        $sql  = "SELECT DATE_FORMAT(fecha,'%Y-%m') as mes, SUM(monto) as total
                 FROM donaciones
                 GROUP BY mes
                 ORDER BY mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function obtenerTopDonantes() {
        $sql  = "SELECT nombre, SUM(monto) as total
                 FROM donaciones
                 GROUP BY nombre
                 ORDER BY total DESC
                 LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function insertar(array $data) {
        $sql  = "INSERT INTO donaciones (nombre, email, monto, metodo, mensaje, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
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
        $sql  = "UPDATE casos_sociales SET monto_recaudado = COALESCE(monto_recaudado, 0) + ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$monto, $casoId]);
    }
 
    public function getKPIs(): array {
        $sql  = "SELECT
                     COUNT(*) AS total_donaciones,
                     IFNULL(SUM(monto), 0) AS total_monto,
                     IFNULL(AVG(monto), 0) AS promedio_monto
                 FROM donaciones";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
 
    public function obtenerPorUsuario(int $idUsuario): array {
        $sql  = "SELECT d.*, cs.titulo_publico, cs.nombre_beneficiario
                 FROM donaciones d
                 LEFT JOIN casos_sociales cs ON d.id = cs.id
                 WHERE d.idusuario = ?
                 ORDER BY d.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    /**
     * Calcula comisiones por donante aplicando la regla:
     * 3% para montos <= 10,000 | 5% para montos > 10,000
     */
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
 
    public function obtenerPorId(int $id): ?array {
        $sql  = "SELECT d.*, cs.titulo_publico, cs.nombre_beneficiario
                 FROM donaciones d
                 LEFT JOIN casos_sociales cs ON d.id = cs.id
                 WHERE d.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
 
    // ════════════════════════════════════════════════════════════════════════
    // NUEVOS MÉTODOS (panel financiero + patrocinios)
    // ════════════════════════════════════════════════════════════════════════
 
    /**
     * KPIs ampliados para el panel financiero de administración.
     * Combina datos de la tabla donaciones con la tabla ingresos existente.
     */
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
 
    /**
     * Lista completa de donaciones con nombre del donador
     * (tabla donadores si existe, o nombre directo de la tabla donaciones).
     */
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
 
    /**
     * Obtiene todos los donadores registrados (tabla donadores).
     * Devuelve array vacío si la tabla no existe aún.
     */
    public function obtenerDonadores(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM donadores ORDER BY nombre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
 
    /**
     * Crea o reutiliza un donador por email/documento.
     */
    public function crearDonador(array $d): int {
        try {
            // Buscar por documento si viene
            if (!empty($d['documento'])) {
                $stmt = $this->db->prepare("SELECT id FROM donadores WHERE documento = ? LIMIT 1");
                $stmt->execute([$d['documento']]);
                $existente = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existente) return (int) $existente['id'];
            }
 
            $stmt = $this->db->prepare("
                INSERT INTO donadores (nombre, email, documento, telefono, tipo)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    nombre    = VALUES(nombre),
                    documento = VALUES(documento),
                    telefono  = VALUES(telefono)
            ");
            $stmt->execute([
                $d['nombre'],
                $d['email'],
                $d['documento'] ?? '',
                $d['telefono']  ?? '',
                $d['tipo']      ?? 'persona'
            ]);
 
            $newId = (int) $this->db->lastInsertId();
            if ($newId > 0) return $newId;
 
            // Si el INSERT hizo UPDATE (ON DUPLICATE KEY) lastInsertId puede ser 0
            $stmt2 = $this->db->prepare("SELECT id FROM donadores WHERE email = ? LIMIT 1");
            $stmt2->execute([$d['email']]);
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
            return $row ? (int) $row['id'] : 0;
 
        } catch (Exception $e) {
            // Tabla no existe; registrar sólo en ingresos como empresa
            return 0;
        }
    }
 
    /**
     * Registra un patrocinio en la tabla ingresos existente
     * y opcionalmente en las tablas nuevas (patrocinios / ingresos_plataforma)
     * si ya fueron migradas.
     */
    public function registrarPatrocinio(array $data): int {
        $this->db->beginTransaction();
 
        try {
            $p = $data['patrocinio'] ?? [];
 
            // Insertar en tabla ingresos (existente en Continental)
            $subtipoMap = ['efectivo' => 'Económico', 'bienes' => 'En especie', 'servicios' => 'Servicios'];
            $subtipo    = $subtipoMap[$p['tipo_aporte'] ?? 'efectivo'] ?? 'Económico';
 
            $empresa = '';
            if (!empty($data['donador_id'])) {
                $stmt = $this->db->prepare("SELECT nombre FROM donadores WHERE id = ? LIMIT 1");
                $stmt->execute([$data['donador_id']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $empresa = $row['nombre'] ?? '';
            }
 
            $sqlIngreso = "INSERT INTO ingresos
                               (tipo, subtipo, empresa, descripcion, monto_base, monto_final, porcentaje, fecha)
                           VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())";
            $stmtI = $this->db->prepare($sqlIngreso);
            $stmtI->execute([
                'Patrocinio',
                $subtipo,
                $empresa,
                $p['descripcion'] ?? $data['notas'] ?? '',
                $data['monto_total'],
                $data['monto_neto'],
                null
            ]);
 
            $ingresoId = (int) $this->db->lastInsertId();
 
            // Insertar en tabla patrocinios si existe
            try {
                $sqlP = "INSERT INTO patrocinios
                             (donacion_id, tipo_aporte, descripcion, valor_estimado, archivo_evidencia)
                         VALUES (?, ?, ?, ?, ?)";
                $this->db->prepare($sqlP)->execute([
                    $ingresoId,
                    $p['tipo_aporte']    ?? 'efectivo',
                    $p['descripcion']    ?? '',
                    $p['valor_estimado'] ?? $data['monto_total'],
                    $p['archivo']        ?? null
                ]);
            } catch (Exception $e) {
                // Tabla patrocinios no existe aún; no bloquear la operación
            }
 
            $this->db->commit();
            return $ingresoId;
 
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
 
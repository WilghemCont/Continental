<?php
/**
 * models/BeneficiarioModel.php
 * Modelo para la página del beneficiario en SocialFunding.
 * Proporciona acceso de solo lectura a los datos del caso
 * y operaciones para actualizaciones y testimonios.
 */
require_once("../config/conexion.php");

class BeneficiarioModel extends Conectar
{
    private $db;

    public function __construct()
    {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }

    // ──────────────────────────────────────────
    // DATOS DEL CASO VINCULADO AL BENEFICIARIO
    // ──────────────────────────────────────────

    /**
     * Obtiene el caso social vinculado al usuario beneficiario.
     *
     * @param int $idusuario  ID del usuario autenticado
     * @return array|false    Datos completos del caso o false si no existe
     */
    public function obtenerCasoPorBeneficiario(int $idusuario)
    {
        $sql = "SELECT * FROM casos_sociales WHERE beneficiario_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idusuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────
    // ACTUALIZACIONES DEL CASO
    // ──────────────────────────────────────────

    /**
     * Devuelve todas las actualizaciones del caso, ordenadas del más reciente.
     *
     * @param int $caso_id
     * @return array
     */
    public function obtenerActualizaciones(int $caso_id): array
    {
        $sql = "SELECT * FROM actualizaciones_caso
                WHERE caso_id = ?
                ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$caso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────
    // HISTORIAL DE CAMBIOS
    // ──────────────────────────────────────────

    /**
     * Devuelve el historial público de cambios del caso.
     *
     * @param int $caso_id
     * @return array
     */
    public function obtenerHistorial(int $caso_id): array
    {
        $sql = "SELECT tipo_cambio, valor_anterior, valor_nuevo, comentario, fecha
                FROM historial_casos
                WHERE caso_id = ?
                ORDER BY fecha DESC
                LIMIT 15";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$caso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────
    // TESTIMONIO DEL BENEFICIARIO
    // ──────────────────────────────────────────

    /**
     * Obtiene el testimonio del beneficiario para un caso.
     *
     * @param int $caso_id
     * @return array|false
     */
    public function obtenerTestimonio(int $caso_id)
    {
        $sql = "SELECT * FROM testimonio_beneficiario WHERE caso_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$caso_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda o actualiza el testimonio del beneficiario.
     * Usa INSERT ... ON DUPLICATE KEY para idempotencia.
     *
     * @param int    $caso_id
     * @param string $contenido
     * @return bool
     */
    public function guardarTestimonio(int $caso_id, string $contenido): bool
    {
        // Verificar si ya existe
        $existe = $this->obtenerTestimonio($caso_id);

        if ($existe) {
            $sql  = "UPDATE testimonio_beneficiario SET contenido = ?, fecha = NOW() WHERE caso_id = ?";
            $args = [trim($contenido), $caso_id];
        } else {
            $sql  = "INSERT INTO testimonio_beneficiario (caso_id, contenido) VALUES (?, ?)";
            $args = [$caso_id, trim($contenido)];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($args);
    }

    /**
     * Obtiene un listado de testimonios aleatorios de beneficiarios.
     *
     * @param int $limite
     * @return array
     */
    public function obtenerTestimoniosAleatorios(int $limite = 10): array
    {
        $sql = "SELECT tb.contenido,
                       tb.fecha,
                       cs.titulo_caso,
                       cs.nombre_beneficiario
                FROM testimonio_beneficiario tb
                INNER JOIN casos_sociales cs ON tb.caso_id = cs.id
                ORDER BY RAND()
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ──────────────────────────────────────────
    // DATOS DEL PERFIL DEL BENEFICIARIO
    // ──────────────────────────────────────────

    /**
     * Devuelve los datos básicos del usuario beneficiario.
     *
     * @param int $idusuario
     * @return array|false
     */
    public function obtenerDatosBeneficiario(int $idusuario)
    {
        $sql = "SELECT u.*, l.correo AS correo_login
                FROM usuario u
                INNER JOIN login l ON u.idlogin = l.idlogin
                WHERE u.idusuario = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idusuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

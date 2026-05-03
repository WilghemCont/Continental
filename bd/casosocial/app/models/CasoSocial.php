<?php

class CasoSocial {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ===============================
    // 📊 DASHBOARD
    // ===============================
    public function obtenerEstadisticas() {

        $sql = "SELECT estado, COUNT(*) as total 
                FROM casos_sociales 
                GROUP BY estado";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $result = [
            'pendiente' => 0,
            'observado' => 0,
            'aprobado'  => 0,
            'publicado' => 0,
            'cerrado'   => 0
        ];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['estado']] = $row['total'];
        }

        return $result;
    }

    // ===============================
    // 📋 LISTAR CASOS (CON FILTROS)
    // ===============================
    public function listar($estado = null, $busqueda = null) {

        $sql = "SELECT c.*, o.nombre as ong_nombre
                FROM casos_sociales c
                INNER JOIN ongs o ON c.id_ong = o.id
                WHERE 1=1";

        $params = [];

        if (!empty($estado)) {
            $sql .= " AND c.estado = :estado";
            $params[':estado'] = $estado;
        }

        if (!empty($busqueda)) {
            $sql .= " AND (c.titulo_caso LIKE :busqueda 
                      OR c.nombre_beneficiario LIKE :busqueda)";
            $params[':busqueda'] = "%" . $busqueda . "%";
        }

        $sql .= " ORDER BY c.fecha_registro DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===============================
    // 🔍 OBTENER CASO POR ID
    // ===============================
    public function obtener($id) {

        $sql = "SELECT c.*, o.nombre as ong_nombre
                FROM casos_sociales c
                INNER JOIN ongs o ON c.id_ong = o.id
                WHERE c.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // 🔄 CAMBIAR ESTADO (CON VALIDACIÓN)
    // ===============================
    public function cambiarEstado($id, $nuevoEstado, $usuario_id = null) {

        // Obtener estado actual
        $caso = $this->obtener($id);

        if (!$caso) {
            throw new Exception("Caso no encontrado");
        }

        $estadoActual = $caso['estado'];

        // 🔒 VALIDACIÓN DE FLUJO
        if (!$this->validarTransicion($estadoActual, $nuevoEstado)) {
            throw new Exception("Transición de estado no permitida");
        }

        // Actualizar estado
        $sql = "UPDATE casos_sociales 
                SET estado = :estado 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':estado' => $nuevoEstado,
            ':id'     => $id
        ]);

        // Guardar historial
        $this->guardarHistorial($id, $estadoActual, $nuevoEstado, $usuario_id);

        return true;
    }

    // ===============================
    // 🔒 VALIDAR TRANSICIÓN
    // ===============================
    private function validarTransicion($actual, $nuevo) {

        $flujo = [
            'pendiente' => ['observado', 'aprobado'],
            'observado' => ['pendiente', 'aprobado'],
            'aprobado'  => ['publicado'],
            'publicado' => ['cerrado'],
            'cerrado'   => []
        ];

        return in_array($nuevo, $flujo[$actual] ?? []);
    }

    // ===============================
    // 🧾 HISTORIAL
    // ===============================
    private function guardarHistorial($id_caso, $anterior, $nuevo, $usuario_id) {

        $sql = "INSERT INTO historial_estados 
                (id_caso, estado_anterior, estado_nuevo, usuario_id)
                VALUES (:id_caso, :anterior, :nuevo, :usuario)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_caso'  => $id_caso,
            ':anterior' => $anterior,
            ':nuevo'    => $nuevo,
            ':usuario'  => $usuario_id
        ]);
    }

    // ===============================
    // ➕ CREAR CASO
    // ===============================
    public function crear($data) {

        $sql = "INSERT INTO casos_sociales (
                    id_ong, titulo_caso, clasificacion, descripcion,
                    monto_requerido, ubicacion,
                    nombre_beneficiario, dni_beneficiario, edad_beneficiario
                ) VALUES (
                    :id_ong, :titulo, :clasificacion, :descripcion,
                    :monto, :ubicacion,
                    :beneficiario, :dni, :edad
                )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id_ong'       => $data['id_ong'],
            ':titulo'       => $data['titulo'],
            ':clasificacion'=> $data['clasificacion'],
            ':descripcion'  => $data['descripcion'],
            ':monto'        => $data['monto'],
            ':ubicacion'    => $data['ubicacion'],
            ':beneficiario' => $data['beneficiario'],
            ':dni'          => $data['dni'],
            ':edad'         => $data['edad']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function beginTransaction() {
    $this->pdo->beginTransaction();
    }

    public function commit() {
        $this->pdo->commit();
    }

    public function rollBack() {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    public function getPDO() {
    return $this->pdo;
    }

}
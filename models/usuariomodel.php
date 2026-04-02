<?php
require_once("../config/conexion.php");
class Usuario extends Conectar {

    // FUNCIÓN DE REGISTRO INTEGRAL
    public function registrar_usuario($datos) {
        $db = parent::conexion();
        parent::set_names();

        try {
            $db->beginTransaction();

            // 1. Insertar en LOGIN (Usuario = Documento)
            $sqlLogin = "INSERT INTO login (usuario, contrasena, correo, estado) VALUES (?, ?, ?, 1)";
            $passHash = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
            $stmtLogin = $db->prepare($sqlLogin);
            $stmtLogin->execute([$datos['documento'], $passHash, $datos['correo']]);
            $idLogin = $db->lastInsertId();

            // 2. Insertar en USUARIO (Siempre se registra aquí)
            $sqlUser = "INSERT INTO usuario (idlogin, tipo_documento, documento, nombres, apepat, apemat, fechanac, correo, direccion, estado) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
            $stmtUser = $db->prepare($sqlUser);
            $stmtUser->execute([
                $idLogin, $datos['tipo_documento'], $datos['documento'], 
                $datos['nombres'], $datos['apepat'], $datos['apemat'], 
                $datos['fechanac'], $datos['correo'], $datos['direccion']
            ]);
            $idUsuario = $db->lastInsertId();

            // 3. SI ES RUC: Insertar en EMPRESA y relacionar
            if ($datos['tipo_documento'] == 'RUC') {
                $sqlEmp = "INSERT INTO empresa (ruc, razon_social, direccion, estado) VALUES (?, ?, ?, 1)";
                $stmtEmp = $db->prepare($sqlEmp);
                $stmtEmp->execute([$datos['documento'], $datos['nombres'], $datos['direccion']]);
                $idEmpresa = $db->lastInsertId();

                // Relación Usuario-Empresa con rol ADMIN por ser el creador
                $sqlRel = "INSERT INTO usuario_empresa (idusuario, idempresa, rol, estado) VALUES (?, ?, 'ADMIN', 1)";
                $stmtRel = $db->prepare($sqlRel);
                $stmtRel->execute([$idUsuario, $idEmpresa]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error en Registro: " . $e->getMessage());
            return false;
        }
    }

    public function get_paises() {
        $db = parent::conexion();
        $sql = "SELECT * FROM pais";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_departamentos() {
        $db = parent::conexion();
        $sql = "SELECT * FROM departamento";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_provincias($iddepartamento) {
        $db = parent::conexion();
        $sql = "SELECT * FROM provincia WHERE iddepartamento = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$iddepartamento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_distritos($idprovincia) {
        $db = parent::conexion();
        $sql = "SELECT * FROM distrito WHERE idprovincia = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idprovincia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FUNCIÓN DE LOGIN CON ROL
    public function login_acceso($correo, $password) {
        $db = parent::conexion();
        parent::set_names();

        // Buscamos el login y unimos con usuario y usuario_empresa para sacar el ROL
        $sql = "SELECT l.*, u.idusuario, u.tipo_documento, u.nombres, ue.rol 
                FROM login l
                INNER JOIN usuario u ON l.idlogin = u.idlogin
                LEFT JOIN usuario_empresa ue ON u.idusuario = ue.idusuario
                WHERE l.correo = ? AND l.estado = 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$correo]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res && password_verify($password, $res['contrasena'])) {
            return $res; // Retorna todos los datos incluyendo el ROL
        }
        return false;
    }

    // Dentro de la clase Usuario en usuariomodel.php
    public function get_tipos_documento() {
        $db = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tipo_documento WHERE estado = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
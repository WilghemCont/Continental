<?php
require_once __DIR__ . "/../config/conexion.php";
class Donante {
    private $db;

    public function __construct() {
        $conectar = new Conectar();
        $this->db = $conectar->Conexion();
    }

    public function listar() {
        $stmt = $this->db->query("SELECT * FROM usuario WHERE tipo='DONANTE' ORDER BY idusuario DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM donantes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($nombre, $email) {
        $stmt = $this->db->prepare("INSERT INTO donantes (nombre, email) VALUES (?, ?)");
        return $stmt->execute([$nombre, $email]);
    }

    public function actualizar($id, $nombre, $email) {
        $stmt = $this->db->prepare("UPDATE donantes SET nombre = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nombre, $email, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM donantes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
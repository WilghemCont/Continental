<?php
require_once("../config/conexion.php");

class DonacionModel extends Conectar {
    
  public function obtenerTotalesPorMetodo() {
    $db = parent::Conexion();
    // Agrupamos por método de pago (Yape, Plin, Transferencia, etc.)
    $sql = "SELECT metodo, SUM(monto) as total FROM donaciones GROUP BY metodo";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function obtenerSumaTotal() {
    $db = parent::Conexion();
    $sql = "SELECT SUM(monto) as total_general FROM donaciones";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total_general'] ?? 0;
}
}
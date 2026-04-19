<?php
require_once("../config/conexion.php");

class IngresoModel extends Conectar {
    
    public function calcularComision($monto) {
        if ($monto <= 1000) return 0.03;
        if ($monto <= 10000) return 0.05;
        return 0.07;
    }

    public function registrar($data) {
        $db = parent::Conexion();
        parent::set_names();

        $montoFinal = $data['monto'];
        $porcentaje = $data['porcentaje']; // Recibido de la API

        if ($data['tipo'] === 'Comisión por donación' && $porcentaje !== null) {
            $montoFinal = $data['monto'] * $porcentaje;
        } 
        // Patrocinio no económico usa el valor estimado
        elseif ($data['tipo'] === 'Patrocinio' && ($data['subtipo'] ?? '') !== 'Económico') {
            $montoFinal = $data['valor_estimado'] > 0 ? $data['valor_estimado'] : $data['monto'];
        }

        $sql = "INSERT INTO ingresos (tipo, subtipo, empresa, descripcion, monto_base, monto_final, porcentaje, fecha) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['tipo'], 
            $data['subtipo'], 
            $data['empresa'], 
            $data['descripcion'], // Guardado para todos los tipos
            $data['monto'], 
            $montoFinal, 
            $porcentaje, // Se guarda en la DB (ej: 0.03, 0.05)
            $data['fecha']
        ]);
    }

    public function listar($desde = null, $hasta = null) {
        $db = parent::Conexion();
        $sql = "SELECT * FROM ingresos WHERE 1=1";
        $params = [];

        if ($desde && $hasta) {
            $sql .= " AND fecha BETWEEN ? AND ?";
            $params = [$desde, $hasta];
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function eliminar($id) {
        $db = parent::Conexion();
        $sql = "DELETE FROM ingresos WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
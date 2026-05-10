<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar que el usuario haya iniciado sesión
if (!isset($_SESSION['idlogin'])) {
    header("Location: ../view/login.php");
    exit;
}

// 2. Importar tu archivo de conexión real
require_once __DIR__ . '/../config/conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura de datos sanitizados
    $id_usuario = intval($_SESSION['idlogin']);
    $id_caso    = isset($_POST['idcaso']) ? intval($_POST['idcaso']) : 0;
    $monto      = isset($_POST['monto']) ? floatval($_POST['monto']) : 0.00;
    $mensaje    = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
    
    // Obtener datos del donante de la sesión para mantener consistencia
    $nombre_donante = isset($_SESSION['nombre']) ? trim($_SESSION['nombre'] . ' ' . ($_SESSION['apepat'] ?? '')) : 'Donante Registrado';
    $email_donante  = isset($_SESSION['correo']) ? trim($_SESSION['correo']) : '';

    // Validaciones básicas
    if ($monto <= 0 || $id_caso <= 0) {
        $_SESSION['error'] = "El monto a donar debe ser mayor a S/ 0.00 y pertenecer a un caso válido.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        // 3. Instanciar e iniciar la conexión con tu clase 'Conectar'
        $conectarObj = new Conectar();
        $pdo = $conectarObj->Conexion();
        $conectarObj->set_names(); // Configurar codificación UTF-8

        // Iniciar una transacción para asegurar que si falla el update del caso, no se guarde la donación (o viceversa)
        $pdo->beginTransaction();

        // 4. Insertar el registro en la tabla de 'donaciones' (con las nuevas columnas)
        $sql_donacion = "INSERT INTO donaciones (id_caso, id_usuario, nombre, email, monto, metodo, mensaje, fecha) 
                         VALUES (:id_caso, :id_usuario, :nombre, :email, :monto, 'Directo (Sistema)', :mensaje, NOW())";
        
        $stmt_donacion = $pdo->prepare($sql_donacion);
        $stmt_donacion->execute([
            ':id_caso'    => $id_caso,
            ':id_usuario' => $id_usuario,
            ':nombre'     => $nombre_donante,
            ':email'      => $email_donante,
            ':monto'      => $monto,
            ':mensaje'    => $mensaje
        ]);

        // 5. Actualizar el acumulado 'monto_recaudado' de la tabla 'casos_sociales'
        $sql_update_caso = "UPDATE casos_sociales 
                            SET monto_recaudado = monto_recaudado + :monto, 
                                fecha_ult_cambio = NOW() 
                            WHERE id = :id_caso";
        
        $stmt_update = $pdo->prepare($sql_update_caso);
        $stmt_update->execute([
            ':monto'   => $monto,
            ':id_caso' => $id_caso
        ]);

        // Confirmar la transacción en la base de datos
        $pdo->commit();

        // Guardar mensaje de éxito en sesión y redirigir
        $_SESSION['success'] = "¡Muchas gracias! Tu donación de S/ " . number_format($monto, 2) . " se ha registrado directamente en la base de datos con éxito.";
        header("Location: ../view/estadistica.php"); 
        exit;

    } catch (Exception $e) {
        // Si algo falla, revertimos los cambios en la BD
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        $_SESSION['error'] = "Ocurrió un error al procesar la donación: " . $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
    // Redirigir si se intenta acceder directamente por URL
    header("Location: ../view/home.php");
    exit;
}
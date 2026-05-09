<?php
/**
 * view/demo_beneficiario.php
 * Página de demo: inicia sesión automáticamente como beneficiario
 * y redirige a la página principal del beneficiario.
 * SOLO PARA DEMOSTRACIÓN en entorno de desarrollo.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/conexion.php');
require_once('../models/usuariomodel.php');

$usuario = new Usuario();
$datos   = $usuario->login_acceso('beneficiario@socialfunding.pe', 'admin123');

if ($datos) {
    $_SESSION['idlogin']   = $datos['idlogin'];
    $_SESSION['idusuario'] = $datos['idusuario'];
    $_SESSION['nombre']    = $datos['nombres'];
    $_SESSION['apepat']    = $datos['apepat'];
    $_SESSION['tipo']      = $datos['tipo'];
    header('Location: beneficiario.php');
} else {
    echo '<p style="font-family:sans-serif;padding:2rem;color:red">
        No se pudo autenticar al beneficiario. Verifica que la base de datos esté importada correctamente.
    </p>';
}
exit;

<?php
require_once "../models/donaciones.php";
require_once "../models/CasoSocial.php";

class DonacionController {
    private $model;
    private $casoModel;

    public function __construct() {
        $this->model = new DonacionModel();
        $this->casoModel = new CasoSocial();
    }

    public function crear() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            header("Location: index.php?controller=caso&action=catalogo&msg=id_invalido");
            exit;
        }

        $caso = $this->casoModel->obtenerPorId($id);

        if (!$caso || $caso['estado_evaluacion'] !== 'publicado' || $caso['publicado'] != 1) {
            header("Location: index.php?controller=caso&action=catalogo&msg=caso_no_disponible");
            exit;
        }

        require_once "../view/donar.php";
    }

    public function success() {
        $casoId = isset($_GET['caso_id']) ? (int) $_GET['caso_id'] : 0;
        $monto = isset($_GET['monto']) ? number_format((float)$_GET['monto'], 2) : '0.00';
        $nombre = isset($_GET['nombre']) ? urldecode($_GET['nombre']) : 'Donante';
        $metodo = isset($_GET['metodo']) ? urldecode($_GET['metodo']) : 'Mercado Pago';
        $caso = $casoId ? $this->casoModel->obtenerPorId($casoId) : null;
        require_once "../view/donacion_exito.php";
    }

    public function historial() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $donaciones = $this->model->obtenerPorUsuario($_SESSION['idusuario']);
        require_once "../view/donaciones_historial.php";
    }

    public function certificado() {
        if (!isset($_SESSION['idusuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $idDonacion = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if (!$idDonacion) {
            header("Location: index.php?controller=donacion&action=historial");
            exit;
        }

        $donacion = $this->model->obtenerPorId($idDonacion);
        if (!$donacion || $donacion['idusuario'] != $_SESSION['idusuario']) {
            header("Location: index.php?controller=donacion&action=historial");
            exit;
        }

        require_once "../view/certificado_donacion.php";
    }

    public function historia() {
        $estilo_pagina = 'home';
        require_once "../view/historia_donador.php";
    }
}
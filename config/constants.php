<?php
/**
 * app/config/constants.php
 * Constantes del dominio de negocio
 */

define('CLASIFICACIONES', [
    'salud'          => ['label' => 'Salud',              'icon' => '🏥', 'color' => '#e74c3c'],
    'desastres'      => ['label' => 'Desastres Naturales','icon' => '🌪️', 'color' => '#e67e22'],
    'medio_ambiente' => ['label' => 'Medio Ambiente',     'icon' => '🌱', 'color' => '#27ae60'],
    'educacion'      => ['label' => 'Educación',          'icon' => '📚', 'color' => '#2980b9'],
]);

define('ESTADOS_EVALUACION', [
    'pendiente'  => ['label' => 'Pendiente',  'color' => '#f39c12'],
    'aprobado'   => ['label' => 'Aprobado',   'color' => '#27ae60'],
    'observado'  => ['label' => 'Observado',  'color' => '#8e44ad'],
    'rechazado'  => ['label' => 'Rechazado',  'color' => '#e74c3c'],
    'publicado'  => ['label' => 'Rechazado',  'color' => '#1a0bf5ff'],
]);

define('ESTADOS_PROCESO', [
    'sin_proceso' => ['label' => 'Sin Proceso', 'color' => '#95a5a6'],
    'en_proceso'  => ['label' => 'En Proceso',  'color' => '#3498db'],
    'cancelado'   => ['label' => 'Cancelado',   'color' => '#e74c3c'],
    'finalizado'  => ['label' => 'Finalizado',  'color' => '#27ae60'],
]);

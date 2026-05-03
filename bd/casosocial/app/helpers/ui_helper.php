<?php

function renderEstado($c) {

    // 🔒 Evitar errores si no existen claves
    $estadoEval = $c['estado_evaluacion'] ?? null;
    $estadoPub  = $c['estado_publicacion'] ?? null;
    $estadoCie  = $c['estado_cierre'] ?? null;

    // 🔴 PRIORIDAD: CIERRE
    if ($estadoCie === 'cerrado') {
        return "<span class='badge bg-dark'>Cerrado</span>";
    }

    // 🟢 PUBLICADO
    if ($estadoPub === 'publicado') {
        return "<span class='badge bg-success'>Publicado</span>";
    }

    // 🟡 EVALUACIÓN
    return match($estadoEval) {
        'pendiente' => "<span class='badge bg-warning text-dark'>Pendiente</span>",
        'observado' => "<span class='badge bg-danger'>Observado</span>",
        'aprobado'  => "<span class='badge bg-primary'>Aprobado</span>",
        default     => "<span class='badge bg-secondary'>N/A</span>"
    };
}


function renderAcciones($c) {

    // 🔒 Evitar errores
    $estadoEval = $c['estado_evaluacion'] ?? null;
    $estadoPub  = $c['estado_publicacion'] ?? null;
    $estadoCie  = $c['estado_cierre'] ?? null;

    $id = $c['id'] ?? 0;

    // 🔹 BOTÓN VER (siempre)
    $btn = "<a href='index.php?controller=caso&action=ver&id={$id}' 
                class='btn btn-sm btn-outline-primary'>Ver</a> ";

    // 🔒 CERRADO → SOLO VER
    if ($estadoCie === 'cerrado') {
        return $btn;
    }

    // 🟢 PUBLICADO → CERRAR
    if ($estadoPub === 'publicado') {
        return $btn . "<a href='index.php?controller=cierre&action=ver&id={$id}' 
                        class='btn btn-sm btn-outline-secondary'>Cerrar</a>";
    }

    // 🟡 PENDIENTE / OBSERVADO → EVALUAR
    if (in_array($estadoEval, ['pendiente','observado'])) {
        return $btn . "<a href='index.php?controller=evaluacion&action=ver&id={$id}' 
                        class='btn btn-sm btn-outline-warning'>Evaluar</a>";
    }

    // 🔵 APROBADO → PUBLICAR
    if ($estadoEval === 'aprobado') {
        return $btn . "<a href='index.php?controller=publicacion&action=crear&id={$id}' 
                        class='btn btn-sm btn-outline-success'>Publicar</a>";
    }

    return $btn;
}
<?php require_once __DIR__ . "/layouts/header.php"; ?>

<div class="container py-4 fade-up">

    <!-- 🔷 TÍTULO -->
    <div class="mb-4">
        <h2 class="titulo">Dashboard de Casos Sociales</h2>
        <p class="subtitulo">Gestión y seguimiento de casos en tiempo real</p>
    </div>

    <!-- 📊 CARDS DE MÉTRICAS -->
    <div class="row g-4 mb-4">

        <?php
        function card($titulo, $valor, $color) {
            echo "
            <div class='col-md-3'>
                <div class='card p-3'>
                    <h6 class='subtitulo'>$titulo</h6>
                    <h3 style='color:$color; font-weight:700;'>$valor</h3>
                </div>
            </div>";
        }

        card("Pendientes", $stats['pendiente'], "#f39c12");
        card("Observados", $stats['observado'], "#e74c3c");
        card("Aprobados",  $stats['aprobado'], "#3498db");
        card("Publicados", $stats['publicado'], "#2ecc71");
        card("Cerrados",   $stats['cerrado'], "#7f8c8d");
        ?>

    </div>

    <!-- 🔍 FILTROS -->
    <div class="card p-3 mb-4">
        <div class="row g-3">

            <div class="col-md-4">
                <input type="text" id="busqueda" class="form-control" placeholder="Buscar caso...">
            </div>

            <div class="col-md-3">
                <select id="filtroEstado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="observado">Observado</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="publicado">Publicado</option>
                    <option value="cerrado">Cerrado</option>
                </select>
            </div>

        </div>
    </div>

    <!-- 📋 TABLA DE CASOS -->
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>ONG</th>
                        <th>Beneficiario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCasos">

                    <?php foreach ($casos as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= $c['titulo_caso'] ?></td>
                            <td><?= $c['ong_nombre'] ?></td>
                            <td><?= $c['nombre_beneficiario'] ?></td>
                            <td>
                                <span class="badge <?= getBadge($c['estado']) ?>">
                                    <?= ucfirst($c['estado']) ?>
                                </span>
                            </td>
                            <td>

                                <!-- VER (siempre) -->
                                <a href="index.php?controller=caso&action=ver&id=<?= $c['id'] ?>"
                                class="btn btn-sm btn-outline-primary">
                                Ver
                                </a>

                                <?php if (in_array($c['estado'], ['pendiente', 'observado'])): ?>

                                    <a href="index.php?controller=evaluacion&action=ver&id=<?= $c['id'] ?>"
                                    class="btn btn-sm btn-outline-warning">
                                    Evaluar
                                    </a>

                                <?php elseif ($c['estado'] === 'aprobado'): ?>

                                    <a href="index.php?controller=publicacion&action=crear&id=<?= $c['id'] ?>"
                                    class="btn btn-sm btn-outline-success">
                                    Publicar
                                    </a>

                                <?php elseif ($c['estado'] === 'publicado'): ?>

                                    <a href="index.php?controller=cierre&action=ver&id=<?= $c['id'] ?>"
                                    class="btn btn-sm btn-outline-secondary">
                                    Cerrar
                                    </a>

                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>


<!-- 🧠 FUNCIONES -->
<?php
function getBadge($estado) {
    return match($estado) {
        'pendiente' => 'bg-warning text-dark',
        'observado' => 'bg-danger',
        'aprobado'  => 'bg-primary',
        'publicado' => 'bg-success',
        'cerrado'   => 'bg-secondary',
        default     => 'bg-dark'
    };
}
?>

<!-- ⚡ AJAX FILTRO -->
<script>
document.getElementById('busqueda').addEventListener('keyup', filtrar);
document.getElementById('filtroEstado').addEventListener('change', filtrar);

function filtrar() {
    const texto = document.getElementById('busqueda').value;
    const estado = document.getElementById('filtroEstado').value;

    fetch(`index.php?controller=caso&action=listarAjax&busqueda=${texto}&estado=${estado}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('tablaCasos').innerHTML = html;
        });
}
</script>
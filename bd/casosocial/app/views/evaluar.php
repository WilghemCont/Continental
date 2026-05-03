<?php require_once __DIR__ . "/layouts/header.php"; ?>

<div class="container py-4 fade-up">

    <!-- TÍTULO -->
    <div class="mb-4">
        <h2 class="titulo">Evaluación del Caso</h2>
        <p class="subtitulo">Validación documental del caso social</p>
    </div>

    <!-- DATOS DEL CASO -->
    <div class="card p-4 mb-4">
        <h4 class="section-title"><?= $caso['titulo_caso'] ?></h4>

        <p><strong>ONG:</strong> <?= $caso['ong_nombre'] ?></p>
        <p><strong>Beneficiario:</strong> <?= $caso['nombre_beneficiario'] ?></p>
        <p><strong>Clasificación:</strong> <?= ucfirst($caso['clasificacion']) ?></p>
        <p><strong>Descripción:</strong> <?= $caso['descripcion'] ?></p>
    </div>

    <!-- FORMULARIO -->
    <form method="POST" action="index.php?controller=evaluacion&action=guardar&id=<?= $caso['id'] ?>" id="formEvaluacion">

        <div class="card p-4">

            <h5 class="section-title mb-4">Checklist de Evaluación</h5>

            <?php foreach($checklist as $item): ?>
                <div class="mb-4 border-bottom pb-3">

                    <label class="fw-bold mb-2 d-block">
                        <?= $item['nombre'] ?>
                    </label>

                    <div class="d-flex gap-4">

                        <div>
                            <input type="radio"
                                   name="check[<?= $item['id'] ?>]"
                                   value="SI"
                                   class="check-opcion"
                                   data-item="<?= $item['id'] ?>"
                                   required>
                            <label>SÍ</label>
                        </div>

                        <div>
                            <input type="radio"
                                   name="check[<?= $item['id'] ?>]"
                                   value="NO"
                                   class="check-opcion"
                                   data-item="<?= $item['id'] ?>">
                            <label>NO</label>
                        </div>

                    </div>

                    <!-- Comentario dinámico -->
                    <div class="mt-2 hidden comentario-box" id="comentario-<?= $item['id'] ?>">
                        <textarea
                            name="comentario[<?= $item['id'] ?>]"
                            class="form-control"
                            placeholder="Explique la observación..."></textarea>
                    </div>

                </div>
            <?php endforeach; ?>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2">

                <a href="index.php?controller=caso&action=dashboard"
                   class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar Evaluación
                </button>

            </div>

        </div>

    </form>

</div>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>


<script>

// Mostrar comentario si selecciona NO
document.querySelectorAll('.check-opcion').forEach(radio => {

    radio.addEventListener('change', function() {

        const item = this.dataset.item;
        const box = document.getElementById('comentario-' + item);

        if(this.value === 'NO'){
            box.classList.remove('hidden');
            box.querySelector('textarea').required = true;
        } else {
            box.classList.add('hidden');
            box.querySelector('textarea').required = false;
            box.querySelector('textarea').value = '';
        }

    });

});


// Validación general
document.getElementById('formEvaluacion').addEventListener('submit', function(e){

    const grupos = {};

    document.querySelectorAll('.check-opcion').forEach(radio => {
        const name = radio.name;
        if (!grupos[name]) grupos[name] = false;
        if (radio.checked) grupos[name] = true;
    });

    for (let grupo in grupos) {
        if (!grupos[grupo]) {
            e.preventDefault();
            alert('Debe responder todos los ítems del checklist.');
            return;
        }
    }

});
</script>
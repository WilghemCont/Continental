<?php require_once __DIR__ . "/layouts/header.php"; ?>

<div class="container py-4 fade-up">

    <h2 class="titulo mb-3">Cierre del Caso</h2>

    <form method="POST" enctype="multipart/form-data"
          action="index.php?controller=cierre&action=guardar&id=<?= $caso['id'] ?>">

        <div class="card p-4 mb-4">

            <h5 class="section-title">Checklist de Cierre</h5>

            <?php foreach($checklist as $item): ?>
                <div class="mb-3">

                    <label><?= $item['nombre'] ?></label>

                    <div>
                        <input type="radio" name="check[<?= $item['id'] ?>]" value="SI" required> SI
                        <input type="radio" name="check[<?= $item['id'] ?>]" value="NO"> NO
                    </div>

                    <textarea name="comentario[<?= $item['id'] ?>]"
                              class="form-control mt-2 hidden comentario"></textarea>

                </div>
            <?php endforeach; ?>

        </div>

        <!-- DOCUMENTOS -->
        <div class="card p-4 mb-4">

            <h5 class="section-title">Documentos de cierre</h5>

            <input type="file" name="documentos[]" multiple class="form-control">

        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="index.php?controller=caso&action=dashboard" class="btn btn-outline-secondary">Cancelar</a>
            <button class="btn btn-primary">Cerrar Caso</button>
        </div>

    </form>

</div>

<script>

// mostrar comentario si NO
document.querySelectorAll('input[type=radio]').forEach(r => {

    r.addEventListener('change', function() {

        let textarea = this.closest('div').parentNode.querySelector('.comentario');

        if(this.value === 'NO'){
            textarea.classList.remove('hidden');
            textarea.required = true;
        } else {
            textarea.classList.add('hidden');
            textarea.required = false;
            textarea.value = '';
        }

    });

});
</script>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>
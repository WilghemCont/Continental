<?php require_once __DIR__ . "/layouts/header.php"; ?>

<div class="container py-4 fade-up">

    <h2 class="titulo mb-3">Publicar Caso</h2>

    <form method="POST" action="index.php?controller=publicacion&action=guardar&id=<?= $caso['id'] ?>">

        <div class="row">

            <!-- FORM -->
            <div class="col-md-6">

                <div class="card p-4 mb-3">

                    <label>Título público</label>
                    <input type="text" name="titulo" id="titulo" class="form-control mb-3"
                           value="<?= $publicacion['titulo_publico'] ?? '' ?>" required>

                    <label>Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control mb-3" required><?= $publicacion['descripcion_publica'] ?? '' ?></textarea>

                    <label>Imagen (ruta)</label>
                    <input type="text" name="imagen" id="imagen" class="form-control mb-3"
                           placeholder="uploads/imagen.jpg">

                    <label>PDF (ruta)</label>
                    <input type="text" name="pdf" id="pdf" class="form-control mb-3"
                           placeholder="uploads/doc.pdf">

                    <div class="d-flex gap-2">
                        <button name="accion" value="borrador" class="btn btn-outline-secondary">Guardar</button>
                        <button name="accion" value="publicar" class="btn btn-primary">Publicar</button>
                    </div>

                </div>

            </div>

            <!-- PREVIEW -->
            <div class="col-md-6">

                <div class="card p-4">

                    <h5 class="section-title">Vista previa</h5>

                    <h4 id="previewTitulo"></h4>
                    <p id="previewDesc"></p>

                    <img id="previewImg" class="img-fluid mb-2 hidden">

                    <iframe id="previewPdf" width="100%" height="200" class="hidden"></iframe>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

// PREVIEW DINÁMICO
document.getElementById('titulo').addEventListener('input', e => {
    document.getElementById('previewTitulo').innerText = e.target.value;
});

document.getElementById('descripcion').addEventListener('input', e => {
    document.getElementById('previewDesc').innerText = e.target.value;
});

document.getElementById('imagen').addEventListener('input', e => {
    let img = document.getElementById('previewImg');
    if(e.target.value){
        img.src = '../' + e.target.value;
        img.classList.remove('hidden');
    }
});

document.getElementById('pdf').addEventListener('input', e => {
    let pdf = document.getElementById('previewPdf');
    if(e.target.value){
        pdf.src = '../' + e.target.value;
        pdf.classList.remove('hidden');
    }
});

</script>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>
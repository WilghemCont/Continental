<?php require_once 'layout/header.php'; ?>

<div class="container py-5">

    <div class="card shadow-lg border-0 p-5 rounded-4">

        <h2 class="fw-bold text-danger mb-4">
            <i class="bi bi-lock-fill me-2"></i>
            Cierre de Caso
        </h2>

        <form method="POST"
              enctype="multipart/form-data"
              action="index.php?controller=cierre&action=guardar&id=<?= $caso['id'] ?>">

            <div class="mb-4">

                <h5 class="fw-bold">
                    Checklist de Cierre
                </h5>

                <?php foreach($items as $item): ?>

                    <div class="border rounded-4 p-3 mb-3">

                        <label class="fw-bold d-block mb-3">
                            <?= $item['nombre'] ?>
                        </label>

                        <div class="d-flex gap-3 mb-3">

                            <label>
                                <input type="radio"
                                       name="check[<?= $item['id'] ?>]"
                                       value="SI"
                                       required>
                                SI
                            </label>

                            <label>
                                <input type="radio"
                                       name="check[<?= $item['id'] ?>]"
                                       value="NO">
                                NO
                            </label>

                        </div>

                        <textarea
                            name="comentario[<?= $item['id'] ?>]"
                            class="form-control"
                            rows="2"
                            placeholder="Comentario opcional"></textarea>

                    </div>

                <?php endforeach; ?>

            </div>

            <!-- DOCUMENTO -->

                    <div class="mb-4">

            <label class="fw-bold mb-3 d-block">
                Documento Sustento Final (PDF)
            </label>

            <?php if(!empty($caso['documento_cierre'])): ?>

                <div class="border rounded-4 p-3 bg-light">

                    <div class="d-flex align-items-center gap-3">

                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>

                        <div class="flex-grow-1">
                            <div class="fw-bold">
                                <?= esc($caso['documento_cierre']) ?>
                            </div>

                            <small class="text-muted">
                                Documento cargado por la ONG
                            </small>
                        </div>

                        <a href="../assets/uploads/docs/<?= $caso['documento_cierre'] ?>"
                        target="_blank"
                        class="btn btn-danger rounded-pill px-4">

                            <i class="bi bi-eye-fill me-2"></i>
                            Ver PDF

                        </a>

                    </div>

                </div>

            <?php else: ?>

                <div class="alert alert-warning rounded-4 mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    La ONG aún no adjuntó el documento de cierre.
                </div>

            <?php endif; ?>

        </div>

            <button class="btn btn-danger btn-lg rounded-pill px-5 fw-bold">

                <i class="bi bi-check-circle-fill me-2"></i>
                Finalizar Caso

            </button>

        </form>

    </div>

</div>

<?php require_once 'layout/footer.php'; ?>
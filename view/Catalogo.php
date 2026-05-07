
<?php require_once 'layout/header.php'; ?>

<div class="container py-5">

    <h2 class="titulo text-center mb-4">Catálogo de Casos Sociales</h2>

    <div class="row g-4">

        <?php if (!empty($casos)): ?>
            <?php foreach ($casos as $c): ?>
                
                <div class="col-md-4 col-sm-6 fade-up">
                    <div class="card h-100">

                        <!-- Imagen -->
                        <img src="../assets/uploads/fotos/<?= $c['foto_beneficiario'] ?>" 
                            class="card-img-top" 
                            style="height: 200px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">

                            <h5>
                                <?= htmlspecialchars($c['titulo_publico'] ?? $c['titulo_caso']) ?>
                                </h5>

                                <p>
                                <?= substr($c['descripcion_publica'] ?? $c['descripcion'], 0, 100) ?>...
                                </p>

                            <!-- Botón -->
                            <a href="index.php?controller=caso&action=vistaCaso&id=<?= $c['id'] ?>" 
                                class="btn btn-primary mt-3">
                                Ver caso
                                </a>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay casos disponibles.</p>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
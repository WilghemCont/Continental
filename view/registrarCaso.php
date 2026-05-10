<?php require_once 'layout/header.php'; ?>

<div class="container mt-4">

    <h2>Registrar Caso Social</h2>

    <form action="../../controllers/Ongcontroller.php?action=guardar"
          method="POST"
          enctype="multipart/form-data">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Nombre ONG</label>
                <input type="text" name="nombre_ong" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>RUC ONG</label>
                <input type="text" name="ruc_ong" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Contacto</label>
                <input type="text" name="contacto_ong" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email ONG</label>
                <input type="email" name="email_ong" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>Título del Caso</label>
                <input type="text" name="titulo_caso" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3">
                <label>Descripción</label>
                <textarea name="descripcion"
                          class="form-control"
                          rows="5"></textarea>
            </div>

            <div class="col-md-4 mb-3">
                <label>Clasificación</label>

                <select name="clasificacion" class="form-control">

                    <option value="salud">Salud</option>
                    <option value="educacion">Educación</option>
                    <option value="desastres">Desastres</option>
                    <option value="medio_ambiente">Medio Ambiente</option>

                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Monto requerido</label>
                <input type="number" name="monto_requerido" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Beneficiario</label>
                <input type="text" name="nombre_beneficiario" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>DNI</label>
                <input type="text" name="dni_beneficiario" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Edad</label>
                <input type="number" name="edad_beneficiario" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Documento PDF</label>
                <input type="file" name="documento_solicitud" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Foto Beneficiario</label>
                <input type="file" name="foto_beneficiario" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary">
            Registrar Caso
        </button>

    </form>

</div>

<?php require_once 'layout/footer.php'; ?>
document.addEventListener("DOMContentLoaded", () => {

    const tipoDocumento = document.getElementById("tipoDocumento");
    const persona = document.getElementById("personaFields");
    const empresa = document.getElementById("empresaFields");

    // --- NUEVO: Carga dinámica de tipos de documento desde la BD ---
    fetch("../Controllers/usuariocontroller.php?op=combo_tipodoc")
        .then(response => response.text())
        .then(html => {
            tipoDocumento.innerHTML = html;
        })
        .catch(error => console.error("Error cargando tipos de doc:", error));
    // -------------------------------------------------------------

    tipoDocumento.addEventListener("change", () => {
        persona.classList.add("d-none");
        empresa.classList.add("d-none");

        // Convertimos a minúsculas para que coincida con tu lógica original
        let valor = tipoDocumento.value.toLowerCase();

        if (valor === "ruc") {
            empresa.classList.remove("d-none");
        } else if (valor !== "") {
            persona.classList.remove("d-none");
        }
    });

    document.getElementById("registroForm").addEventListener("submit", (e) => {
        // Bloqueamos el envío por defecto para validar primero
        e.preventDefault();

        let tipo = tipoDocumento.value.toLowerCase();
        let doc = document.getElementById("numeroDocumento").value;
        let valido = true;

        // Validaciones originales de longitud
        if (tipo === "dni" && doc.length !== 8) {
            mostrarAlerta("El DNI debe tener 8 dígitos");
            valido = false;
        }

        if (tipo === "ruc" && doc.length !== 11) {
            mostrarAlerta("El RUC debe tener 11 dígitos");
            valido = false;
        }

        // Validaciones de contraseña según el bloque visible
        if (tipo === "ruc") {
            let p1 = document.getElementById("passwordEmpresa").value;
            let p2 = document.getElementById("passwordEmpresa2").value;

            if (p1.length < 6) {
                document.getElementById("errorPassEmpresa").textContent = "mínimo 6 caracteres";
                valido = false;
            } else if (p1 !== p2) {
                document.getElementById("errorPassEmpresa").textContent = "las contraseñas no coinciden";
                valido = false;
            } else {
                document.getElementById("errorPassEmpresa").textContent = "";
            }
        } else {
            let p1 = document.getElementById("passwordPersona").value;
            let p2 = document.getElementById("passwordPersona2").value;

            if (p1.length < 6) {
                document.getElementById("errorPassPersona").textContent = "mínimo 6 caracteres";
                valido = false;
            } else if (p1 !== p2) {
                document.getElementById("errorPassPersona").textContent = "las contraseñas no coinciden";
                valido = false;
            } else {
                document.getElementById("errorPassPersona").textContent = "";
            }
        }

        // --- NUEVO: Envío de datos si todo es válido ---
        if (valido) {
            const form = document.getElementById("registroForm");
            const formData = new FormData(form);

            // IMPORTANTE: Como el número de documento está fuera de los divs, 
            // nos aseguramos de que viaje en el FormData
            formData.append('num_doc', doc);
            formData.append('tipo_doc', tipoDocumento.value);

            fetch("../Controllers/usuariocontroller.php?op=guardar_registro", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "1") {
                    mostrarAlerta("¡Registro exitoso! Redirigiendo al login...", "success");
                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 2000);
                } else {
                    mostrarAlerta("Error en el servidor: El documento o correo ya podrían estar registrados.");
                }
            })
            .catch(error => {
                mostrarAlerta("Error de conexión. Intente más tarde.");
                console.error("Error:", error);
            });
        }
    });

    function mostrarAlerta(mensaje, tipo = "danger") {
        const alerta = document.getElementById("alerta");
        alerta.className = `alert alert-${tipo}`;
        alerta.textContent = mensaje;
        alerta.classList.remove("d-none");

        setTimeout(() => {
            alerta.classList.add("d-none");
        }, 4000);
    }
});
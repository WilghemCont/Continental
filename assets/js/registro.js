document.addEventListener("DOMContentLoaded", () => {

  const tipoDocumento = document.getElementById("tipoDocumento");
  const persona = document.getElementById("personaFields");
  const empresa = document.getElementById("empresaFields");

  tipoDocumento.addEventListener("change", () => {

    persona.classList.add("d-none");
    empresa.classList.add("d-none");

    if (tipoDocumento.value === "ruc") {
      empresa.classList.remove("d-none");
    } else if (tipoDocumento.value !== "") {
      persona.classList.remove("d-none");
    }

  });

  document.getElementById("registroForm").addEventListener("submit", (e) => {

    let tipo = tipoDocumento.value;
    let doc = document.getElementById("numeroDocumento").value;
    let valido = true;

    if (tipo === "dni" && doc.length !== 8) {
      mostrarAlerta("El DNI debe tener 8 dígitos");
      valido = false;
    }

    if (tipo === "ruc" && doc.length !== 11) {
      mostrarAlerta("El RUC debe tener 11 dígitos");
      valido = false;
    }

    if (tipo === "ruc") {
      let p1 = document.getElementById("passwordEmpresa").value;
      let p2 = document.getElementById("passwordEmpresa2").value;

      if (p1.length < 6) {
        document.getElementById("errorPassEmpresa").textContent = "mínimo 6 caracteres";
        valido = false;
      }

      if (p1 !== p2) {
        document.getElementById("errorPassEmpresa").textContent = "las contraseñas no coinciden";
        valido = false;
      }

    } else {

      let p1 = document.getElementById("passwordPersona").value;
      let p2 = document.getElementById("passwordPersona2").value;

      if (p1.length < 6) {
        document.getElementById("errorPassPersona").textContent = "mínimo 6 caracteres";
        valido = false;
      }

      if (p1 !== p2) {
        document.getElementById("errorPassPersona").textContent = "las contraseñas no coinciden";
        valido = false;
      }

    }

    if (!valido) e.preventDefault();

  });

  function mostrarAlerta(mensaje, tipo = "danger") {
    const alerta = document.getElementById("alerta");

    alerta.className = `alert alert-${tipo}`;
    alerta.textContent = mensaje;
    alerta.classList.remove("d-none");

    // Ocultar después de 3 segundos
    setTimeout(() => {
        alerta.classList.add("d-none");
    }, 3000);
  }

});
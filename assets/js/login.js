document.addEventListener("DOMContentLoaded", () => {

  const form = document.getElementById("formLogin");
  const error = document.getElementById("error");

  // Mostrar / ocultar contraseña
  document.getElementById("togglePass").addEventListener("click", () => {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
  });

  // Validación
  form.addEventListener("submit", (e) => {
    let correo = document.getElementById("correo").value;
    let pass = document.getElementById("password").value;

    if (correo === "" || pass === "") {
      e.preventDefault();
      error.innerText = "❌ Completa todos los campos";
    }
  });

  // Error desde PHP
  const params = new URLSearchParams(window.location.search);
  if (params.get("error")) {
    error.innerText = "❌ Correo o contraseña incorrectos";
  }

});
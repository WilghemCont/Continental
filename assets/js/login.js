document.addEventListener("DOMContentLoaded", () => {
    console.log("Login JS cargado correctamente");

    const form = document.getElementById("formLogin");
    const errorMsg = document.getElementById("error");

    // Toggle password
    document.getElementById("togglePass").addEventListener("click", () => {
        let pass = document.getElementById("password");
        pass.type = pass.type === "password" ? "text" : "password";
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault(); // DETIENE LA RECARGA DE LA PÁGINA
        
        console.log("Intentando conectar con el controlador...");

        const formData = new FormData(form);

        // Ajustamos la ruta: si estás en view/login.php, 
        // para ir a controller/ debe subir un nivel (../)
        fetch("../Controllers/usuariocontroller.php?op=acceso", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            console.log("Respuesta servidor:", data);
            if (data.trim() === "1") {
                window.location.href = "home.php";
            } else {
                errorMsg.innerText = "❌ Correo o contraseña incorrectos";
            }
        })
        .catch(err => {
            console.error("Error Fetch:", err);
            errorMsg.innerText = "❌ Error de comunicación con el servidor";
        });
    });
});
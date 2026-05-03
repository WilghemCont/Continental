function filtrar() {
    const texto = document.getElementById('busqueda').value;
    let estado = document.getElementById('filtroEstado').value;

    if (estado === "") estado = "todos";

    fetch(`index.php?controller=caso&action=listarAjax&busqueda=${encodeURIComponent(texto)}&estado=${estado}&t=${Date.now()}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('tablaCasos').innerHTML = html;
        });
}
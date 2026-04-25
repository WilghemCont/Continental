/**
 * main.js - Lógica global de la interfaz
 */
document.addEventListener('DOMContentLoaded', function () {
    
    // --- SOPORTE PARA SUBMENÚS (Maestros, etc.) ---
    const submenus = document.querySelectorAll('.dropdown-submenu .dropdown-toggle');

    submenus.forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Evita que el dropdown padre se cierre

            const submenu = this.nextElementSibling;
            
            // Cerrar otros submenús abiertos en el mismo nivel
            const parentMenu = this.closest('.dropdown-menu');
            parentMenu.querySelectorAll('.dropdown-menu.show').forEach(function (openSubmenu) {
                if (openSubmenu !== submenu) {
                    openSubmenu.classList.remove('show');
                }
            });

            // Abrir/Cerrar el submenú actual
            submenu.classList.toggle('show');
        });
    });

    // --- LIMPIEZA AL CERRAR EL MENÚ PRINCIPAL ---
    // Si el usuario cierra el dropdown principal, cerramos todos los submenús internos
    const mainDropdowns = document.querySelectorAll('.dropdown');
    mainDropdowns.forEach(function (dd) {
        dd.addEventListener('hide.bs.dropdown', function () {
            this.querySelectorAll('.dropdown-menu').forEach(function (submenu) {
                submenu.classList.remove('show');
            });
        });
    });
});
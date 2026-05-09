/**
 * assets/js/beneficiario.js
 * Lógica interactiva de la página del beneficiario en SocialFunding.
 */
document.addEventListener("DOMContentLoaded", () => {

    // ── 1. Animación de la barra de progreso ──────────────────────────
    const fill = document.getElementById("progressFill");
    if (fill) {
        const target = parseFloat(fill.dataset.pct) || 0;
        // Pequeño delay para que la animación CSS sea visible
        setTimeout(() => {
            fill.style.width = Math.min(target, 100) + "%";
        }, 300);
    }

    // ── 2. Contador animado de montos ─────────────────────────────────
    document.querySelectorAll(".count-up").forEach(el => {
        const final   = parseFloat(el.dataset.target) || 0;
        const prefix  = el.dataset.prefix || "";
        const suffix  = el.dataset.suffix || "";
        const decimals= parseInt(el.dataset.decimals) || 0;
        const duration= 1400;
        const step    = 16;
        const steps   = duration / step;
        const inc     = final / steps;
        let current   = 0;

        const timer = setInterval(() => {
            current += inc;
            if (current >= final) {
                current = final;
                clearInterval(timer);
            }
            el.textContent = prefix + current.toLocaleString("es-PE", {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }) + suffix;
        }, step);
    });

    // ── 3. Guardar testimonio (AJAX) ──────────────────────────────────
    const formTestimonio = document.getElementById("formTestimonio");
    if (formTestimonio) {
        formTestimonio.addEventListener("submit", e => {
            e.preventDefault();

            const btn       = formTestimonio.querySelector("button[type=submit]");
            const alert     = document.getElementById("alertTestimonio");
            const contenido = formTestimonio.querySelector("textarea[name=contenido]").value.trim();

            if (!contenido) {
                mostrarAlerta(alert, "warning", "Por favor escribe tu testimonio antes de guardar.");
                return;
            }

            btn.disabled    = true;
            btn.innerHTML   = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

            const formData = new FormData(formTestimonio);

            fetch("../Controllers/BeneficiarioController.php?op=guardar_testimonio", {
                method: "POST",
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    mostrarAlerta(alert, "success", "✓ Tu testimonio fue guardado correctamente. ¡Gracias por compartir tu experiencia!");
                    // Actualizar sección de lectura si existe
                    const lectura = document.getElementById("testimonioTexto");
                    if (lectura) lectura.textContent = contenido;
                } else {
                    mostrarAlerta(alert, "danger", "Error al guardar: " + (data.msg || "intenta de nuevo"));
                }
            })
            .catch(() => {
                mostrarAlerta(alert, "danger", "Error de comunicación. Por favor intenta nuevamente.");
            })
            .finally(() => {
                btn.disabled  = false;
                btn.innerHTML = '<i class="bi bi-send-check me-2"></i>Guardar Testimonio';
            });
        });
    }

    // ── 4. Helper: mostrar alerta Bootstrap ──────────────────────────
    function mostrarAlerta(el, tipo, msg) {
        if (!el) return;
        el.className = `alert alert-${tipo} rounded-3 mt-3`;
        el.textContent = msg;
        el.classList.remove("d-none");
        setTimeout(() => el.classList.add("d-none"), 6000);
    }

    // ── 5. Scroll suave a secciones internas ─────────────────────────
    document.querySelectorAll("a[href^='#']").forEach(link => {
        link.addEventListener("click", e => {
            const target = document.querySelector(link.getAttribute("href"));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    });
});

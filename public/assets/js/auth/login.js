document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    const email = document.getElementById("email");
    const pass = document.getElementById("mot_de_passe");
    const toggleBtn = document.getElementById("togglePassword");
    const googleBtn = document.getElementById("googleBtn");
    const facebookBtn = document.getElementById("facebookBtn");

    // TOGGLE PASSWORD (icônes SVG dans le bouton)
    const eyeOpen = toggleBtn?.querySelector(".js-eye-open");
    const eyeShut = toggleBtn?.querySelector(".js-eye-shut");
    if (toggleBtn && pass) {
        toggleBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const showPlain = pass.type === "password";
            pass.type = showPlain ? "text" : "password";
            eyeOpen?.classList.toggle("hidden", showPlain);
            eyeShut?.classList.toggle("hidden", !showPlain);
            toggleBtn.setAttribute("aria-label", showPlain ? "Masquer le mot de passe" : "Afficher le mot de passe");
            toggleBtn.setAttribute("aria-pressed", showPlain ? "true" : "false");
        });
    }

    // SOCIAL BUTTONS
    if (googleBtn) {
        googleBtn.addEventListener("click", (e) => {
            e.preventDefault();
            alert("Fonctionnalité non implémentée");
        });
    }

    if (facebookBtn) {
        facebookBtn.addEventListener("click", (e) => {
            e.preventDefault();
            alert("Fonctionnalité non implémentée");
        });
    }

    function showError(id, msg) {
        document.getElementById("err-" + id).textContent = msg || "";
    }

    function showGlobal(msg) {
        document.getElementById("err-global").textContent = msg;
    }

    function clearErrors() {
        showError("email", "");
        showError("mot_de_passe", "");
        showGlobal("");
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        clearErrors();

        fetch("/login", {
            method: "POST",
            body: new FormData(form)
        })
        .then(r => r.json())
        .then(data => {

            if (data.success) {
                window.location.href = "/dashboard";
            } else {

                if (data.errors) {
                    if (data.errors.email) showError("email", data.errors.email);
                    if (data.errors.mot_de_passe) showError("mot_de_passe", data.errors.mot_de_passe);
                } else {
                    showGlobal(data.message);
                }
            }
        })
        .catch(() => {
            showGlobal("Erreur réseau");
        });
    });

});
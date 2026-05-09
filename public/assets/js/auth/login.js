document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    const email = document.getElementById("email");
    const pass = document.getElementById("mot_de_passe");
    const toggleBtn = document.getElementById("togglePassword");
    const googleBtn = document.getElementById("googleBtn");
    const facebookBtn = document.getElementById("facebookBtn");

    // TOGGLE PASSWORD
    if (toggleBtn) {
        toggleBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const isPassword = pass.type === "password";
            pass.type = isPassword ? "text" : "password";
            toggleBtn.innerHTML = isPassword 
                ? '<i class="fas fa-eye-slash"></i>' 
                : '<i class="fas fa-eye"></i>';
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
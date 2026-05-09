document.addEventListener("DOMContentLoaded", () => {

    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");

    const nextBtn = document.getElementById("next-btn");
    const prevBtn = document.getElementById("prev-btn");

    const progressSteps = document.querySelectorAll(".progress-step");
    const form = document.getElementById("registerForm");

    // STATE
    let errors = {
        nom_complet: true,
        email: true,
        mot_de_passe: true,
        confirm: true,
        genre: true,
        taille: true,
        poids: true
    };

    function canStep1() {
        return !errors.nom_complet &&
               !errors.email &&
               !errors.mot_de_passe &&
               !errors.confirm &&
               !errors.genre;
    }

    function canSubmit() {
        return canStep1() && !errors.taille && !errors.poids;
    }

    // RESET initial 
    function setValid(field) {
        errors[field] = false;
    }

    function setInvalid(field) {
        errors[field] = true;
    }

    // UI
    function showError(id, msg) {
        const el = document.getElementById("err-" + id);
        if (el) el.textContent = msg || "";
    }

    function clearError(id) {
        const el = document.getElementById("err-" + id);
        if (el) el.textContent = "";
    }

    function showGlobal(msg) {
        document.getElementById("err-step1").textContent = msg;
    }

    function clearGlobal() {
        document.getElementById("err-step1").textContent = "";
    }

    // AJAX
    function validate(input, value, cb) {
        fetch('/auth/validation-input', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json'
            },
            body: `input=${input}&value=${encodeURIComponent(value)}`
        })
        .then(async r => {

            const text = await r.text();

            try {
                return JSON.parse(text);
            } catch (e) {
                console.error("REPONSE BRUTE SERVER:", text);
                throw new Error(text); 
            }
        })
        .then(cb)
        .catch(err => {
            cb({
                valid: false,
                errors: [err.message || "Erreur serveur"]
            });
        });
    }

    // INPUTS
    const nom = document.getElementById("nom_complet");
    const email = document.getElementById("email");
    const pass = document.getElementById("mot_de_passe");
    const confirm = document.getElementById("confirm_password");
    const taille = document.getElementById("taille");
    const poids = document.getElementById("poids");

    // NOM
    nom.addEventListener("blur", () => {
        validate("nom_complet", nom.value, res => {
            errors.nom_complet = !res.valid;
            res.valid ? clearError("nom_complet") : showError("nom_complet", res.errors[0]);
        });
    });

    // EMAIL
    email.addEventListener("blur", () => {
        validate("email", email.value, res => {
            errors.email = !res.valid;
            res.valid ? clearError("email") : showError("email", res.errors[0]);
        });
    });

    // PASSWORD
    pass.addEventListener("blur", () => {
        validate("mot_de_passe", pass.value, res => {
            errors.mot_de_passe = !res.valid;
            res.valid ? clearError("mot_de_passe") : showError("mot_de_passe", res.errors[0]);
        });
    });

    // CONFIRM
    confirm.addEventListener("blur", () => {
        errors.confirm = confirm.value !== pass.value;
        errors.confirm
            ? showError("confirm", "Les mots de passe ne correspondent pas")
            : clearError("confirm");
    });

    // GENRE 
    document.querySelectorAll('input[name="genre"]').forEach(radio => {
        radio.addEventListener("change", () => {
            const value = document.querySelector('input[name="genre"]:checked')?.value;

            validate("genre", value, res => {
                errors.genre = !res.valid;
                res.valid ? clearError("genre") : showError("genre", res.errors[0]);
            });
        });
    });

    // TAILLE
    taille.addEventListener("blur", () => {
        validate("taille", taille.value, res => {
            errors.taille = !res.valid;
            res.valid ? clearError("taille") : showError("taille", res.errors[0]);
        });
    });

    // POIDS
    poids.addEventListener("blur", () => {
        validate("poids", poids.value, res => {
            errors.poids = !res.valid;
            res.valid ? clearError("poids") : showError("poids", res.errors[0]);
        });
    });

    // NEXT
    nextBtn.addEventListener("click", () => {

        const genre = document.querySelector('input[name="genre"]:checked');
        if (!genre) errors.genre = true;

        if (!canStep1()) {
            showGlobal("Corrige les erreurs avant de continuer");
            return;
        }

        clearGlobal();
        step1.classList.remove("active");
        step2.classList.add("active");
        progressSteps[1].classList.add("active");
    });

    prevBtn.addEventListener("click", () => {
        step2.classList.remove("active");
        step1.classList.add("active");
        progressSteps[1].classList.remove("active");
    });

    // SUBMIT
    form.addEventListener("submit", e => {
        e.preventDefault();

        if (!canSubmit()) {
            showGlobal("Formulaire invalide");
            return;
        }

        fetch("/register", {
            method: "POST",
            body: new FormData(form)
        })
        .then(r => r.json())
        .then(data => {

            if (data.success) {
                window.location.href = "/login";
            } else {

                if (data.errors) {
                    Object.keys(data.errors).forEach(k => {
                        showError(k, data.errors[k]);
                    });
                } else {
                    showGlobal(data.message);
                }
            }
        })
        .catch(err => {
            showGlobal(err.message);
        });
    });

});
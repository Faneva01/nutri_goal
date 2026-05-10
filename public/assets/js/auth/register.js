document.addEventListener("DOMContentLoaded", () => {

    const step1 = document.getElementById("step-1");
    const step2 = document.getElementById("step-2");

    const nextBtn = document.getElementById("next-btn");
    const prevBtn = document.getElementById("prev-btn");

    const progressSteps = document.querySelectorAll(".progress-step");
    const form = document.getElementById("registerForm");

    const pass = document.getElementById("mot_de_passe");
    const confirm = document.getElementById("confirm_password");
    const toggle1 = document.getElementById("togglePassword1");
    const toggle2 = document.getElementById("togglePassword2");

    // INPUTS
    const nom = document.getElementById("nom_complet");
    const email = document.getElementById("email");
    const taille = document.getElementById("taille");
    const poids = document.getElementById("poids");

    // TOGGLE PASSWORD 1
    if (toggle1) {
        toggle1.addEventListener("click", (e) => {
            e.preventDefault();
            const isPassword = pass.type === "password";
            pass.type = isPassword ? "text" : "password";
            toggle1.innerHTML = isPassword 
                ? '<i class="fas fa-eye-slash"></i>' 
                : '<i class="fas fa-eye"></i>';
        });
    }

    // TOGGLE PASSWORD 2
    if (toggle2) {
        toggle2.addEventListener("click", (e) => {
            e.preventDefault();
            const isPassword = confirm.type === "password";
            confirm.type = isPassword ? "text" : "password";
            toggle2.innerHTML = isPassword 
                ? '<i class="fas fa-eye-slash"></i>' 
                : '<i class="fas fa-eye"></i>';
        });
    }

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

    // AJAX VALIDATION
    function validate(input, value) {
        return new Promise((resolve) => {
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
            .then(data => {
                errors[input] = !data.valid;
                if (data.valid) {
                    clearError(input);
                } else {
                    showError(input, data.errors[0]);
                }
                resolve();
            })
            .catch(err => {
                errors[input] = true;
                showError(input, err.message || "Erreur serveur");
                resolve();
            });
        });
    }

    // VALIDATE FIELD SYNCHRONE (LOCAL ONLY)
    function validateFieldLocal(fieldName, value) {
        if (fieldName === "mot_de_passe") {
            if (value.length < 6) {
                showError("mot_de_passe", "Mot de passe trop faible");
                errors.mot_de_passe = true;
                return false;
            }
            clearError("mot_de_passe");
            errors.mot_de_passe = false;
            return true;
        }

        if (fieldName === "confirm") {
            if (value !== pass.value) {
                showError("confirm", "Les mots de passe ne correspondent pas");
                errors.confirm = true;
                return false;
            }
            clearError("confirm");
            errors.confirm = false;
            return true;
        }

        if (fieldName === "genre") {
            const genre = document.querySelector('input[name="genre"]:checked');
            if (!genre) {
                showError("genre", "Genre invalide");
                errors.genre = true;
                return false;
            }
            clearError("genre");
            errors.genre = false;
            return true;
        }

        if (fieldName === "taille") {
            if (!value || !Number.isInteger(Number(value)) || value < 50 || value > 250) {
                showError("taille", "Taille invalide");
                errors.taille = true;
                return false;
            }
            clearError("taille");
            errors.taille = false;
            return true;
        }

        if (fieldName === "poids") {
            if (!value || !Number.isInteger(Number(value)) || value < 20 || value > 300) {
                showError("poids", "Poids invalide");
                errors.poids = true;
                return false;
            }
            clearError("poids");
            errors.poids = false;
            return true;
        }

        return false;
    }

    // VALIDATE STEP 1 (ALL FIELDS)
    async function validateStep1Complete() {
        clearGlobal();
        
        validateFieldLocal("genre", "");
        validateFieldLocal("mot_de_passe", pass.value);
        validateFieldLocal("confirm", confirm.value);
        validateFieldLocal("taille", taille.value);
        validateFieldLocal("poids", poids.value);

        // ASYNC VALIDATIONS (NOM, EMAIL)
        const validations = [
            validate("nom_complet", nom.value),
            validate("email", email.value)
        ];

        await Promise.all(validations);
    }

    // VALIDATE STEP 2
    async function validateStep2Complete() {
        validateFieldLocal("taille", taille.value);
        validateFieldLocal("poids", poids.value);
    }

    // NOM - BLUR
    nom.addEventListener("blur", () => {
        validate("nom_complet", nom.value);
    });

    // EMAIL - BLUR
    email.addEventListener("blur", () => {
        validate("email", email.value);
    });

    // PASSWORD - BLUR
    pass.addEventListener("blur", () => {
        validateFieldLocal("mot_de_passe", pass.value);
    });

    // CONFIRM - BLUR
    confirm.addEventListener("blur", () => {
        validateFieldLocal("confirm", confirm.value);
    });

    // GENRE - CHANGE
    document.querySelectorAll('input[name="genre"]').forEach(radio => {
        radio.addEventListener("change", () => {
            validate("genre", document.querySelector('input[name="genre"]:checked')?.value);
        });
    });

    // TAILLE - BLUR
    taille.addEventListener("blur", () => {
        validateFieldLocal("taille", taille.value);
    });

    // POIDS - BLUR
    poids.addEventListener("blur", () => {
        validateFieldLocal("poids", poids.value);
    });

    // NEXT BUTTON
    nextBtn.addEventListener("click", async () => {
        await validateStep1Complete();

        if (!canStep1()) {
            showGlobal("Corrige les erreurs avant de continuer");
            return;
        }

        clearGlobal();
        step1.classList.remove("active");
        step2.classList.add("active");
        progressSteps[1].classList.add("active");
    });

    // PREV BUTTON
    prevBtn.addEventListener("click", () => {
        step2.classList.remove("active");
        step1.classList.add("active");
        progressSteps[1].classList.remove("active");
    });

    // SUBMIT FORM
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        await validateStep2Complete();

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
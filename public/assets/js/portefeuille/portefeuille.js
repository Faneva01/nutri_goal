/**
 * portefeuille.js — NutriGoal
 *
 * FIX: suppression de <?= site_url(...) ?> dans un fichier .js statique
 *      (le PHP n'est pas interprété dans les fichiers .js servis directement).
 *      On utilise désormais une URL relative ou window.baseUrl définie inline
 *      dans le template PHP qui inclut ce script.
 *
 * Convention : dans votre layout PHP, ajoutez AVANT ce script :
 *   <script>window.baseUrl = "<?= rtrim(base_url(), '/') ?>";</script>
 *   <script>window.csrfToken = "<?= csrf_hash() ?>";</script>
 *   <script>window.csrfHeader = "<?= csrf_header() ?>";</script>
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Vérification de code en temps réel (AJAX) ───────────────────────────
    const codeInput    = document.getElementById('code');
    const codeStatusEl = document.getElementById('code-status');

    if (codeInput && codeStatusEl) {
        codeInput.addEventListener('input', debounce(verifyCodeLive, 500));
    }

    // ── Copier un code dans le presse-papiers ───────────────────────────────
    document.querySelectorAll('.btn-copy-code').forEach(btn => {
        btn.addEventListener('click', handleCopy);
    });

    // ── Fermer les alertes ──────────────────────────────────────────────────
    document.querySelectorAll('.alert-close').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.alert').remove();
        });
    });

    // ── Validation formulaire achat ─────────────────────────────────────────
    const buyForm = document.getElementById('buy-form');
    if (buyForm) buyForm.addEventListener('submit', validateBuyForm);

    // ── Validation formulaire validation de code ────────────────────────────
    const validateForm = document.getElementById('validate-form');
    if (validateForm) validateForm.addEventListener('submit', validateCodeForm);

    // ── Format numérique du montant ─────────────────────────────────────────
    const montantInput = document.getElementById('montant');
    if (montantInput) montantInput.addEventListener('input', sanitizeMontant);

    // ── Format carte bancaire ───────────────────────────────────────────────
    const carteInput = document.getElementById('numero_carte');
    if (carteInput) carteInput.addEventListener('input', formatCardNumber);

    const expInput = document.getElementById('date_expiration');
    if (expInput) expInput.addEventListener('input', formatExpiry);
});

// ── DEBOUNCE ─────────────────────────────────────────────────────────────────

function debounce(fn, ms) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), ms);
    };
}

// ── VÉRIFICATION CODE (AJAX) ──────────────────────────────────────────────────

function verifyCodeLive() {
    const code       = document.getElementById('code').value.trim();
    const statusDiv  = document.getElementById('code-status');
    const codeInput  = document.getElementById('code');

    codeInput.classList.remove('is-valid', 'is-invalid');
    statusDiv.innerHTML = '';

    if (!code || code.length < 8) return;

    // FIX: on utilise window.baseUrl injecté par le template PHP
    const url = (window.baseUrl || '') + '/code/verifier';

    const body = new URLSearchParams({ code });

    // Ajouter le token CSRF si disponible
    if (window.csrfToken && window.csrfHeader) {
        body.append(window.csrfHeader, window.csrfToken);
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: body.toString(),
    })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                statusDiv.innerHTML = `
                    <div class="alert alert-success">
                        <span><i class="fas fa-check-circle"></i>
                        Code valide — Montant : <strong>${formatNumber(data.montant)} Ar</strong></span>
                    </div>`;
                codeInput.classList.add('is-valid');
            } else {
                statusDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <span><i class="fas fa-times-circle"></i> ${data.message}</span>
                    </div>`;
                codeInput.classList.add('is-invalid');
            }
        })
        .catch(() => {
            showToast('error', 'Erreur de connexion au serveur.');
        });
}

// ── COPY TO CLIPBOARD ─────────────────────────────────────────────────────────

function handleCopy(e) {
    e.preventDefault();
    const text = this.dataset.code || this.closest('[data-code]')?.dataset.code || '';
    if (!text) return;

    navigator.clipboard.writeText(text).then(() => {
        const original = this.innerHTML;
        this.innerHTML = '<i class="fas fa-check"></i> Copié !';
        setTimeout(() => { this.innerHTML = original; }, 2000);
    }).catch(() => {
        showToast('warning', 'Copie automatique non disponible — sélectionnez le code manuellement.');
    });
}

// ── VALIDATION FORMULAIRES ────────────────────────────────────────────────────

function validateBuyForm(e) {
    const montant = parseFloat(document.getElementById('montant')?.value);
    const moyen   = document.querySelector('input[name="moyen_paiement"]:checked');

    if (!montant || montant < 1000) {
        e.preventDefault();
        showToast('error', 'Le montant minimum est 1 000 Ar.');
        return false;
    }
    if (!moyen) {
        e.preventDefault();
        showToast('error', 'Veuillez choisir un moyen de paiement.');
        return false;
    }
    return true;
}

function validateCodeForm(e) {
    const code = document.getElementById('code')?.value.trim();
    if (!code) {
        e.preventDefault();
        showToast('error', 'Veuillez saisir votre code.');
        return false;
    }
    if (code.length < 8) {
        e.preventDefault();
        showToast('error', 'Le code semble incomplet.');
        return false;
    }
    return true;
}

// ── FORMAT HELPERS ─────────────────────────────────────────────────────────────

function sanitizeMontant(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
}

function formatCardNumber(e) {
    let v = e.target.value.replace(/\D/g, '').slice(0, 16);
    e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiry(e) {
    let v = e.target.value.replace(/\D/g, '').slice(0, 4);
    if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
    e.target.value = v;
}

function formatNumber(n) {
    return new Intl.NumberFormat('fr-MG').format(n);
}
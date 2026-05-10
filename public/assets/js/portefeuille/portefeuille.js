/**
 * Script pour gestion du portefeuille
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérification de code en temps réel
    const codeInput = document.getElementById('code');
    const verifyBtn = document.getElementById('verify-code-btn');
    const codeStatusDiv = document.getElementById('code-status');

    if (codeInput) {
        codeInput.addEventListener('input', debounce(verifyCodeLive, 500));
    }

    // Copier un code dans le presse-papiers
    const copyButtons = document.querySelectorAll('.btn-copy-code');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', copyToClipboard);
    });

    // Fermer les alerts
    const closeButtons = document.querySelectorAll('.alert-close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    // Sélection du moyen de paiement
    const paymentMethods = document.querySelectorAll('input[name="moyen_paiement"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', updatePaymentForm);
    });

    // Validation du formulaire d'achat
    const buyForm = document.getElementById('buy-form');
    if (buyForm) {
        buyForm.addEventListener('submit', validateBuyForm);
    }

    // Validation du formulaire de validation
    const validateForm = document.getElementById('validate-form');
    if (validateForm) {
        validateForm.addEventListener('submit', validateValidateForm);
    }

    // Format du montant en temps réel
    const montantInput = document.getElementById('montant');
    if (montantInput) {
        montantInput.addEventListener('input', formatMontant);
    }
});

/**
 * Débounce function for delayed execution
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Vérification de code en temps réel
 */
function verifyCodeLive() {
    const code = document.getElementById('code').value.trim();

    if (!code || code.length < 5) {
        clearCodeStatus();
        return;
    }

    fetch('<?= site_url('/code/verifier') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'code=' + encodeURIComponent(code)
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('code-status');
        if (statusDiv) {
            if (data.valid) {
                statusDiv.innerHTML = `
                    <div class="alert alert-success">
                        ✓ Code valide - Montant: <strong>${data.montant} Ar</strong>
                    </div>
                `;
                document.getElementById('code').classList.add('is-valid');
            } else {
                statusDiv.innerHTML = `
                    <div class="alert alert-danger">
                        ✗ ${data.message}
                    </div>
                `;
                document.getElementById('code').classList.add('is-invalid');
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

/**
 * Effacer l'état du code
 */
function clearCodeStatus() {
    const statusDiv = document.getElementById('code-status');
    if (statusDiv) {
        statusDiv.innerHTML = '';
    }
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.classList.remove('is-valid', 'is-invalid');
    }
}

/**
 * Copier un code dans le presse-papiers
 */
function copyToClipboard(e) {
    e.preventDefault();
    const codeText = this.dataset.code || this.closest('.code-display').textContent.trim();

    navigator.clipboard.writeText(codeText).then(() => {
        const originalText = this.textContent;
        this.textContent = 'Copié!';
        setTimeout(() => {
            this.textContent = originalText;
        }, 2000);
    }).catch(err => {
        alert('Erreur lors de la copie');
        console.error(err);
    });
}

/**
 * Mise à jour du formulaire selon le moyen de paiement sélectionné
 */
function updatePaymentForm(e) {
    const method = e.target.value;
    const mobilePaymentFields = document.getElementById('mobile-payment-fields');
    const cardPaymentFields = document.getElementById('card-payment-fields');

    if (['mvola', 'airtel_money', 'orange_money'].includes(method)) {
        if (mobilePaymentFields) mobilePaymentFields.style.display = 'block';
        if (cardPaymentFields) cardPaymentFields.style.display = 'none';
    } else if (method === 'carte_bancaire') {
        if (mobilePaymentFields) mobilePaymentFields.style.display = 'none';
        if (cardPaymentFields) cardPaymentFields.style.display = 'block';
    }
}

/**
 * Validation du formulaire d'achat
 */
function validateBuyForm(e) {
    const montant = document.getElementById('montant').value;
    const moyenPaiement = document.querySelector('input[name="moyen_paiement"]:checked');

    if (!montant || montant <= 0) {
        e.preventDefault();
        showAlert('danger', 'Veuillez entrer un montant valide');
        return false;
    }

    if (!moyenPaiement) {
        e.preventDefault();
        showAlert('danger', 'Veuillez choisir un moyen de paiement');
        return false;
    }

    return true;
}

/**
 * Validation du formulaire de validation
 */
function validateValidateForm(e) {
    const code = document.getElementById('code').value.trim();

    if (!code) {
        e.preventDefault();
        showAlert('danger', 'Veuillez entrer votre code');
        return false;
    }

    if (code.length < 5) {
        e.preventDefault();
        showAlert('danger', 'Le code semble invalide');
        return false;
    }

    return true;
}

/**
 * Format montant avec séparateurs
 */
function formatMontant(e) {
    const value = e.target.value;
    const numericValue = value.replace(/[^0-9.]/g, '');
    e.target.value = numericValue;

    // Afficher montant formaté
    const montantInfo = document.getElementById('montant-info');
    if (montantInfo && numericValue) {
        const formatted = new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'MGA',
            minimumFractionDigits: 0
        }).format(numericValue);

        montantInfo.textContent = formatted;
    }
}

/**
 * Afficher une alerte
 */
function showAlert(type, message) {
    const container = document.getElementById('alerts-container');
    if (!container) return;

    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        ${message}
        <span class="alert-close" onclick="this.parentElement.remove()">×</span>
    `;

    container.appendChild(alert);

    // Effacer après 5 secondes
    if (type !== 'danger') {
        setTimeout(() => alert.remove(), 5000);
    }
}

/**
 * Afficher le loading
 */
function showLoading(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.classList.add('loading');
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';
    }
}

/**
 * Cacher le loading
 */
function hideLoading(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.classList.remove('loading');
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }
}
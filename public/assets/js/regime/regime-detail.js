document.addEventListener('DOMContentLoaded', () => {

    const durationBtns = document.querySelectorAll('.duration-btn');
    const selectedDuration = document.getElementById('selectedDuration');
    const buyBtn = document.getElementById('buyBtn');
    const prixTotal = document.getElementById('prixTotal');
    const durationDays = document.getElementById('durationDays');
    const remiseRow = document.getElementById('remiseRow');
    const montantRemise = document.getElementById('montantRemise');
    const errorMessage = document.getElementById('errorMessage');

    const regimeId = window.location.pathname.split('/')[2];
    let selectedJours = null;

    durationBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            durationBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            selectedJours = parseInt(btn.dataset.jours);
            durationDays.textContent = selectedJours;
            selectedDuration.style.display = 'block';

            calculatePrice(selectedJours);
        });
    });

    function calculatePrice(jours) {
        fetch(`/regime/${regimeId}/calculate-price`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `duree_jours=${jours}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                prixTotal.textContent = data.prix_total + '€';

                if (data.remise) {
                    remiseRow.style.display = 'flex';
                    montantRemise.textContent = '-' + data.montant_remise + '€';
                } else {
                    remiseRow.style.display = 'none';
                }

                buyBtn.disabled = false;
            } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
                buyBtn.disabled = true;
            }
        })
        .catch(err => {
            errorMessage.textContent = 'Erreur lors du calcul du prix';
            errorMessage.style.display = 'block';
            buyBtn.disabled = true;
        });
    }

    buyBtn.addEventListener('click', () => {
        if (!selectedJours) {
            errorMessage.textContent = 'Veuillez choisir une durée';
            errorMessage.style.display = 'block';
            return;
        }

        const poidsInitial = parseFloat(document.querySelector('[data-poids-initial]')?.dataset.poidsInitial || 0);
        const poidsCible = parseFloat(document.querySelector('[data-poids-cible]')?.dataset.poidsCible || 0);
        const prix = parseFloat(prixTotal.textContent);

        if (!poidsInitial || !poidsCible) {
            errorMessage.textContent = 'Informations de poids manquantes. Complétez votre profil.';
            errorMessage.style.display = 'block';
            return;
        }

        fetch(`/regime/${regimeId}/subscribe`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `duree_jours=${selectedJours}&poids_initial=${poidsInitial}&poids_cible=${poidsCible}&prix_total=${prix}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/dashboard';
            } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        })
        .catch(err => {
            errorMessage.textContent = 'Erreur lors de l\'achat';
            errorMessage.style.display = 'block';
        });
    });

});
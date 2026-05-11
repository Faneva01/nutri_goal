document.addEventListener('DOMContentLoaded', () => {

    const durationBtns = document.querySelectorAll('.duration-btn');
    const selectedDuration = document.getElementById('selectedDuration');
    const buyBtn = document.getElementById('buyBtn');
    const prixTotal = document.getElementById('prixTotal');
    const durationDays = document.getElementById('durationDays');
    const remiseRow = document.getElementById('remiseRow');
    const montantRemise = document.getElementById('montantRemise');
    const errorMessage = document.getElementById('errorMessage');

    const regimeId = window.location.pathname.split('/').pop();
    let selectedJours = null;

    durationBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            durationBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            selectedJours = parseInt(btn.dataset.jours);
            if (durationDays) durationDays.textContent = selectedJours;
            if (selectedDuration) selectedDuration.style.display = 'block';

            calculatePrice(selectedJours);
        });
    });

    function calculatePrice(jours) {
        fetch(`/regimes/${regimeId}/calculate-price`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `duree_jours=${jours}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                prixTotal.textContent = data.prix_total.toLocaleString() + ' Ar';

                if (data.remise && remiseRow) {
                    remiseRow.style.display = 'flex';
                    if (montantRemise) montantRemise.textContent = '-' + data.montant_remise.toLocaleString() + ' Ar';
                } else if (remiseRow) {
                    remiseRow.style.display = 'none';
                }

                buyBtn.disabled = false;
            } else if (errorMessage) {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
                buyBtn.disabled = true;
            }
        })
        .catch(err => {
            if (errorMessage) {
                errorMessage.textContent = 'Erreur lors du calcul du prix';
                errorMessage.style.display = 'block';
            }
            buyBtn.disabled = true;
        });
    }

    buyBtn.addEventListener('click', () => {
        if (!selectedJours) {
            if (errorMessage) {
                errorMessage.textContent = 'Veuillez choisir une durée';
                errorMessage.style.display = 'block';
            }
            return;
        }

        // Poids initial et cible peuvent être récupérés via le DOM si on les injecte dans la vue
        // ou gérés côté serveur. Dans recommendation_page c'est géré via hidden inputs.
        // Ici on va les envoyer, mais le serveur devrait valider.
        
        fetch(`/regimes/${regimeId}/subscribe`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `duree_jours=${selectedJours}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => window.location.href = '/dashboard', 1500);
            } else if (errorMessage) {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
                showToast('error', data.message);
            }
        })
        .catch(err => {
            if (errorMessage) {
                errorMessage.textContent = 'Erreur lors de l\'achat';
                errorMessage.style.display = 'block';
            }
            showToast('error', 'Erreur réseau');
        });
    });

});
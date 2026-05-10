/**
 * Admin Statistics: Types d'Utilisateurs (Simple, Gold, Premium)
 * Affiche la répartition des types d'abonnement
 */

let chartTypeUtilisateurs = null;

document.addEventListener('DOMContentLoaded', function() {
    loadChartTypeUtilisateurs();
    loadDetailedStats();
});

/**
 * Charge le graphique des types d'utilisateurs
 */
function loadChartTypeUtilisateurs() {
    const chartContainer = document.getElementById('chartTypeUtilisateurs');
    if (!chartContainer) {
        console.error('Élément chartTypeUtilisateurs introuvable');
        return;
    }

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            rendreChart(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique:', error);
        });
}

/**
 * Rend le graphique en camembert
 */
function rendreChart(data) {
    const ctx = document.getElementById('chartTypeUtilisateurs').getContext('2d');

    if (chartTypeUtilisateurs) {
        chartTypeUtilisateurs.destroy();
    }

    chartTypeUtilisateurs = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 15,
                        usePointStyle: true,
                        boxWidth: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Charge les statistiques détaillées
 */
function loadDetailedStats() {
    const detailedStatsContainer = document.getElementById('detailedStats');
    if (!detailedStatsContainer) return;

    const apiUrl = detailedStatsContainer.getAttribute('data-url');
    if (!apiUrl) return;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            displayDetailedStats(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des stats détaillées:', error);
        });
}

/**
 * Affiche les statistiques détaillées
 */
function displayDetailedStats(data) {
    let html = '<div class="stats-summary">';

    for (const [type, stats] of Object.entries(data)) {
        let badgeClass = 'info';
        let icon = 'fa-user';

        if (type === 'gold') {
            badgeClass = 'warning';
            icon = 'fa-crown';
        } else if (type === 'premium') {
            badgeClass = 'danger';
            icon = 'fa-gem';
        }

        html += `
            <div class="summary-item">
                <div class="summary-icon" style="font-size: 24px; margin-bottom: 10px; color: #3498db;">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="summary-label">${type.toUpperCase()}</div>
                <div class="summary-value">${stats.count} utilisateurs</div>
                <div class="summary-trend">${stats.percentage}% de la base</div>
                <div class="summary-stats" style="margin-top: 10px; font-size: 12px; color: #7f8c8d;">
                    <div>Actifs: <strong>${stats.active}</strong></div>
                    <div>Inactifs: <strong>${stats.inactive}</strong></div>
                    <div>Revenu: <strong>${new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'MGA'
                    }).format(stats.revenue)}</strong></div>
                </div>
            </div>
        `;
    }

    html += '</div>';
    detailedStatsContainer.innerHTML = html;
}

/**
 * Exporte les données
 */
function exportStats() {
    if (!chartTypeUtilisateurs) return;

    let csv = 'Type,Nombre,Pourcentage\n';

    const labels = chartTypeUtilisateurs.data.labels;
    const data = chartTypeUtilisateurs.data.datasets[0].data;
    const total = data.reduce((a, b) => a + b, 0);

    for (let i = 0; i < labels.length; i++) {
        const percentage = ((data[i] / total) * 100).toFixed(1);
        csv += `${labels[i]},${data[i]},${percentage}%\n`;
    }

    downloadCSV(csv, 'statistiques-types-utilisateurs.csv');
}

/**
 * Télécharge un fichier CSV
 */
function downloadCSV(csv, filename) {
    const link = document.createElement('a');
    const blob = new Blob([csv], { type: 'text/csv' });
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

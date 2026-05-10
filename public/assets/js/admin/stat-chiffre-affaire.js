/**
 * Admin Statistics: Chiffre d'Affaires
 * Affiche l'évolution des revenus et la répartition par méthode de paiement
 */

let chartChiffreAffaire = null;
let chartPaymentMethods = null;

document.addEventListener('DOMContentLoaded', function() {
    loadChartChiffreAffaire();
    loadChartPaymentMethods();
    loadGlobalStats();
});

/**
 * Charge le graphique du chiffre d'affaires
 */
function loadChartChiffreAffaire() {
    const chartContainer = document.getElementById('chartChiffreAffaire');
    if (!chartContainer) return;

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            rendreChartRevenue(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique chiffre d\'affaires:', error);
        });
}

/**
 * Rend le graphique du chiffre d'affaires
 */
function rendreChartRevenue(data) {
    const ctx = document.getElementById('chartChiffreAffaire').getContext('2d');

    if (chartChiffreAffaire) {
        chartChiffreAffaire.destroy();
    }

    chartChiffreAffaire = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'CA: ' + new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'MGA'
                            }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                notation: 'compact',
                                style: 'currency',
                                currency: 'MGA'
                            }).format(value);
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Charge le graphique des méthodes de paiement
 */
function loadChartPaymentMethods() {
    const chartContainer = document.getElementById('chartPaymentMethods');
    if (!chartContainer) return;

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            rendreChartPaymentMethods(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique des paiements:', error);
        });
}

/**
 * Rend le graphique des méthodes de paiement
 */
function rendreChartPaymentMethods(data) {
    const ctx = document.getElementById('chartPaymentMethods');
    if (!ctx) return;

    const context = ctx.getContext('2d');

    if (chartPaymentMethods) {
        chartPaymentMethods.destroy();
    }

    chartPaymentMethods = new Chart(context, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'MGA'
                            }).format(context.parsed.x);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                notation: 'compact',
                                style: 'currency',
                                currency: 'MGA'
                            }).format(value);
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

/**
 * Charge les statistiques globales
 */
function loadGlobalStats() {
    const statsContainer = document.getElementById('globalStats');
    if (!statsContainer) return;

    const apiUrl = statsContainer.getAttribute('data-url');
    if (!apiUrl) return;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            displayGlobalStats(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des stats globales:', error);
        });
}

/**
 * Affiche les statistiques globales
 */
function displayGlobalStats(data) {
    let html = '<div class="stats-summary">';

    const stats = [
        {
            label: 'Chiffre d\'Affaires Total',
            value: new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA' }).format(data.total_revenue),
            icon: 'fa-chart-line'
        },
        {
            label: 'Aujourd\'hui',
            value: new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA' }).format(data.revenue_today),
            icon: 'fa-calendar-day'
        },
        {
            label: 'This Month',
            value: new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA' }).format(data.revenue_this_month),
            icon: 'fa-calendar'
        },
        {
            label: 'Montant Moyen',
            value: new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'MGA' }).format(data.average_transaction),
            icon: 'fa-average'
        },
        {
            label: 'Transactions',
            value: data.total_transactions,
            icon: 'fa-exchange-alt'
        },
        {
            label: 'Croissance',
            value: data.growth + '%',
            icon: 'fa-arrow-up'
        }
    ];

    stats.forEach(stat => {
        html += `
            <div class="summary-item">
                <div class="summary-icon" style="font-size: 20px; color: #27ae60;">
                    <i class="fas ${stat.icon}"></i>
                </div>
                <div class="summary-label">${stat.label}</div>
                <div class="summary-value">${stat.value}</div>
            </div>
        `;
    });

    html += '</div>';
    document.getElementById('globalStats').innerHTML = html;
}

/**
 * Exporte les données
 */
function exportRevenueData() {
    if (!chartChiffreAffaire) return;

    let csv = 'Date,Chiffre d\'Affaire\n';

    const labels = chartChiffreAffaire.data.labels;
    const data = chartChiffreAffaire.data.datasets[0].data;

    for (let i = 0; i < labels.length; i++) {
        csv += `${labels[i]},${data[i]}\n`;
    }

    downloadCSV(csv, 'statistiques-chiffre-affaires.csv');
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

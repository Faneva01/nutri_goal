/**
 * Admin Statistics: Régimes et Plats Populaires
 * Affiche les régimes populaires et les plats les plus consommés
 */

let chartRegimes = null;
let chartDishes = null;

document.addEventListener('DOMContentLoaded', function() {
    loadChartRegimes();
    loadChartDishes();
    loadDetailedStats();
});

/**
 * Charge le graphique des régimes populaires
 */
function loadChartRegimes() {
    const chartContainer = document.getElementById('chartRegimes');
    if (!chartContainer) return;

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            rendreChartRegimes(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique régimes:', error);
        });
}

/**
 * Rend le graphique des régimes
 */
function rendreChartRegimes(data) {
    const ctx = document.getElementById('chartRegimes').getContext('2d');

    if (chartRegimes) {
        chartRegimes.destroy();
    }

    chartRegimes = new Chart(ctx, {
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
                            return context.parsed.x + ' utilisateurs';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
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
 * Charge le graphique des plats populaires
 */
function loadChartDishes() {
    const chartContainer = document.getElementById('chartDishes');
    if (!chartContainer) return;

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            rendreChartDishes(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique plats:', error);
        });
}

/**
 * Rend le graphique des plats
 */
function rendreChartDishes(data) {
    const ctx = document.getElementById('chartDishes');
    if (!ctx) return;

    const context = ctx.getContext('2d');

    if (chartDishes) {
        chartDishes.destroy();
    }

    chartDishes = new Chart(context, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' consommations';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
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
 * Charge les statistiques détaillées
 */
function loadDetailedStats() {
    const container = document.getElementById('detailedStatsRegime');
    if (!container) return;

    const apiUrl = container.getAttribute('data-url');
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
 * Affiche les statistiques détaillées des régimes
 */
function displayDetailedStats(data) {
    let html = '';

    // Régimes
    if (data.regimes && data.regimes.length > 0) {
        html += `
            <div class="stats-section">
                <h4 class="section-title">
                    <i class="fas fa-leaf"></i> Régimes Populaires
                </h4>
                <div class="stats-table-container">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Régime</th>
                                <th>Utilisateurs</th>
                                <th>Pourcentage</th>
                                <th>Note</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        data.regimes.forEach(regime => {
            html += `
                            <tr>
                                <td>${regime.name}</td>
                                <td>${regime.users}</td>
                                <td>${regime.percentage}%</td>
                                <td>
                                    <div class="rating">
                                        ${'⭐'.repeat(Math.floor(regime.rating))}
                                        <span class="rating-value">${regime.rating}/5</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="stats-badge stats-badge-${regime.status === 'Très Populaire' ? 'success' : regime.status === 'Populaire' ? 'warning' : 'info'}">
                                        ${regime.status}
                                    </span>
                                </td>
                            </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    // Plats populaires
    if (data.popular_dishes && data.popular_dishes.length > 0) {
        html += `
            <div class="stats-section" style="margin-top: 30px;">
                <h4 class="section-title">
                    <i class="fas fa-utensils"></i> Plats Populaires
                </h4>
                <div class="stats-table-container">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Consommations</th>
                                <th>Régime</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        data.popular_dishes.forEach(dish => {
            html += `
                            <tr>
                                <td><strong>${dish.name}</strong></td>
                                <td>${dish.count}</td>
                                <td><span class="stats-badge stats-badge-info">${dish.regime}</span></td>
                            </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    document.getElementById('detailedStatsRegime').innerHTML = html;
}

/**
 * Exporte les données
 */
function exportRegimeData() {
    let csv = 'Régime,Utilisateurs,Pourcentage\n';

    if (chartRegimes && chartRegimes.data) {
        const labels = chartRegimes.data.labels;
        const data = chartRegimes.data.datasets[0].data;
        const total = data.reduce((a, b) => a + b, 0);

        for (let i = 0; i < labels.length; i++) {
            const percentage = ((data[i] / total) * 100).toFixed(1);
            csv += `${labels[i]},${data[i]},${percentage}%\n`;
        }
    }

    downloadCSV(csv, 'statistiques-regimes.csv');
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

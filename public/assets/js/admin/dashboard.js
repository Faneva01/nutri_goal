/**
 * Admin Dashboard Charts
 * Gère le chargement et l'affichage des graphiques sur le dashboard
 */

let charts = {};

document.addEventListener('DOMContentLoaded', function() {
    loadAllCharts();
});

/**
 * Charge tous les graphiques du dashboard
 */
function loadAllCharts() {
    loadUserStats();
    loadUserTypeStats();
    loadRevenueStats();
    loadRegimeStats();
}

/**
 * Charge le graphique de variation des utilisateurs
 */
function loadUserStats() {
    const chartElement = document.getElementById('chartUtilisateurs');
    if (!chartElement) return;

    const dataUrl = chartElement.getAttribute('data-url');

    fetch(dataUrl)
        .then(response => response.json())
        .then(data => {
            const ctx = chartElement.getContext('2d');
            
            if (charts.usuarios) {
                charts.usuarios.destroy();
            }

            charts.usuarios = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
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
        })
        .catch(error => console.error('Erreur lors du chargement des stats utilisateurs:', error));
}

/**
 * Charge le graphique de répartition des types d'utilisateurs
 */
function loadUserTypeStats() {
    const chartElement = document.getElementById('chartTypeUtilisateurs');
    if (!chartElement) return;

    const dataUrl = chartElement.getAttribute('data-url');

    fetch(dataUrl)
        .then(response => response.json())
        .then(data => {
            const ctx = chartElement.getContext('2d');
            
            if (charts.typeUtilisateurs) {
                charts.typeUtilisateurs.destroy();
            }

            charts.typeUtilisateurs = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Erreur lors du chargement des stats types:', error));
}

/**
 * Charge le graphique de chiffre d'affaires
 */
function loadRevenueStats() {
    const chartElement = document.getElementById('chartChiffreAffaire');
    if (!chartElement) return;

    const dataUrl = chartElement.getAttribute('data-url');

    fetch(dataUrl)
        .then(response => response.json())
        .then(data => {
            const ctx = chartElement.getContext('2d');
            
            if (charts.chiffreAffaire) {
                charts.chiffreAffaire.destroy();
            }

            charts.chiffreAffaire = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
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
        })
        .catch(error => console.error('Erreur lors du chargement des stats revenue:', error));
}

/**
 * Charge le graphique des régimes populaires
 */
function loadRegimeStats() {
    const chartElement = document.getElementById('chartRegimes');
    if (!chartElement) return;

    const dataUrl = chartElement.getAttribute('data-url');

    fetch(dataUrl)
        .then(response => response.json())
        .then(data => {
            const ctx = chartElement.getContext('2d');
            
            if (charts.regimes) {
                charts.regimes.destroy();
            }

            charts.regimes = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Erreur lors du chargement des stats régimes:', error));
}

/**
 * Rafraîchit tous les graphiques
 */
function refreshAllCharts() {
    Object.values(charts).forEach(chart => {
        if (chart) chart.destroy();
    });
    charts = {};
    loadAllCharts();
}

// Auto-refresh toutes les 5 minutes
setInterval(refreshAllCharts, 300000);

/**
 * Admin Statistics: Utilisateurs
 * Affiche l'évolution du nombre d'utilisateurs
 */

let chartUtilisateurs = null;

document.addEventListener('DOMContentLoaded', function() {
    loadChartUtilisateurs();
});

/**
 * Charge le graphique des utilisateurs
 */
function loadChartUtilisateurs() {
    const chartContainer = document.getElementById('chartUtilisateurs');
    if (!chartContainer) {
        console.error('Élément chartUtilisateurs introuvable');
        return;
    }

    const apiUrl = chartContainer.getAttribute('data-url');

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            rendreChart(data);
        })
        .catch(error => {
            console.error('Erreur lors du chargement du graphique:', error);
            showError('Erreur lors du chargement des données');
        });
}

/**
 * Rend le graphique des utilisateurs
 */
function rendreChart(data) {
    const ctx = document.getElementById('chartUtilisateurs').getContext('2d');

    // Détruire le graphique existant s'il y en a un
    if (chartUtilisateurs) {
        chartUtilisateurs.destroy();
    }

    chartUtilisateurs = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    borderColor: '#ddd',
                    borderWidth: 1,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: true
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    title: {
                        display: true,
                        text: 'Nombre d\'utilisateurs',
                        font: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Date',
                        font: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 750
            }
        }
    });
}

/**
 * Affiche un message d'erreur
 */
function showError(message) {
    console.error(message);
    // Vous pouvez ajouter une notification visuelle ici
}

/**
 * Exporte les données en CSV
 */
function exportToCSV() {
    if (!chartUtilisateurs || !chartUtilisateurs.data) return;

    let csv = 'Date,Nouveaux Utilisateurs,Utilisateurs Actifs\n';
    
    const labels = chartUtilisateurs.data.labels;
    const datasets = chartUtilisateurs.data.datasets;

    for (let i = 0; i < labels.length; i++) {
        const row = [
            labels[i],
            datasets[0].data[i] || 0,
            datasets[1].data[i] || 0
        ];
        csv += row.join(',') + '\n';
    }

    downloadCSV(csv, 'statistiques-utilisateurs.csv');
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

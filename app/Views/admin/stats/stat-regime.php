<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-leaf"></i> Régimes et Activités Populaires
            </h1>
            <p style="color: #95a5a6; margin-top: 5px;">Analyse des régimes les plus populaires et des plats les plus consommés</p>
        </div>
        <div class="stats-controls">
            <button class="stats-filter active" onclick="loadChartRegimes()">
                <i class="fas fa-sync"></i> Rafraîchir
            </button>
            <button class="stats-filter" onclick="exportRegimeData()">
                <i class="fas fa-download"></i> Export CSV
            </button>
            <a href="<?= base_url('/admin/dashboard') ?>" class="stats-filter">
                <i class="fas fa-arrow-left"></i> Tableau de Bord
            </a>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="admin-stats-grid">
        <!-- Régimes Chart -->
        <div class="stats-chart-card">
            <div class="stats-chart-header">
                <h3 class="stats-chart-title">
                    <i class="fas fa-chart-bar"></i> Régimes Populaires
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartRegimes" 
                    data-url="<?= base_url('/admin/api/stats/regime') ?>">
                </canvas>
            </div>
        </div>

        <!-- Dishes Chart -->
        <div class="stats-chart-card">
            <div class="stats-chart-header">
                <h3 class="stats-chart-title">
                    <i class="fas fa-utensils"></i> Activités Populaires
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartDishes" 
                    data-url="<?= base_url('/admin/api/stats/regime/dishes') ?>">
                </canvas>
            </div>
        </div>
    </div>

    {{-- Detailed Statistics --}}
    <div id="detailedStatsRegime" data-url="<?= base_url('/admin/api/stats/regime/detailed') ?>">
        {{-- Sera rempli par le JavaScript --}}
    </div>

    {{-- Quick Stats --}}
    <div class="stats-summary" style="margin-top: 20px;">
        <div class="summary-item">
            <div class="summary-label">Régime le Plus Populaire</div>
            <div class="summary-value">Équilibré</div>
            <div class="summary-trend">
                <i class="fas fa-users"></i> 215 utilisateurs (32.1%)
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Meilleure Note</div>
            <div class="summary-value">4.8/5</div>
            <div class="summary-trend">
                Régime Méditerranéen ⭐⭐⭐⭐⭐
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Plat le Plus Consommé</div>
            <div class="summary-value">Riz Gras</div>
            <div class="summary-trend">
                <i class="fas fa-fire"></i> 287 consommations
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Total Régimes</div>
            <div class="summary-value">6</div>
            <div class="summary-trend">
                Tous actifs et populaires
            </div>
        </div>
    </div>

    {{-- Insights --}}
    <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h3 style="margin-top: 0; color: #2c3e50;">
            <i class="fas fa-lightbulb"></i> Insights Nutritionnels
        </h3>
        <ul style="color: #555; line-height: 1.8;">
            <li><strong>Tendance Massive:</strong> 32.1% des utilisateurs optent pour un régime équilibré</li>
            <li><strong>Haute Satisfaction:</strong> Le régime méditerranéen affiche une note de 4.8/5</li>
            <li><strong>Plat Signature:</strong> Le Riz Gras domine avec 287 consommations</li>
            <li><strong>Diversification:</strong> Les utilisateurs sont distribués entre 6 régimes différents, pas de concentration excessive</li>
            <li><strong>Recommandation:</strong> Mettre l'accent sur le marketing du régime Méditerranéen</li>
        </ul>
    </div>

    {{-- Performance Comparison --}}
    <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h3 style="margin-top: 0; color: #2c3e50;">
            <i class="fas fa-columns"></i> Comparaison Complète
        </h3>
        <div style="overflow-x: auto;">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Type</th>
                        <th>Nombre</th>
                        <th>Pourcentage</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-star" style="color: #FFD700;"></i></td>
                        <td><strong>Très Populaire</strong></td>
                        <td>189 utilisateurs</td>
                        <td>28.2%</td>
                        <td>⭐⭐⭐⭐⭐ 4.8/5</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-star" style="color: #FFA500;"></i></td>
                        <td><strong>Populaire</strong></td>
                        <td>357 utilisateurs</td>
                        <td>53.3%</td>
                        <td>⭐⭐⭐⭐ ~4.4/5</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-star" style="color: #9B9B9B;"></i></td>
                        <td><strong>Modéré</strong></td>
                        <td>174 utilisateurs</td>
                        <td>26.0%</td>
                        <td>⭐⭐⭐ ~4.3/5</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

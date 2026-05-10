<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-users"></i> Types d'Utilisateurs
            </h1>
            <p style="color: #95a5a6; margin-top: 5px;">Répartition entre Simple et Gold</p>
        </div>
        <div class="stats-controls">
            <button class="stats-filter active" onclick="loadChartTypeUtilisateurs()">
                <i class="fas fa-sync"></i> Rafraîchir
            </button>
            <button class="stats-filter" onclick="exportStats()">
                <i class="fas fa-download"></i> Export CSV
            </button>
            <a href="<?= base_url('/admin/dashboard') ?>" class="stats-filter">
                <i class="fas fa-arrow-left"></i> Tableau de Bord
            </a>
        </div>
    </div>

    {{-- Main Chart --}}
    <div class="admin-stats-grid">
        <div class="stats-chart-card">
            <div class="stats-chart-header">
                <h3 class="stats-chart-title">
                    <i class="fas fa-pie-chart"></i> Répartition
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartTypeUtilisateurs" 
                    data-url="<?= base_url('/admin/api/stats/type-usuarios') ?>">
                </canvas>
            </div>
        </div>
    </div>

    {{-- Detailed Statistics --}}
    <div id="detailedStats" data-url="<?= base_url('/admin/api/stats/type-usuarios/detailed') ?>">
        {{-- Sera rempli par le JavaScript --}}
    </div>

    {{-- Comparison Table --}}
    <div class="stats-table-card" style="margin-top: 20px;">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Nombre</th>
                    <th>Pourcentage</th>
                    <th>Tarif Mensuel</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <i class="fas fa-user"></i> Simple
                    </td>
                    <td><strong>312</strong></td>
                    <td>50.2%</td>
                    <td>Gratuit</td>
                    <td><span class="stats-badge stats-badge-info">Accès Basique</span></td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-crown" style="color: #ffc107;"></i> Gold
                    </td>
                    <td><strong>187</strong></td>
                    <td>30.1%</td>
                    <td>5,990 Ar/mois</td>
                    <td><span class="stats-badge stats-badge-warning">Plans Personnalisés</span></td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-gem" style="color: #e83e8c;"></i> Premium
                    </td>
                    <td><strong>0</strong></td>
                    <td>0%</td>
                    <td>-</td>
                    <td><span class="stats-badge stats-badge-info">Non défini</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Insights --}}
    <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h3 style="margin-top: 0; color: #2c3e50;">
            <i class="fas fa-lightbulb"></i> Insights
        </h3>
        <ul style="color: #555; line-height: 1.8;">
            <li>La majorité des utilisateurs (50.2%) sont en abonnement Simple</li>
            <li>30.1% des utilisateurs ont souscrit à Gold avec des plans personnalisés</li>
            <li>La majorité des utilisateurs sont en abonnement Simple ou sans option Gold</li>
            <li>Potentiel d'upsell: les utilisateurs Simple peuvent être convertis à Gold</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>

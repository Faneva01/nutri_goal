<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-chart-line"></i> Statistiques Utilisateurs
            </h1>
            <p style="color: #95a5a6; margin-top: 5px;">Évolution du nombre d'utilisateurs sur les 30 derniers jours</p>
        </div>
        <div class="stats-controls">
            <button class="stats-filter active" onclick="loadChartUtilisateurs()">
                <i class="fas fa-sync"></i> Rafraîchir
            </button>
            <button class="stats-filter" onclick="exportToCSV()">
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
                    <i class="fas fa-line-chart"></i> Évolution Utilisateurs
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartUtilisateurs" 
                    data-url="<?= base_url('/admin/api/stats/usuarios') ?>">
                </canvas>
            </div>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="stats-summary" style="margin-bottom: 20px;">
        <div class="summary-item">
            <div class="summary-label">Utilisateurs Actifs Aujourd'hui</div>
            <div class="summary-value">+125</div>
            <div class="summary-trend">
                <i class="fas fa-arrow-up"></i> Tendance Positive
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Total 30 Jours</div>
            <div class="summary-value">+2,450</div>
            <div class="summary-trend">
                <i class="fas fa-arrow-up"></i> +28% vs période précédente
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Taux d'Activation</div>
            <div class="summary-value">87%</div>
            <div class="summary-trend">
                Utilisateurs ayant complété le profil
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Taux de Rétention</div>
            <div class="summary-value">75%</div>
            <div class="summary-trend">
                Utilisateurs revenant chaque semaine
            </div>
        </div>
    </div>

    {{-- Detailed Table --}}
    <div class="stats-table-card">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nouveaux Utilisateurs</th>
                    <th>Utilisateurs Actifs</th>
                    <th>Variation</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>09 Mai 2026</td>
                    <td><strong>12</strong></td>
                    <td>347</td>
                    <td>+5 (+2%)</td>
                    <td><span class="stats-badge stats-badge-success">Croissance</span></td>
                </tr>
                <tr>
                    <td>08 Mai 2026</td>
                    <td><strong>19</strong></td>
                    <td>342</td>
                    <td>+8 (+2%)</td>
                    <td><span class="stats-badge stats-badge-success">Croissance</span></td>
                </tr>
                <tr>
                    <td>07 Mai 2026</td>
                    <td><strong>15</strong></td>
                    <td>334</td>
                    <td>+3 (+1%)</td>
                    <td><span class="stats-badge stats-badge-warning">Stable</span></td>
                </tr>
                <tr>
                    <td>06 Mai 2026</td>
                    <td><strong>8</strong></td>
                    <td>331</td>
                    <td>-5 (-2%)</td>
                    <td><span class="stats-badge stats-badge-danger">Baisse</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="<?= base_url('/assets/js/admin/stat-usuarios.js') ?>"></script>

<?= $this->endSection() ?>

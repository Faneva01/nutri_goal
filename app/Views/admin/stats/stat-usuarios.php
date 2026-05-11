<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-chart-line"></i> Statistiques Utilisateurs
            </h1>
            <p style="color: #9a938e; margin-top: 5px;">Analyse de la croissance et de l'engagement des utilisateurs</p>
        </div>
        <div class="stats-controls">
            <button class="stats-filter active" onclick="window.location.reload()">
                <i class="fas fa-sync"></i> Rafraîchir
            </button>
            <a href="<?= base_url('/admin/dashboard') ?>" class="stats-filter">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Main Chart -->
    <div class="stats-chart-card">
        <div class="chart-header">
            <h3 class="chart-title">
                <i class="fas fa-line-chart"></i> Évolution Utilisateurs (30j)
            </h3>
        </div>
        <div class="chart-container" style="height: 350px;">
            <canvas id="chartUtilisateurs" data-url="<?= base_url('/admin/api/stats/usuarios') ?>"></canvas>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats-summary">
        <div class="summary-item">
            <div class="summary-label">Total Utilisateurs</div>
            <div class="summary-value"><?= number_format($summary['total']) ?></div>
            <div class="summary-trend">Inscriptions cumulées</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Membres Gold</div>
            <div class="summary-value"><?= number_format($summary['gold']) ?></div>
            <div class="summary-trend">Utilisateurs premium</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Taux d'Activation</div>
            <div class="summary-value"><?= $summary['activation'] ?>%</div>
            <div class="summary-trend">Conversion vers Gold</div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="stats-table-card">
        <div class="panel-header">
            <h2>Historique des 10 derniers jours</h2>
        </div>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nouveaux Utilisateurs</th>
                    <th>Abonnements Actifs</th>
                    <th>Variation Active</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tableData as $row): ?>
                <tr>
                    <td><?= $row['date'] ?></td>
                    <td><strong><?= $row['new'] ?></strong></td>
                    <td><?= $row['active'] ?></td>
                    <td><?= $row['diff'] ?></td>
                    <td>
                        <span class="stats-badge stats-badge-<?= $row['status'] == 'Croissance' ? 'success' : 'danger' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= js_url('admin/stat-usuarios.js') ?>"></script>
<?= $this->endSection() ?>

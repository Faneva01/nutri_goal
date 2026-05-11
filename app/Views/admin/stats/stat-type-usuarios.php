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

    <!-- Main Chart -->
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

    <!-- Detailed Statistics -->
    <div id="detailedStats" data-url="<?= base_url('/admin/api/stats/type-usuarios/detailed') ?>">
        <!-- Sera rempli par le JavaScript -->
    </div>

    <!-- Comparison Table -->
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
                    <td><strong><?= $stats['simple']['count'] ?></strong></td>
                    <td><?= $stats['simple']['percent'] ?>%</td>
                    <td>Gratuit</td>
                    <td><span class="stats-badge stats-badge-info">Accès Basique</span></td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-crown" style="color: #ffc107;"></i> Gold
                    </td>
                    <td><strong><?= $stats['gold']['count'] ?></strong></td>
                    <td><?= $stats['gold']['percent'] ?>%</td>
                    <td>50 000 Ar (Unique)</td>
                    <td><span class="stats-badge stats-badge-warning">Option Gold</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Insights -->
    <div style="background: white; padding: 24px; border-radius: 16px; margin-top: 24px; border: 1px solid #f0ece8; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2d2926; font-size: 18px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-lightbulb" style="color: var(--admin-accent);"></i> Insights Plateforme
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
            <div style="padding: 16px; background: #faf7f5; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #fcfaf8;">
                <i class="fas fa-info-circle" style="color: #3498db; margin-top: 3px;"></i>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">La majorité des utilisateurs (<strong><?= $stats['simple']['percent'] ?>%</strong>) utilisent actuellement la version gratuite.</p>
            </div>
            <div style="padding: 16px; background: #FEF0EC; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #fcece8;">
                <i class="fas fa-crown" style="color: var(--admin-primary); margin-top: 3px;"></i>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;"><strong><?= $stats['gold']['percent'] ?>%</strong> des membres ont activé l'Option Gold.</p>
            </div>
            <div style="padding: 16px; background: #f0fff4; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #dcfce7;">
                <i class="fas fa-users" style="color: #27ae60; margin-top: 3px;"></i>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">Vous gérez actuellement une communauté de <strong><?= $total ?></strong> utilisateurs actifs.</p>
            </div>
            <?php if($stats['simple']['percent'] > 0): ?>
                <div style="padding: 16px; background: #fffaf0; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #fef3c7;">
                    <i class="fas fa-rocket" style="color: #f39c12; margin-top: 3px;"></i>
                    <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;"><strong>Opportunité :</strong> Les <?= $stats['simple']['count'] ?> utilisateurs Simple sont des prospects directs pour l'Option Gold.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= js_url('admin/stat-type-usuarios.js') ?>"></script>
<?= $this->endSection() ?>

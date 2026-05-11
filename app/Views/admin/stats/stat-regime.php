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

    <!-- Charts Grid -->
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

    <!-- Detailed Statistics -->
    <div id="detailedStatsRegime" data-url="<?= base_url('/admin/api/stats/regime/detailed') ?>">
        <!-- Sera rempli par le JavaScript -->
    </div>

    <!-- Quick Stats -->
    <div class="stats-summary" style="margin-top: 20px;">
        <div class="summary-item">
            <div class="summary-label">Régime le Plus Populaire</div>
            <div class="summary-value"><?= $popularRegime['nom'] ?? 'Aucun' ?></div>
            <div class="summary-trend">
                <i class="fas fa-users"></i> <?= $popularRegime['count'] ?? 0 ?> utilisateurs
                <?php if($totalSubscribers > 0 && isset($popularRegime['count'])): ?>
                    (<?= round(($popularRegime['count'] / $totalSubscribers) * 100, 1) ?>%)
                <?php endif; ?>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Activité la Plus Fréquente</div>
            <div class="summary-value"><?= $popularActivity['nom'] ?? 'Aucune' ?></div>
            <div class="summary-trend">
                <i class="fas fa-running"></i> <?= $popularActivity['count'] ?? 0 ?> apparitions
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Total Régimes</div>
            <div class="summary-value"><?= $totalRegimes ?></div>
            <div class="summary-trend">
                Catalogue complet
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Total Abonnements</div>
            <div class="summary-value"><?= $totalSubscribers ?></div>
            <div class="summary-trend">
                Programmes en cours
            </div>
        </div>
    </div>

    <!-- Insights -->
    <div style="background: white; padding: 24px; border-radius: 16px; margin-top: 24px; border: 1px solid #f0ece8; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2d2926; font-size: 18px; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-lightbulb" style="color: var(--admin-accent);"></i> Insights Nutritionnels
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
            <?php if(isset($popularRegime)): ?>
                <div style="padding: 16px; background: #faf7f5; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #fcfaf8;">
                    <i class="fas fa-star" style="color: #f1c40f; margin-top: 3px;"></i>
                    <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">Le régime <strong><?= $popularRegime['nom'] ?></strong> est actuellement le plus plébiscité par les utilisateurs.</p>
                </div>
            <?php endif; ?>
            <?php if(isset($popularActivity)): ?>
                <div style="padding: 16px; background: #FEF0EC; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #fcece8;">
                    <i class="fas fa-heart" style="color: var(--admin-primary); margin-top: 3px;"></i>
                    <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">L'activité <strong><?= $popularActivity['nom'] ?></strong> est la composante la plus fréquente de nos programmes.</p>
                </div>
            <?php endif; ?>
            <div style="padding: 16px; background: #f0fff4; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px; border: 1px solid #dcfce7;">
                <i class="fas fa-check-circle" style="color: #27ae60; margin-top: 3px;"></i>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">Nous proposons <strong><?= $totalRegimes ?></strong> approches différentes pour <strong><?= $totalSubscribers ?></strong> suivis en cours.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= js_url('admin/stat-regime.js') ?>"></script>
<?= $this->endSection() ?>

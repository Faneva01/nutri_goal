<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-dashboard-container">
    <!-- Header -->
    <div class="admin-dashboard-header">
        <div class="admin-header-content">
            <h1 class="admin-dashboard-title">
                <i class="fas fa-tachometer-alt"></i> Tableau de Bord Administrateur
            </h1>
            <p class="admin-dashboard-subtitle">Bienvenue <?= htmlspecialchars(session()->get('admin_name') ?? 'Administrateur') ?></p>
        </div>
        <div class="admin-header-actions">
            <a href="<?= base_url('/admin/stats/usuarios') ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-refresh"></i> Rafraîchir
            </a>
            <a href="<?= base_url('/admin/auth/logout') ?>" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Quick Stats -->
    <div class="admin-quick-stats">
        <div class="stat-card stat-card-users">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" id="totalUsers"><?= $stats['total_users'] ?? 0 ?></h3>
                <p class="stat-label">Utilisateurs Total</p>
            </div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> +<?= $stats['new_users_today'] ?? 0 ?> aujourd'hui
            </div>
        </div>

        <div class="stat-card stat-card-revenue">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?= number_format($stats['total_revenue'] ?? 0, 2) ?> Ar</h3>
                <p class="stat-label">Chiffre d'Affaire</p>
            </div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> +<?= $stats['revenue_today'] ?? 0 ?> Ar
            </div>
        </div>

        <div class="stat-card stat-card-codes">
            <div class="stat-icon">
                <i class="fas fa-barcode"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?= $stats['total_codes'] ?? 0 ?></h3>
                <p class="stat-label">Codes Portefeuille</p>
            </div>
            <div class="stat-trend">
                <?= $stats['codes_used'] ?? 0 ?> utilisés
            </div>
        </div>

        <div class="stat-card stat-card-regimes">
            <div class="stat-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value"><?= $stats['total_regimes'] ?? 0 ?></h3>
                <p class="stat-label">Régimes Actifs</p>
            </div>
            <div class="stat-trend">
                Pour <?= $stats['regimes_users'] ?? 0 ?> utilisateurs
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="admin-charts-grid">
        <!-- Chart: User Growth -->
        <div class="admin-chart-card">
            <div class="chart-header">
                <h4 class="chart-title">
                    <i class="fas fa-chart-line"></i> Évolution des Utilisateurs
                </h4>
                <a href="<?= base_url('/admin/stats/usuarios') ?>" class="chart-link">Voir détails →</a>
            </div>
            <div class="chart-container">
                <canvas id="chartUtilisateurs" data-url="<?= base_url('/admin/api/stats/usuarios') ?>"></canvas>
            </div>
            <div class="chart-footer">
                <small class="text-muted">Évolution sur les 30 derniers jours</small>
            </div>
        </div>

        <!-- Chart: User Types -->
        <div class="admin-chart-card">
            <div class="chart-header">
                <h4 class="chart-title">
                    <i class="fas fa-pie-chart"></i> Répartition Types Utilisateurs
                </h4>
                <a href="<?= base_url('/admin/stats/type-usuarios') ?>" class="chart-link">Voir détails →</a>
            </div>
            <div class="chart-container">
                <canvas id="chartTypeUtilisateurs" data-url="<?= base_url('/admin/api/stats/type-usuarios') ?>"></canvas>
            </div>
            <div class="chart-footer">
                <small class="text-muted">Simple vs Gold</small>
            </div>
        </div>

        <!-- Chart: Revenue -->
        <div class="admin-chart-card">
            <div class="chart-header">
                <h4 class="chart-title">
                    <i class="fas fa-bar-chart"></i> Chiffre d'Affaires
                </h4>
                <a href="<?= base_url('/admin/stats/chiffre-affaire') ?>" class="chart-link">Voir détails →</a>
            </div>
            <div class="chart-container">
                <canvas id="chartChiffreAffaire" data-url="<?= base_url('/admin/api/stats/chiffre-affaire') ?>"></canvas>
            </div>
            <div class="chart-footer">
                <small class="text-muted">Revenus par méthode de paiement</small>
            </div>
        </div>

        <!-- Chart: Popular Regimes -->
        <div class="admin-chart-card">
            <div class="chart-header">
                <h4 class="chart-title">
                    <i class="fas fa-fire"></i> Régimes Populaires
                </h4>
                <a href="<?= base_url('/admin/stats/regime') ?>" class="chart-link">Voir détails →</a>
            </div>
            <div class="chart-container">
                <canvas id="chartRegimes" data-url="<?= base_url('/admin/api/stats/regime') ?>"></canvas>
            </div>
            <div class="chart-footer">
                <small class="text-muted">Plats les plus consommés</small>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="admin-recent-activity">
        <div class="activity-header">
            <h4 class="activity-title">
                <i class="fas fa-history"></i> Activité Récente
            </h4>
        </div>
        <div class="activity-list">
            <?php if (!empty($recent_activity)): ?>
                <?php foreach ($recent_activity as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon" style="background-color: <?= $activity['color'] ?>;">
                            <i class="<?= $activity['icon'] ?>"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-message"><?= htmlspecialchars($activity['message']) ?></p>
                            <small class="activity-time"><?= $activity['time'] ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-activity">
                    <p class="text-muted">Aucune activité récente</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

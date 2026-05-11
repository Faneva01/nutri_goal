<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-money-bill-wave"></i> Chiffre d'Affaires
            </h1>
            <p style="color: #9a938e; margin-top: 5px;">Analyse des revenus générés par les abonnements et recharges</p>
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

    <!-- Summary Stats -->
    <div class="stats-summary">
        <div class="summary-item">
            <div class="summary-label">CA Total</div>
            <div class="summary-value"><?= format_currency_smart($summary['total']) ?></div>
            <div class="summary-trend">Depuis le lancement</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">Transactions</div>
            <div class="summary-value"><?= number_format($summary['count']) ?></div>
            <div class="summary-trend">Nombre total d'opérations</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">CA 30 derniers jours</div>
            <div class="summary-value"><?= format_currency_smart($summary['last30']) ?></div>
            <div class="summary-trend">Revenus récents</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="admin-charts-grid">
        <div class="stats-chart-card">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line"></i> Évolution (30j)
                </h3>
            </div>
            <div class="chart-container">
                <canvas id="chartChiffreAffaire" data-url="<?= base_url('/admin/api/stats/chiffre-affaire') ?>"></canvas>
            </div>
        </div>

        <div class="stats-chart-card">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-pie-chart"></i> Répartition par Type
                </h3>
            </div>
            <div class="chart-container">
                <canvas id="chartPaymentMethods" data-url="<?= base_url('/admin/api/stats/chiffre-affaire/payment-methods') ?>"></canvas>
            </div>
        </div>
    </div>

    <!-- Payment Methods Details Table -->
    <div class="stats-table-card">
        <div class="panel-header">
            <h2>Détails par Type de Transaction</h2>
        </div>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Type de Transaction</th>
                    <th>Nombre</th>
                    <th>Montant Total</th>
                    <th>Moyenne / Trans.</th>
                    <th>Part du CA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($methods as $m): ?>
                <tr>
                    <td>
                        <strong><?= $m['name'] ?></strong>
                    </td>
                    <td><?= $m['count'] ?></td>
                    <td><?= format_currency_smart($m['total']) ?></td>
                    <td><?= format_currency_smart($m['avg']) ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; height: 8px; background: #eee; border-radius: 4px; overflow: hidden;">
                                <div style="width: <?= $m['percent'] ?>%; background: var(--admin-primary); height: 100%;"></div>
                            </div>
                            <span style="font-size: 12px; font-weight: 700; width: 40px;"><?= $m['percent'] ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($methods)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #9a938e;">Aucune transaction enregistrée</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= js_url('admin/stat-chiffre-affaire.js') ?>"></script>
<?= $this->endSection() ?>

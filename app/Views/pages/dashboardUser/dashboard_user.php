<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="dash-wrap">

    <?php if (!empty($dbDown)): ?>
    <div class="container">
        <div class="db-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Base de données indisponible</strong>
                <p>Le dashboard affiche des données de secours. Vérifiez la config MySQL.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- HERO -->
    <section class="dash-hero">
        <div class="container">
            <div class="dash-hero-content">
                <div>
                    <h1>
                        Bonjour, <?= esc(explode(' ', $user['nom_complet'] ?? 'Utilisateur')[0]) ?> 
                        <i class="fas fa-hand-wave" style="font-size:28px;"></i>
                    </h1>
                    <p>
                        IMC : <strong><?= esc(number_format((float)($user['imc'] ?? 0), 1)) ?></strong>
                        &nbsp;·&nbsp;
                        <span class="imc-badge"><?= esc($stats['imc_status'] ?? '') ?></span>
                    </p>
                </div>
                <div class="hero-solde">
                    <i class="fas fa-wallet"></i>
                    <div>
                        <small>Mon solde</small>
                        <strong><?= esc(number_format((float)($user['solde'] ?? 0), 0)) ?> Ar</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container dash-body">

        <!-- KPI CARDS -->
        <section class="kpi-grid">

            <article class="kpi-card">
                <div class="kpi-icon" style="background:#FEF0EC;">
                    <i class="fas fa-weight" style="color:#E17864;"></i>
                </div>
                <div>
                    <h4>Poids actuel</h4>
                    <p><?= esc(number_format((float)($user['poids'] ?? 0), 1)) ?> <span>kg</span></p>
                    <small>Objectif : <?= esc(number_format((float)($stats['objectif_cible'] ?? 0), 1)) ?> kg</small>
                </div>
            </article>

            <article class="kpi-card">
                <div class="kpi-icon" style="background:#FFF8EC;">
                    <i class="fas fa-fire" style="color:#FAB863;"></i>
                </div>
                <div>
                    <h4>Calories / jour</h4>
                    <p><?= esc($stats['objectif_journalier_kcal'] ?? 2200) ?> <span>kcal</span></p>
                    <small>Objectif cible quotidien</small>
                </div>
            </article>

            <article class="kpi-card">
                <div class="kpi-icon" style="background:#EDFAF4;">
                    <i class="fas fa-running" style="color:#27ae60;"></i>
                </div>
                <div>
                    <h4>Activité</h4>
                    <p><?= esc($stats['activites_semaine'] ?? 0) ?> <span>sessions</span></p>
                    <small>Cette semaine</small>
                </div>
            </article>

            <article class="kpi-card">
                <div class="kpi-icon" style="background:#EEF4FF;">
                    <i class="fas fa-chart-pie" style="color:#3498db;"></i>
                </div>
                <div>
                    <h4>Progression</h4>
                    <?php $progress = (int) ($regimes[0]['progression'] ?? 0); ?>
                    <p><?= $progress ?> <span>%</span></p>
                    <small>Vers l'objectif</small>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width:<?= $progress ?>%"></div>
                </div>
            </article>

        </section>

        <!-- GRAPHES -->
        <section class="two-col">

            <article class="dash-panel">
                <div class="panel-header">
                    <div>
                        <h2><i class="fas fa-chart-line"></i> Évolution du poids</h2>
                        <p class="sub">Suivi journalier (kg)</p>
                    </div>
                </div>
                <div class="chart-wrap">
                    <canvas id="weightChart" height="240"></canvas>
                </div>
            </article>

            <article class="dash-panel">
                <div class="panel-header">
                    <div>
                        <h2><i class="fas fa-utensils"></i> Régime actuel</h2>
                        <p class="sub"><?= esc($currentRegime['nom'] ?? 'Aucun régime') ?></p>
                    </div>
                </div>
                <div class="donut-wrap">
                    <canvas id="macroChart" width="200" height="200"></canvas>
                </div>
                <div class="legend">
                    <span><span class="dot prot"></span>Protéines (Viande)</span>
                    <span><span class="dot gluc"></span>Glucides (Volaille)</span>
                    <span><span class="dot lip"></span>Lipides (Poisson)</span>
                </div>
            </article>

        </section>

        <!-- CALORIES -->
        <article class="dash-panel">
            <div class="panel-header">
                <div>
                    <h2><i class="fas fa-fire-alt"></i> Apport calorique moyen</h2>
                    <p class="sub">Évolution mensuelle (kcal/jour)</p>
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="calorieChart" height="200"></canvas>
            </div>
        </article>

        <!-- HISTORIQUE RÉGIMES -->
        <article class="dash-panel">
            <div class="panel-header">
                <div>
                    <h2><i class="fas fa-history"></i> Historique des régimes</h2>
                    <p class="sub">Vos régimes passés et en cours</p>
                </div>
                <a href="<?= base_url('/regimes') ?>" class="btn-panel-link">
                    Voir les régimes <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <?php if (!empty($regimes) && is_array($regimes)): ?>
                <div class="diet-history">
                    <?php foreach ($regimes as $regime): ?>
                    <div class="diet-row">
                        <div class="diet-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="diet-info">
                            <strong><?= esc($regime['nom'] ?? '-') ?></strong>
                            <p><?= esc($regime['periode'] ?? '-') ?></p>
                        </div>
                        <strong class="diet-variation">
                            <?= esc(number_format((float)($regime['variation'] ?? 0), 2)) ?> kg/j
                        </strong>
                        <span class="pill pill--<?= esc($regime['statut'] ?? 'actif') ?>">
                            <?php if (($regime['statut'] ?? '') === 'actif'): ?>
                                <i class="fas fa-circle" style="font-size:7px;"></i>
                            <?php elseif (($regime['statut'] ?? '') === 'termine'): ?>
                                <i class="fas fa-check"></i>
                            <?php else: ?>
                                <i class="fas fa-times"></i>
                            <?php endif; ?>
                            <?= esc($regime['statut'] ?? 'actif') ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <p>Aucun régime disponible pour l'instant.</p>
                    <a href="<?= base_url('/regimes') ?>" class="btn btn-primary">Découvrir les régimes</a>
                </div>
            <?php endif; ?>
        </article>

        <!-- HISTORIQUE TRANSACTIONS -->
        <?php if (!empty($historique)): ?>
        <article class="dash-panel">
            <div class="panel-header">
                <div>
                    <h2><i class="fas fa-receipt"></i> Historique des transactions</h2>
                    <p class="sub">Vos 8 dernières opérations</p>
                </div>
            </div>
            <div class="tx-list">
                <?php foreach ($historique as $tx): ?>
                <div class="tx-row">
                    <div class="tx-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="tx-info">
                        <strong><?= esc($tx['label'] ?? '-') ?></strong>
                        <small><?= esc($tx['date'] ?? '-') ?></small>
                    </div>
                    <span class="tx-amount"><?= esc($tx['detail'] ?? '') ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </article>
        <?php endif; ?>

    </div><!-- /.container -->

</main>

<!-- JSON pour les graphes Chart.js -->
<script id="weight-series-data" type="application/json"><?= esc(json_encode($weightSeries ?? []), 'raw') ?></script>
<script id="macro-data"         type="application/json"><?= esc(json_encode($currentRegime ?? []), 'raw') ?></script>
<script id="calorie-data"       type="application/json"><?= esc(json_encode($caloriesSeries ?? []), 'raw') ?></script>

<?= $this->endSection() ?>
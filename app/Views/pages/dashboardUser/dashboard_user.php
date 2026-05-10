<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="dash-wrap">
  <?php if (!empty($dbDown)): ?>
    <section class="panel db-warning">
      <strong>Connexion base de donnees indisponible.</strong>
      <p>Le dashboard affiche des donnees de secours. Verifie la config MySQL (`hostname`, service MySQL, identifiants).</p>
    </section>
  <?php endif; ?>

  <section class="dash-hero">
    <h1>Tableau de bord</h1>
    <p>Suivez votre evolution, votre regime actuel et l'historique de vos resultats.</p>
  </section>

  <section class="kpi-grid">
    <article class="kpi-card">
      <h4>Poids actuel</h4>
      <p><?= esc((string) ($user['poids'] ?? 0)) ?> kg</p>
      <small>Objectif <?= esc((string) ($stats['objectif_cible'] ?? 0)) ?> kg</small>
    </article>
    <article class="kpi-card">
      <h4>Calories / jour</h4>
      <p><?= esc((string) ($stats['objectif_journalier_kcal'] ?? 0)) ?></p>
      <small>kcal cible quotidienne</small>
    </article>
    <article class="kpi-card">
      <h4>Activite</h4>
      <p><?= esc((string) ($stats['activites_semaine'] ?? 0)) ?> sessions</p>
      <small>Cette semaine</small>
    </article>
    <article class="kpi-card">
      <h4>Progression</h4>
      <?php $progress = (int) (($regimes[0]['progression'] ?? 0)); ?>
      <p><?= esc((string) $progress) ?>%</p>
      <small>Vers l'objectif</small>
      <div class="progress-track">
        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
      </div>
    </article>
  </section>

  <section class="two-col">
    <article class="panel">
      <h2>Evolution du poids</h2>
      <p class="sub">Suivi journalier (jours / poids)</p>
      <div class="chart-wrap">
        <canvas id="weightChart" height="260"></canvas>
      </div>
    </article>

    <article class="panel">
      <h2>Regime actuel</h2>
      <p class="sub"><?= esc($currentRegime['nom'] ?? 'Regime actif') ?> - repartition</p>
      <div class="donut-wrap">
        <canvas id="macroChart" width="240" height="240"></canvas>
      </div>
      <div class="legend">
        <span><i class="dot prot"></i>Proteines</span>
        <span><i class="dot gluc"></i>Glucides</span>
        <span><i class="dot lip"></i>Lipides</span>
      </div>
    </article>
  </section>

  <section class="panel">
    <h2>Apport calorique moyen</h2>
    <p class="sub">Evolution mensuelle (kcal/jour)</p>
    <div class="chart-wrap">
      <canvas id="calorieChart" height="240"></canvas>
    </div>
  </section>

  <section class="panel">
    <h2>Historique des regimes</h2>
    <p class="sub">Vos regimes consommes</p>
    <div class="history-list diet-history">
      <?php if (!empty($regimes) && is_array($regimes)): ?>
        <?php foreach ($regimes as $regime): ?>
          <div class="history-row diet-row">
            <div>
              <strong><?= esc($regime['nom'] ?? '-') ?></strong>
              <p><?= esc($regime['periode'] ?? '-') ?></p>
            </div>
            <strong class="weight-loss"><?= esc(number_format((float) ($regime['variation'] ?? 0), 1)) ?> kg</strong>
            <span class="pill"><?= esc($regime['statut'] ?? 'actif') ?></span>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Aucun regime disponible.</p>
      <?php endif; ?>
    </div>
  </section>

  <script id="weight-series-data" type="application/json"><?= esc(json_encode($weightSeries ?? []), 'raw') ?></script>
  <script id="macro-data" type="application/json"><?= esc(json_encode($currentRegime ?? []), 'raw') ?></script>
  <script id="calorie-data" type="application/json"><?= esc(json_encode($caloriesSeries ?? []), 'raw') ?></script>
</main>

<?= $this->endSection() ?>

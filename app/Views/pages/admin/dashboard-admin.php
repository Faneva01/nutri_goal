<?php
// app/Views/admin/dashboard-admin.php
$this->extend('pages/admin/layouts/admin-main');
$this->section('content');
?>

<!-- ── En-tête ─────────────────────────────────────────────── -->
<div class="ad-page-header">
  <div>
    <h1>Tableau de bord</h1>
    <p>Bienvenue, <?= esc($admin_nom) ?> — voici un aperçu de l'activité.</p>
  </div>
  <span class="ad-pill ad-pill--gold">⭐ <?= esc(session()->get('admin_role')) ?></span>
</div>

<!-- ── KPI ───────────────────────────────────────────────────── -->
<div class="ad-kpi-grid">
  <div class="ad-kpi">
    <h4>Utilisateurs</h4>
    <p><?= esc($totalUsers) ?></p>
    <small>Inscrits au total</small>
  </div>
  <div class="ad-kpi">
    <h4>Abonnés Gold</h4>
    <p><?= esc($totalGold) ?></p>
    <small>Actifs actuellement</small>
  </div>
  <div class="ad-kpi">
    <h4>Régimes actifs</h4>
    <p><?= esc($totalRegimes) ?></p>
    <small>En cours</small>
  </div>
  <div class="ad-kpi">
    <h4>Chiffre d'affaire</h4>
    <p><?= number_format($caTotal, 0, ',', ' ') ?></p>
    <small>Ar — total cumulé</small>
  </div>
</div>

<!-- ── Row 1 : Inscriptions + Répartition ──────────────────── -->
<div class="ad-grid-2">
  <div class="ad-card">
    <h2>Évolution des inscriptions</h2>
    <p class="ad-card-sub">6 derniers mois</p>
    <div class="ad-chart-wrap">
      <canvas id="chartInscriptions" height="220"></canvas>
    </div>
  </div>
  <div class="ad-card">
    <h2>Standard vs Gold</h2>
    <p class="ad-card-sub">Répartition des utilisateurs</p>
    <div class="ad-chart-wrap" style="display:grid;place-items:center;min-height:220px;">
      <canvas id="chartTypes" width="220" height="220"></canvas>
    </div>
  </div>
</div>

<!-- ── Row 2 : CA + Régimes ─────────────────────────────────── -->
<div class="ad-grid-3">
  <div class="ad-card">
    <h2>Chiffre d'affaire mensuel</h2>
    <p class="ad-card-sub">Régimes vs Gold</p>
    <div class="ad-chart-wrap">
      <canvas id="chartCA" height="220"></canvas>
    </div>
  </div>
  <div class="ad-card">
    <h2>Régimes populaires</h2>
    <p class="ad-card-sub">Top abonnements</p>
    <div class="ad-chart-wrap">
      <canvas id="chartRegimes" height="220"></canvas>
    </div>
  </div>
</div>

<!-- ── Derniers abonnements ─────────────────────────────────── -->
<div class="ad-card">
  <h2>Derniers abonnements</h2>
  <p class="ad-card-sub">8 plus récents</p>
  <div class="ad-table-wrap">
    <table class="ad-table">
      <thead>
        <tr>
          <th>Utilisateur</th>
          <th>Régime</th>
          <th>Début</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($derniersAbonnements as $ab): ?>
          <tr>
            <td><strong><?= esc($ab['nom_complet']) ?></strong></td>
            <td><?= esc($ab['regime']) ?></td>
            <td><?= esc($ab['date_debut']) ?></td>
            <td>
              <?php $pill = $ab['statut'] === 'actif' ? 'green' : ($ab['statut'] === 'termine' ? 'grey' : 'red') ?>
              <span class="ad-pill ad-pill--<?= $pill ?>"><?= esc($ab['statut']) ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ── JSON DATA pour les graphes ───────────────────────────── -->
<script id="data-inscriptions" type="application/json"><?= json_encode($inscriptions) ?></script>
<script id="data-types"        type="application/json"><?= json_encode(['gold' => $goldCount, 'simple' => $simpleCount]) ?></script>
<script id="data-ca"           type="application/json"><?= json_encode($caMonthly) ?></script>
<script id="data-regimes"      type="application/json"><?= json_encode($regimesPopulaires) ?></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/admin/dashboard-admin.js') ?>"></script>

<?php $this->endSection(); ?>

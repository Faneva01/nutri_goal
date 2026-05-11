<?php
// app/Views/admin/regimes/index.php
$this->extend('pages/admin/layouts/admin-main');
$this->section('content');
?>

<div class="ad-page-header">
  <div>
    <h1>Gestion des Régimes</h1>
    <p><?= count($regimes) ?> régime(s) dans la base</p>
  </div>
  <a href="<?= base_url('admin/regimes/create') ?>" class="ad-btn ad-btn--primary">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouveau régime
  </a>
</div>

<div class="ad-card">
  <div class="ad-table-wrap">
    <table class="ad-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Type</th>
          <th>Intensité</th>
          <th>Variation/j</th>
          <th>Prix/j</th>
          <th>V / P / Vo %</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($regimes)): ?>
          <tr><td colspan="9" style="text-align:center;color:#999;padding:24px;">Aucun régime enregistré.</td></tr>
        <?php else: ?>
          <?php foreach ($regimes as $r): ?>
            <tr>
              <td><?= esc($r['id']) ?></td>
              <td><strong><?= esc($r['nom']) ?></strong></td>
              <td><?= esc($r['type_regime']) ?></td>
              <td><?= esc($r['intensite']) ?></td>
              <td><?= esc($r['variation_quotidienne']) ?> kg</td>
              <td><?= number_format($r['prix_jour'], 2) ?> Ar</td>
              <td><?= esc($r['pourcentage_viande']) ?>/<?= esc($r['pourcentage_poisson']) ?>/<?= esc($r['pourcentage_volaille']) ?></td>
              <td>
                <?php if ($r['actif']): ?>
                  <span class="ad-pill ad-pill--green">Actif</span>
                <?php else: ?>
                  <span class="ad-pill ad-pill--red">Inactif</span>
                <?php endif; ?>
              </td>
              <td style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="<?= base_url('admin/regimes/edit/' . $r['id']) ?>" class="ad-btn ad-btn--ghost ad-btn--sm">Modifier</a>
                <form method="post" action="<?= base_url('admin/regimes/delete/' . $r['id']) ?>"
                      onsubmit="return confirm('Supprimer ce régime ?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="ad-btn ad-btn--danger ad-btn--sm">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->endSection(); ?>

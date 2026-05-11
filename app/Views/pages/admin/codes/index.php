<?php
// app/Views/pages/admin/codes/index.php
$this->extend('pages/admin/layouts/admin-main');
$this->section('content');
?>

<div class="ad-page-header">
  <div>
    <h1>Codes Portefeuille</h1>
    <p><?= count($codes) ?> code(s) au total</p>
  </div>
</div>

<!-- Formulaire génération -->
<div class="ad-card" style="margin-bottom:20px;">
  <h2>Générer des codes</h2>
  <p class="ad-card-sub">Les codes générés pourront être distribués aux utilisateurs.</p>
  <form method="post" action="<?= base_url('admin/codes/generer') ?>" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
    <?= csrf_field() ?>
    <div class="ad-field" style="margin:0;min-width:160px;">
      <label>Montant (Ar)</label>
      <input type="number" name="montant" min="500" step="500" value="5000" required>
    </div>
    <div class="ad-field" style="margin:0;min-width:120px;">
      <label>Quantité</label>
      <input type="number" name="quantite" min="1" max="50" value="5" required>
    </div>
    <button type="submit" class="ad-btn ad-btn--primary">🎟 Générer</button>
  </form>
</div>

<!-- Table codes -->
<div class="ad-card">
  <div class="ad-table-wrap">
    <table class="ad-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Code</th>
          <th>Montant</th>
          <th>Utilisateur</th>
          <th>Créé le</th>
          <th>Utilisé le</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($codes)): ?>
          <tr><td colspan="7" style="text-align:center;color:#999;padding:24px;">Aucun code.</td></tr>
        <?php else: ?>
          <?php foreach ($codes as $c): ?>
            <tr>
              <td><?= esc($c['id']) ?></td>
              <td><code style="background:#f5ede8;padding:3px 8px;border-radius:6px;font-weight:700;"><?= esc($c['code']) ?></code></td>
              <td><?= number_format($c['montant'], 2) ?> Ar</td>
              <td><?= esc($c['utilisateur_nom'] ?? '—') ?></td>
              <td><?= esc($c['date_creation']) ?></td>
              <td><?= esc($c['date_utilisation'] ?? '—') ?></td>
              <td>
                <?php if ($c['utilisateur_id']): ?>
                  <span class="ad-pill ad-pill--grey">Utilisé</span>
                <?php else: ?>
                  <span class="ad-pill ad-pill--green">Disponible</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->endSection(); ?>

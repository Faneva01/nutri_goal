<?php
// app/Views/admin/gold/index.php
$this->extend('pages/admin/layouts/admin-main');
$this->section('content');
?>

<div class="ad-page-header">
  <div>
    <h1>Utilisateurs Gold ⭐</h1>
    <p>Gérez le statut Gold de chaque utilisateur.</p>
  </div>
</div>

<div class="ad-card">
  <div class="ad-table-wrap">
    <table class="ad-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Utilisateur</th>
          <th>Email</th>
          <th>IMC</th>
          <th>Solde (Ar)</th>
          <th>Gold depuis</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($utilisateurs as $u): ?>
          <tr>
            <td><?= esc($u['id']) ?></td>
            <td><strong><?= esc($u['nom_complet']) ?></strong></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['imc'] ?? '—') ?></td>
            <td><?= number_format($u['solde'] ?? 0, 2) ?></td>
            <td><?= esc($u['date_achat'] ?? '—') ?></td>
            <td>
              <?php if ($u['gold_actif']): ?>
                <span class="ad-pill ad-pill--gold">⭐ Gold</span>
              <?php else: ?>
                <span class="ad-pill ad-pill--grey">Standard</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="post" action="<?= base_url('admin/gold/toggle/' . $u['id']) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="ad-btn ad-btn--ghost ad-btn--sm">
                  <?= $u['gold_actif'] ? '🔕 Retirer Gold' : '⭐ Activer Gold' ?>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $this->endSection(); ?>

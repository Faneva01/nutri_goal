<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
// app/Views/portefeuille/confirmation.php
$labels = ['mvola' => 'MVola', 'orange_money' => 'Orange Money', 'airtel' => 'Airtel Money'];
$moyenLabel = $labels[$moyen] ?? $moyen;
?>

<main class="ac-wrap">
  <div class="ac-card ac-confirm-card">
    <div class="ac-confirm-icon">✅</div>
    <h2>Paiement simulé !</h2>
    <p>Votre paiement via <strong><?= esc($moyenLabel) ?></strong> a bien été enregistré.</p>
    <p>Montant : <strong><?= number_format($montant, 0, ',', ' ') ?> Ar</strong></p>

    <p style="margin-top:18px;font-weight:700;">Votre code de recharge :</p>
    <div class="ac-code-box"><?= esc($code) ?></div>

    <p style="font-size:13px;color:#999;">Conservez ce code et entrez-le dans votre portefeuille pour créditer votre solde.</p>

    <div class="ac-confirm-actions">
      <a href="<?= base_url('portefeuille') ?>" class="ac-pay-btn" style="width:auto;padding:12px 28px;display:inline-block;text-decoration:none;">
        💰 Aller au portefeuille
      </a>
      <a href="<?= base_url('dashboard') ?>" style="padding:12px 22px;border:1.5px solid #e8ddd8;border-radius:30px;text-decoration:none;font-weight:700;color:#666;font-size:14px;">
        Tableau de bord
      </a>
    </div>
  </div>
</main>

<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
// app/Views/portefeuille/achat-code.php
?>

<main class="ac-wrap">

  <div class="ac-breadcrumb">
    <a href="<?= base_url('portefeuille') ?>">← Retour au portefeuille</a>
  </div>

  <div class="ac-hero">
    <h1>Recharger mon portefeuille</h1>
    <p>Choisissez un montant et un moyen de paiement Mobile Money.</p>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="ac-alert"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <div class="ac-grid">

    <!-- ── Formulaire ─────────────────────────────────────── -->
    <div class="ac-card ac-form-card">

      <!-- Montants rapides -->
      <p class="ac-section-label">Choisir un montant</p>
      <div class="ac-amounts">
        <?php foreach ([2000, 5000, 10000, 20000, 50000] as $m): ?>
          <button type="button" class="ac-amount-btn" onclick="setMontant(<?= $m ?>)">
            <?= number_format($m, 0, ',', ' ') ?> Ar
          </button>
        <?php endforeach; ?>
      </div>
      <p class="ac-or">ou saisir un montant personnalisé</p>
      <input type="number" id="montantInput" class="ac-input" placeholder="Montant en Ar" min="1000" step="500">

      <hr class="ac-divider">

      <!-- Moyens de paiement -->
      <p class="ac-section-label">Moyen de paiement</p>
      <div class="ac-methods">
        <label class="ac-method" for="m-mvola">
          <input type="radio" id="m-mvola" name="moyen" value="mvola" checked>
          <span class="ac-method-inner">
            <span class="ac-method-emoji">📱</span>
            <span>
              <strong>MVola</strong>
              <small>Telma Madagascar</small>
            </span>
          </span>
        </label>
        <label class="ac-method" for="m-orange">
          <input type="radio" id="m-orange" name="moyen" value="orange_money">
          <span class="ac-method-inner">
            <span class="ac-method-emoji">🟠</span>
            <span>
              <strong>Orange Money</strong>
              <small>Orange Madagascar</small>
            </span>
          </span>
        </label>
        <label class="ac-method" for="m-airtel">
          <input type="radio" id="m-airtel" name="moyen" value="airtel">
          <span class="ac-method-inner">
            <span class="ac-method-emoji">🔴</span>
            <span>
              <strong>Airtel Money</strong>
              <small>Airtel Madagascar</small>
            </span>
          </span>
        </label>
      </div>

      <form method="post" action="<?= base_url('portefeuille/payer') ?>" id="payForm">
        <?= csrf_field() ?>
        <input type="hidden" name="montant" id="montantHidden">
        <input type="hidden" name="moyen"   id="moyenHidden">
        <button type="button" class="ac-pay-btn" onclick="submitPay()">
          Confirmer le paiement
        </button>
      </form>
    </div>

    <!-- ── Infos ──────────────────────────────────────────── -->
    <div class="ac-card ac-info-card">
      <p class="ac-section-label">ℹ️ Comment ça marche ?</p>
      <ol class="ac-steps">
        <li><span>1</span> Choisissez un montant et un opérateur.</li>
        <li><span>2</span> Après paiement, vous recevrez un code unique.</li>
        <li><span>3</span> Entrez ce code dans votre portefeuille.</li>
        <li><span>4</span> Votre solde est crédité immédiatement.</li>
      </ol>
      <hr class="ac-divider">
      <p class="ac-section-label">🔒 Sécurisé</p>
      <p class="ac-info-text">Vos transactions sont protégées et chaque code est à usage unique.</p>
    </div>

  </div>
</main>

<script>
  function setMontant(v) {
    document.getElementById('montantInput').value = v;
    document.querySelectorAll('.ac-amount-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
  }
  function submitPay() {
    const montant = document.getElementById('montantInput').value;
    const moyen   = document.querySelector('input[name="moyen"]:checked').value;
    if (!montant || montant < 1000) {
      alert('Montant minimum : 1 000 Ar');
      return;
    }
    document.getElementById('montantHidden').value = montant;
    document.getElementById('moyenHidden').value   = moyen;
    document.getElementById('payForm').submit();
  }
</script>

<?= $this->endSection() ?>

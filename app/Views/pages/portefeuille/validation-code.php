<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
// app/Views/portefeuille/validation-code.php
$solde  = number_format($user['solde'] ?? 0, 2, ',', ' ');
?>

<main class="vc-wrap">

  <!-- Breadcrumb -->
  <div class="vc-breadcrumb">
    <a href="<?= base_url('dashboard') ?>">Tableau de bord</a>
    <span>›</span>
    <span>Portefeuille</span>
  </div>

  <div class="vc-grid">

    <!-- ── Solde + Recharge ───────────────────────────────── -->
    <section class="vc-card vc-wallet">
      <div class="vc-wallet-header">
        <div class="vc-wallet-icon">💰</div>
        <div>
          <h2>Mon Portefeuille</h2>
          <p>Solde actuel</p>
        </div>
      </div>

      <div class="vc-balance">
        <span class="vc-balance-label">Solde disponible</span>
        <span class="vc-balance-amount" id="soldeDisplay"><?= $solde ?> Ar</span>
      </div>

      <hr class="vc-divider">

      <div class="vc-recharge">
        <p class="vc-recharge-title">Entrer un code de recharge</p>
        <div class="vc-code-row">
          <input type="text" id="codeInput" class="vc-code-input"
                 placeholder="EX : A3F9C2B1D0"
                 maxlength="20" autocomplete="off"
                 oninput="this.value = this.value.toUpperCase()">
          <button class="vc-valider-btn" id="btnValider" onclick="validerCode()">
            Valider
          </button>
        </div>
        <p class="vc-feedback" id="codeFeedback"></p>
      </div>

      <div class="vc-cta">
        <a href="<?= base_url('portefeuille/acheter') ?>" class="vc-acheter-btn">
          ➕ Recharger via Mobile Money
        </a>
      </div>
    </section>

    <!-- ── Historique transactions ────────────────────────── -->
    <section class="vc-card vc-history">
      <h2>Historique des transactions</h2>
      <p class="vc-card-sub">10 dernières opérations</p>

      <?php if (empty($transactions)): ?>
        <p class="vc-empty">Aucune transaction pour le moment.</p>
      <?php else: ?>
        <ul class="vc-tx-list">
          <?php foreach ($transactions as $tx): ?>
            <?php
              $icon = match ($tx['type_transaction']) {
                'ajout_code'    => '🎟',
                'achat_regime'  => '🥗',
                'achat_gold'    => '⭐',
                'remboursement' => '↩️',
                default         => '💳',
              };
              $positif = in_array($tx['type_transaction'], ['ajout_code','remboursement']);
            ?>
            <li class="vc-tx-item">
              <span class="vc-tx-icon"><?= $icon ?></span>
              <div class="vc-tx-info">
                <span class="vc-tx-label"><?= esc($tx['description'] ?? $tx['type_transaction']) ?></span>
                <span class="vc-tx-date"><?= esc(date('d/m/Y H:i', strtotime($tx['date_transaction']))) ?></span>
              </div>
              <span class="vc-tx-amount <?= $positif ? 'pos' : 'neg' ?>">
                <?= $positif ? '+' : '-' ?><?= number_format($tx['montant'], 2, ',', ' ') ?> Ar
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </div>
</main>

<script>
async function validerCode() {
  const code = document.getElementById('codeInput').value.trim();
  const fb   = document.getElementById('codeFeedback');
  const btn  = document.getElementById('btnValider');

  if (!code) { fb.textContent = 'Saisissez un code.'; fb.className = 'vc-feedback error'; return; }

  btn.disabled = true;
  btn.textContent = '…';

  const resp = await fetch('<?= base_url('portefeuille/valider-code') ?>', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      code,
      '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
    }),
  });
  const data = await resp.json();

  fb.className = 'vc-feedback ' + (data.ok ? 'success' : 'error');
  fb.textContent = data.message;

  if (data.ok) {
    document.getElementById('soldeDisplay').textContent = data.nouveau_solde + ' Ar';
    document.getElementById('codeInput').value = '';
    setTimeout(() => location.reload(), 1800);
  }

  btn.disabled = false;
  btn.textContent = 'Valider';
}
</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="portefeuille-container">
    <div class="portefeuille-header">
        <h1>Paiement : <?= esc($nom_moyen) ?></h1>
        <p>Complétez les informations de paiement pour le code sélectionné.</p>
    </div>

    <div id="alerts-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>
    </div>

    <form method="post" action="<?= site_url('/paiement/traiter/' . esc($moyen_paiement)) ?>" class="form-section" id="payment-form">
        <input type="hidden" name="code_id" value="<?= esc($code_data['id'] ?? '') ?>">

        <div class="form-group">
            <label class="form-label">Code n°</label>
            <input type="text" class="form-input" value="<?= esc($code_data['code'] ?? 'N/A') ?>" disabled>
        </div>

        <div class="form-group">
            <label class="form-label">Montant</label>
            <input type="text" class="form-input" value="<?= esc($code_data['montant'] ?? '0') ?> Ar" disabled>
        </div>

        <?php if ($moyen_paiement === 'carte_bancaire'): ?>
            <div class="form-group">
                <label class="form-label" for="numero_carte">Numéro de carte</label>
                <input type="text" id="numero_carte" name="numero_carte" class="form-input" placeholder="0000 0000 0000 0000" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="date_expiration">Date d'expiration</label>
                <input type="text" id="date_expiration" name="date_expiration" class="form-input" placeholder="MM/AA" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" class="form-input" placeholder="123" required>
            </div>
        <?php else: ?>
            <div class="form-group" id="mobile-payment-fields">
                <label class="form-label" for="numero_mobile">Numéro de téléphone</label>
                <input type="text" id="numero_mobile" name="numero_mobile" class="form-input" placeholder="0341234567" required>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Payer avec <?= esc($nom_moyen) ?></button>
            <a href="<?= site_url('/paiement/process/' . esc($code_data['id'] ?? '')) ?>" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
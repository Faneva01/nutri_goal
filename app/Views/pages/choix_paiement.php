<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="portefeuille-container">
    <div class="portefeuille-header">
        <h1>Choisissez un moyen de paiement</h1>
        <p>Sélectionnez le mode de paiement pour finaliser votre achat.</p>
    </div>

    <div id="alerts-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>
    </div>

    <form method="post" action="<?= site_url('/paiement/choisir') ?>" class="form-section" id="payment-selection-form">
        <input type="hidden" name="code_id" value="<?= esc($code_data['id'] ?? '') ?>">

        <div class="form-group">
            <label class="form-label">Sélectionnez votre moyen de paiement</label>
            <div class="payment-methods">
                <div class="payment-method">
                    <input type="radio" id="mvola" name="moyen_paiement" value="mvola" required>
                    <label for="mvola">
                        <span class="payment-icon">📱</span>
                        <span>MVola</span>
                    </label>
                </div>
                <div class="payment-method">
                    <input type="radio" id="airtel" name="moyen_paiement" value="airtel_money">
                    <label for="airtel">
                        <span class="payment-icon">📱</span>
                        <span>Airtel Money</span>
                    </label>
                </div>
                <div class="payment-method">
                    <input type="radio" id="orange" name="moyen_paiement" value="orange_money">
                    <label for="orange">
                        <span class="payment-icon">📱</span>
                        <span>Orange Money</span>
                    </label>
                </div>
                <div class="payment-method">
                    <input type="radio" id="carte" name="moyen_paiement" value="carte_bancaire">
                    <label for="carte">
                        <span class="payment-icon">💳</span>
                        <span>Carte Bancaire</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Continuer</button>
            <a href="<?= site_url('/code/achat') ?>" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
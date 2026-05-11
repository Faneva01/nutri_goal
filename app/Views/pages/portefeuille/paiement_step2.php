<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <div class="page-header" style="margin-bottom: 40px; text-align: center;">
        <h1 style="font-size: 32px; color: #2d2926; font-weight: 800;">
            <i class="fas fa-credit-card" style="color: #E17864;"></i> Moyen de Paiement
        </h1>
        <p class="subtitle" style="color: #9a938e; font-weight: 600;">Sélectionnez comment vous souhaitez régler vos <strong><?= number_format($code['montant'], 0, ',', ' ') ?> Ar</strong></p>
    </div>

    <div style="max-width: 850px; margin: 0 auto;">
        <article class="dash-panel">
            <form action="<?= base_url('/paiement/formulaire') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="code_id" value="<?= $code['id'] ?>">

                <div class="payment-methods-grid" style="margin-bottom: 40px;">
                    
                    <label class="payment-card">
                        <input type="radio" name="moyen_paiement" value="mvola" checked>
                        <span class="icon">📱</span>
                        <strong>MVola</strong>
                        <small>Telma Money</small>
                    </label>

                    <label class="payment-card">
                        <input type="radio" name="moyen_paiement" value="airtel">
                        <span class="icon">📱</span>
                        <strong>Airtel Money</strong>
                        <small>Airtel Madagascar</small>
                    </label>

                    <label class="payment-card">
                        <input type="radio" name="moyen_paiement" value="orange">
                        <span class="icon">📱</span>
                        <strong>Orange Money</strong>
                        <small>Orange Madagascar</small>
                    </label>

                    <label class="payment-card">
                        <input type="radio" name="moyen_paiement" value="visa">
                        <span class="icon">💳</span>
                        <strong>Carte Bancaire</strong>
                        <small>Visa / Mastercard</small>
                    </label>

                </div>

                <div style="display: flex; gap: 20px; align-items: center; justify-content: center;">
                    <a href="<?= base_url('/code/achat') ?>" class="btn btn-secondary" style="min-width: 150px; padding: 15px;">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary" style="min-width: 250px; padding: 15px; font-size: 16px;">
                        Valider le choix <i class="fas fa-check"></i>
                    </button>
                </div>
            </form>
        </article>

        <p style="text-align: center; color: #b0a9a4; font-size: 13px; margin-top: 30px; font-weight: 500;">
            <i class="fas fa-shield-alt"></i> Vos données de paiement ne sont jamais stockées sur nos serveurs.
        </p>
    </div>

</main>

<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <div class="page-header" style="margin-bottom: 40px; text-align: center;">
        <h1 style="font-size: 32px; color: #2d2926; font-weight: 800;">
            <i class="fas fa-lock" style="color: #27ae60;"></i> Finaliser le Paiement
        </h1>
        <p class="subtitle" style="color: #9a938e; font-weight: 600;">Saisissez vos coordonnées de paiement pour recevoir votre code</p>
    </div>

    <div style="max-width: 600px; margin: 0 auto;">
        <article class="dash-panel">
            <div style="text-align: center; margin-bottom: 32px; padding-bottom: 32px; border-bottom: 1px solid #f0ece8;">
                <div style="font-size: 13px; color: #9a938e; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Montant à régler</div>
                <div style="font-size: 40px; font-weight: 900; color: #2d2926; margin: 8px 0;"><?= number_format($code['montant'], 0, ',', ' ') ?> <span style="font-size: 20px; color: #9a938e;">Ar</span></div>
                <div style="display: inline-flex; align-items: center; gap: 8px; margin-top: 8px; padding: 6px 16px; background: #FFF9F7; border: 1px solid #fcece8; border-radius: 999px; font-size: 13px; font-weight: 700; color: #E17864;">
                    <i class="fas fa-wallet"></i> Mode : <?= strtoupper($moyen) ?>
                </div>
            </div>

            <form action="<?= base_url('/paiement/traiter') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="code_id" value="<?= $code['id'] ?>">

                <?php if (in_array($moyen, ['mvola', 'airtel', 'orange'])): ?>
                    <div class="form-group">
                        <label><i class="fas fa-mobile-alt"></i> Numéro de téléphone mobile</label>
                        <input type="tel" name="phone" class="input" placeholder="03X XX XXX XX" required style="padding: 15px;">
                        <small style="color: #b0a9a4; margin-top: 8px; display: block; font-weight: 500;">Un SMS de validation vous sera envoyé sur ce numéro.</small>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Numéro de carte</label>
                        <input type="text" name="card_num" class="input" placeholder="XXXX XXXX XXXX XXXX" required style="padding: 15px;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Expiration</label>
                            <input type="text" name="exp" class="input" placeholder="MM/YY" required style="padding: 15px;">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-key"></i> CVV</label>
                            <input type="text" name="cvv" class="input" placeholder="123" required style="padding: 15px;">
                        </div>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 32px;">
                    <button type="submit" class="btn btn-primary w-full" style="padding: 18px; font-size: 16px;">
                        Confirmer le paiement de <?= number_format($code['montant'], 0, ',', ' ') ?> Ar <i class="fas fa-check-circle"></i>
                    </button>
                    <a href="<?= base_url('/paiement/moyen/' . $code['id']) ?>" class="btn btn-secondary w-full" style="margin-top: 12px; padding: 12px; background: transparent; border: none; color: #b0a9a4;">
                        Annuler et changer de mode
                    </a>
                </div>
            </form>
        </article>
        
        <div style="text-align: center; margin-top: 32px; display: flex; align-items: center; justify-content: center; gap: 24px; opacity: 0.5;">
            <i class="fab fa-cc-visa" style="font-size: 32px;"></i>
            <i class="fab fa-cc-mastercard" style="font-size: 32px;"></i>
            <i class="fas fa-shield-check" style="font-size: 24px;"></i>
        </div>
    </div>

</main>

<?= $this->endSection() ?>

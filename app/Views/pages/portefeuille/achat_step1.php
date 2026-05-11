<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <!-- HEADER ALIGNÉ DASHBOARD -->
    <div class="page-header" style="margin-bottom: 40px;">
        <h1 style="font-size: 28px; color: #2d2926; font-weight: 800;">
            <i class="fas fa-cart-plus" style="color: #E17864;"></i> Acheter un Code
        </h1>
        <p class="subtitle" style="color: #9a938e; font-weight: 600;">Créditez votre portefeuille pour accéder aux programmes Gold</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 24px;">
        
        <!-- FORMULAIRE PRINCIPAL -->
        <article class="dash-panel">
            <div class="panel-header">
                <div>
                    <h2><i class="fas fa-coins" style="color: #FAB863;"></i> Montant de la recharge</h2>
                    <p class="sub">Choisissez la somme que vous souhaitez ajouter</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div style="padding: 16px; border-radius: 12px; background: #fff5f5; color: #e74c3c; margin-bottom: 24px; border: 1px solid #fed7d7; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/code/achat') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-group" style="margin-bottom: 32px;">
                    <label>Saisir un montant (Ar)</label>
                    <input type="number" name="montant" class="input" placeholder="ex: 10000" min="1000" step="500" required
                           style="padding: 18px; font-size: 24px; font-weight: 800; text-align: center; color: #E17864; background: #fffaf9; border-color: #fcece8;">
                    <small style="color: #b0a9a4; margin-top: 10px; display: block; text-align: center; font-weight: 600;">Le montant minimum est de 1 000 Ar</small>
                </div>

                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 32px;">
                    <?php foreach([5000, 10000, 20000, 50000] as $amt): ?>
                        <button type="button" class="btn btn-outline" onclick="document.getElementsByName('montant')[0].value=<?= $amt ?>"
                                style="padding: 12px; font-weight: 700;">
                            <?= number_format($amt, 0, ',', ' ') ?> Ar
                        </button>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding: 18px; font-size: 16px;">
                    Continuer vers le paiement <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </article>

        <!-- SIDEBAR INFO -->
        <aside style="display: flex; flex-direction: column; gap: 20px;">
            <div class="dash-panel" style="background: #fff; border-color: #f0ece8; padding: 30px;">
                <h3 style="margin-top:0; font-size: 18px; color: #2d2926; font-weight: 800; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-lightbulb" style="color: #FAB863;"></i> Comment ça marche ?
                </h3>
                
                <div class="purchase-steps" style="display: flex; flex-direction: column; gap: 24px;">
                    <div class="p-step" style="display: flex; gap: 16px; align-items: flex-start;">
                        <div class="p-circle" style="width: 32px; height: 32px; border-radius: 50%; background: #FEF0EC; color: #E17864; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; border: 2px solid #fcece8;">1</div>
                        <div class="p-text">
                            <strong style="display: block; font-size: 14px; color: #2d2926;">Montant</strong>
                            <p style="margin: 0; font-size: 13px; color: #9a938e; line-height: 1.4;">Choisissez un montant à créditer</p>
                        </div>
                    </div>

                    <div class="p-step" style="display: flex; gap: 16px; align-items: flex-start;">
                        <div class="p-circle" style="width: 32px; height: 32px; border-radius: 50%; background: #FEF0EC; color: #E17864; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; border: 2px solid #fcece8;">2</div>
                        <div class="p-text">
                            <strong style="display: block; font-size: 14px; color: #2d2926;">Paiement Mobile</strong>
                            <p style="margin: 0; font-size: 13px; color: #9a938e; line-height: 1.4;">Payez via MVola, Airtel Money ou Orange Money</p>
                        </div>
                    </div>

                    <div class="p-step" style="display: flex; gap: 16px; align-items: flex-start;">
                        <div class="p-circle" style="width: 32px; height: 32px; border-radius: 50%; background: #FEF0EC; color: #E17864; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; border: 2px solid #fcece8;">3</div>
                        <div class="p-text">
                            <strong style="display: block; font-size: 14px; color: #2d2926;">Réception Code</strong>
                            <p style="margin: 0; font-size: 13px; color: #9a938e; line-height: 1.4;">Recevez votre code de validation unique</p>
                        </div>
                    </div>

                    <div class="p-step" style="display: flex; gap: 16px; align-items: flex-start;">
                        <div class="p-circle" style="width: 32px; height: 32px; border-radius: 50%; background: #FEF0EC; color: #E17864; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; border: 2px solid #fcece8;">4</div>
                        <div class="p-text">
                            <strong style="display: block; font-size: 14px; color: #2d2926;">Validation</strong>
                            <p style="margin: 0; font-size: 13px; color: #9a938e; line-height: 1.4;">Validez manuellement pour créditer votre compte</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="padding: 20px; border-radius: 16px; background: #f0fdf4; border: 1px solid #dcfce7; display: flex; align-items: flex-start; gap: 12px;">
                <i class="fas fa-lock" style="color:#22c55e; margin-top: 4px;"></i>
                <div>
                    <strong style="color: #166534; font-size: 14px;">Sécurisé</strong>
                    <p style="font-size: 12px; color: #15803d; margin-top: 2px; opacity: 0.8; line-height: 1.4;">Vos transactions sont protégées et cryptées de bout en bout.</p>
                </div>
            </div>
        </aside>

    </div>

</main>

<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <div class="page-header" style="margin-bottom: 40px;">
        <h1 style="font-size: 28px; color: #333;"><i class="fas fa-check-circle" style="color: #27ae60;"></i> Valider un Code</h1>
        <p class="subtitle" style="color: #666;">Collez votre code unique pour augmenter instantanément votre solde.</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
        
        <!-- FORMULAIRE VALIDATION -->
        <article class="dash-panel" style="padding: 30px;">
            <div class="panel-header" style="margin-bottom: 25px;">
                <h2 style="margin:0;"><i class="fas fa-keyboard"></i> Saisie du code</h2>
                <p class="sub">Veuillez entrer le code à 12 caractères</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" style="padding: 15px; border-radius: 8px; background: #ffebee; color: #c62828; margin-bottom: 20px; border: 1px solid #ffcdd2;">
                    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/code/validation') ?>" method="POST" id="validate-form">
                <?= csrf_field() ?>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display:block; margin-bottom:10px; font-weight:600; color:#555;">Code Portefeuille</label>
                    <input type="text" id="code" name="code" class="input" placeholder="ex: CODEA1B2C3D4" required
                           style="width:100%; padding:15px; font-size: 24px; font-weight: 800; border-radius: 10px; border: 2px solid #eee; text-transform: uppercase; letter-spacing: 2px; text-align: center;">
                </div>

                <!-- Feedback AJAX -->
                <div id="code-status" style="margin-bottom: 20px;"></div>

                <button type="submit" class="btn btn-primary w-full" style="padding: 15px; font-size: 16px; font-weight: 700;">
                    Valider et créditer <i class="fas fa-coins"></i>
                </button>
            </form>
        </article>

        <!-- SIDEBAR SOLDE -->
        <aside>
            <div class="dash-panel" style="padding: 25px; background: #FFF9F7; border: 2px solid #E17864;">
                <h3 style="margin-top:0; font-size: 18px; color: #E17864;"><i class="fas fa-wallet"></i> Votre Solde</h3>
                <div style="font-size: 32px; font-weight: 800; color: #333; margin: 15px 0;">
                    <?= number_format(session()->get('solde') ?? 0, 0) ?> <small style="font-size: 14px;">Ar</small>
                </div>
                <p style="font-size: 13px; color: #666;">Le montant du code sera ajouté immédiatement après validation.</p>
            </div>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="<?= base_url('/code/achat') ?>" class="link" style="font-weight: 600;">
                    <i class="fas fa-plus"></i> Acheter un autre code
                </a>
            </div>
        </aside>

    </div>

</main>

<script src="<?= base_url('assets/js/portefeuille/portefeuille.js') ?>"></script>

<?= $this->endSection() ?>
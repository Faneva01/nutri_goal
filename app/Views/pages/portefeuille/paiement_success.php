<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <div style="max-width: 700px; margin: 0 auto; text-align: center;">
        
        <div class="success-icon" style="background: #27ae60; color: white; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; box-shadow: 0 10px 20px rgba(39, 174, 96, 0.2);">
            <i class="fas fa-check"></i>
        </div>

        <h1 style="font-size: 32px; color: #333; margin-bottom: 10px;">Paiement Réussi !</h1>
        <p class="subtitle" style="color: #666; font-size: 18px; margin-bottom: 40px;">Voici votre code unique à utiliser pour créditer votre solde.</p>

        <article class="dash-panel" style="padding: 40px; background: #fff; border: 2px dashed #E17864; border-radius: 20px;">
            <div style="margin-bottom: 15px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Votre Code Unique</div>
            
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; margin-bottom: 25px;">
                <code id="code-display" style="font-size: 36px; font-weight: 900; color: #E17864; background: #FFF9F7; padding: 15px 30px; border-radius: 12px; letter-spacing: 3px;">
                    <?= esc($code) ?>
                </code>
                <button onclick="copyCode()" class="btn btn-secondary" style="padding: 15px 20px;" title="Copier le code">
                    <i class="fas fa-copy"></i>
                </button>
            </div>

            <div style="font-size: 14px; color: #666; background: #f9f9f9; padding: 15px; border-radius: 10px;">
                <i class="fas fa-info-circle"></i> Ce code d'une valeur de <strong><?= number_format($amount, 0) ?> Ar</strong> doit être validé sur la page "Valider un code".
            </div>
        </article>

        <div style="margin-top: 40px; display: flex; gap: 15px; justify-content: center;">
            <a href="<?= base_url('/code/validation') ?>" class="btn btn-primary" style="padding: 15px 30px; font-weight: 700;">
                Valider ce code maintenant <i class="fas fa-arrow-right"></i>
            </a>
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary" style="padding: 15px 30px;">
                Retour au Dashboard
            </a>
        </div>

    </div>

</main>

<script>
function copyCode() {
    const code = document.getElementById('code-display').innerText;
    navigator.clipboard.writeText(code).then(() => {
        showToast('success', "Code copié dans le presse-papier !");
    });
}
</script>

<?= $this->endSection() ?>
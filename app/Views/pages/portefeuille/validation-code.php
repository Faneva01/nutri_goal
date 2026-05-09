<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="portefeuille-container">
    <!-- Header -->
    <div class="portefeuille-header">
        <h1>Valider votre code</h1>
        <p>Entrez votre code portefeuille pour ajouter du solde</p>
    </div>

    <!-- Alerts Container -->
    <div id="alerts-container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                ✓ <?= esc(session()->getFlashdata('success')) ?>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                ✗ <?= esc(session()->getFlashdata('error')) ?>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (!empty($errors) && is_array($errors)): ?>
            <div class="alert alert-danger">
                <div>
                    <strong>Erreur(s) de validation:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Validation Form -->
    <form id="validate-form" method="post" action="<?= site_url('/code/validation') ?>" class="form-section">
        <h2 class="form-section-title">Saisir votre code</h2>

        <!-- Code Input -->
        <div class="form-group">
            <label for="code" class="form-label">Code portefeuille *</label>
            <input type="text" 
                   id="code" 
                   name="code" 
                   class="form-input" 
                   value="<?= esc(old('code')) ?>"
                   placeholder="Collez votre code ici (ex: CODE123456789)"
                   required
                   autocomplete="off">
            <small style="color: var(--gray); display: block; margin-top: 0.5rem;">
                Vous trouverez votre code dans l'email de confirmation du paiement
            </small>
        </div>

        <!-- Code Status Feedback -->
        <div id="code-status" style="margin: 1rem 0;"></div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Valider le code
            </button>
            <a href="<?= site_url('/code/achat') ?>" class="btn btn-secondary">
                Acheter un nouveau code
            </a>
        </div>
    </form>

    <!-- Tips Section -->
    <div class="form-section" style="background: rgba(76, 175, 80, 0.05); border-left-color: var(--success);">
        <h3 style="color: var(--success); margin-bottom: 1rem;">💡 Conseils</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 0.5rem 0;">
                ✓ Vérifiez que vous avez copié correctement le code complet
            </li>
            <li style="padding: 0.5rem 0;">
                ✓ Le code doit commencer par "CODE" suivi de chiffres
            </li>
            <li style="padding: 0.5rem 0;">
                ✓ Un code ne peut être utilisé qu'une seule fois
            </li>
            <li style="padding: 0.5rem 0;">
                ✓ Certains codes peuvent avoir une date d'expiration
            </li>
        </ul>
    </div>

    <!-- History Section -->
    <?php if (session()->get('user_id')): ?>
    <div class="form-section">
        <h3 class="form-section-title">Historique récent</h3>
        <p style="color: var(--gray); text-align: center; padding: 2rem;">
            <a href="<?= site_url('/code/historique') ?>" style="color: var(--primary); font-weight: 600;">
                Voir votre historique complet →
            </a>
        </p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
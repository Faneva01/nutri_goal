<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="portefeuille-container">
    <!-- Header -->
    <div class="portefeuille-header">
        <h1>Recharger votre portefeuille</h1>
        <p>Achetez un code pour ajouter du solde à votre compte</p>
    </div>

    <!-- Alerts Container -->
    <div id="alerts-container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
                <span class="alert-close" onclick="this.parentElement.remove()">×</span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
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

    <!-- Form Section -->
    <form id="buy-form" method="post" action="<?= site_url('/code/achat') ?>" class="form-section">
        <h2 class="form-section-title">Détails d'achat</h2>

        <!-- Montant -->
        <div class="form-group">
            <label for="montant" class="form-label">Montant (Ar) *</label>
            <input type="number" 
                   step="100" 
                   min="1000" 
                   id="montant" 
                   name="montant" 
                   class="form-input" 
                   value="<?= esc(old('montant')) ?>"
                   placeholder="Entrez le montant souhaité"
                   required>
            <small style="color: var(--gray); display: block; margin-top: 0.5rem;">
                Montant minimum: 1000 Ar. Exemple: 5000, 10000, 50000...
            </small>
            <div id="montant-info" style="margin-top: 0.5rem; font-weight: 600; color: var(--success);"></div>
        </div>

        <!-- Moyen de paiement -->
        <div class="form-group">
            <label class="form-label">Moyen de paiement *</label>
            <div class="payment-methods">
                <div class="payment-method">
                    <input type="radio" id="mvola" name="moyen_paiement" value="mvola" <?= old('moyen_paiement') === 'mvola' ? 'checked' : '' ?> required>
                    <label for="mvola">
                        <span class="payment-icon">📱</span>
                        <span>MVola</span>
                    </label>
                </div>

                <div class="payment-method">
                    <input type="radio" id="airtel" name="moyen_paiement" value="airtel_money" <?= old('moyen_paiement') === 'airtel_money' ? 'checked' : '' ?>>
                    <label for="airtel">
                        <span class="payment-icon">📱</span>
                        <span>Airtel Money</span>
                    </label>
                </div>

                <div class="payment-method">
                    <input type="radio" id="orange" name="moyen_paiement" value="orange_money" <?= old('moyen_paiement') === 'orange_money' ? 'checked' : '' ?>>
                    <label for="orange">
                        <span class="payment-icon">📱</span>
                        <span>Orange Money</span>
                    </label>
                </div>

                <div class="payment-method">
                    <input type="radio" id="carte" name="moyen_paiement" value="carte_bancaire" <?= old('moyen_paiement') === 'carte_bancaire' ? 'checked' : '' ?>>
                    <label for="carte">
                        <span class="payment-icon">💳</span>
                        <span>Carte Bancaire</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Procéder au paiement
            </button>
            <a href="<?= site_url('/code/validation') ?>" class="btn btn-secondary">
                J'ai déjà un code
            </a>
        </div>
    </form>

    <!-- Info Section -->
    <div class="form-section" style="background: rgba(33, 150, 243, 0.05); border-left-color: #2196F3;">
        <h3 style="color: #2196F3; margin-bottom: 1rem;">ℹ️ Comment ça marche?</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 0.5rem 0;">
                <strong>1.</strong> Sélectionnez le montant que vous souhaitez recharger
            </li>
            <li style="padding: 0.5rem 0;">
                <strong>2.</strong> Choisissez votre moyen de paiement préféré
            </li>
            <li style="padding: 0.5rem 0;">
                <strong>3.</strong> Complétez le paiement pour recevoir votre code
            </li>
            <li style="padding: 0.5rem 0;">
                <strong>4.</strong> Utilisez le code pour ajouter du solde à votre portefeuille
            </li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
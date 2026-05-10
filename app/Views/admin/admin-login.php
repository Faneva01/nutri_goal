<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="admin-login-container">
    <div class="admin-login-card">
        <div class="admin-login-header">
            <h1 class="admin-login-title">Espace Administrateur</h1>
            <p class="admin-login-subtitle">Connexion sécurisée</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur!</strong> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Succès!</strong> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/admin/auth/verify') ?>" method="POST" class="admin-login-form" id="adminLoginForm">
            <?= csrf_field() ?>

            <div class="form-group admin-form-group">
                <label for="email" class="form-label admin-form-label">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input 
                    type="email" 
                    class="form-control admin-form-input <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                    id="email" 
                    name="email" 
                    placeholder="admin@nutri-goal.com"
                    value="<?= old('email') ?>"
                    required
                >
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <div class="invalid-feedback d-block">
                        <?= $validation->getError('email') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group admin-form-group">
                <label for="password" class="form-label admin-form-label">
                    <i class="fas fa-lock"></i> Mot de passe
                </label>
                <input 
                    type="password" 
                    class="form-control admin-form-input <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                    id="password" 
                    name="password" 
                    placeholder="Votre mot de passe"
                    required
                >
                <?php if (isset($validation) && $validation->hasError('password')): ?>
                    <div class="invalid-feedback d-block">
                        <?= $validation->getError('password') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-check admin-form-check">
                <input 
                    type="checkbox" 
                    class="form-check-input admin-form-check-input" 
                    id="remember" 
                    name="remember"
                >
                <label class="form-check-label admin-form-check-label" for="remember">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-admin-primary admin-btn-login w-100">
                <i class="fas fa-sign-in-alt"></i> Connexion
            </button>

            <div class="admin-login-footer">
                <a href="<?= base_url('/') ?>" class="admin-back-link">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </form>

        <div class="admin-login-security">
            <small class="text-muted">
                <i class="fas fa-shield-alt"></i> 
                Cette zone est protégée et réservée aux administrateurs autorisés.
            </small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

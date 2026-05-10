<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container auth-page">

    <section class="auth-card">

        <div class="auth-header">
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-subtitle">Accédez à votre compte Nutri Goal</p>
        </div>

        <form id="loginForm" class="form">

            <div id="err-global" class="error-global"></div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" name="email" class="input">
                <small class="error-msg" id="err-email"></small>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label>Mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="input" autocomplete="current-password">
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher le mot de passe" aria-pressed="false" title="Afficher / masquer">
                        <svg class="js-eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="js-eye-shut hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.5a3 3 0 004.2 4.2M9.88 9.88a3 3 0 105.29 5.29"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.34 6.34C3.9 8.2 2 12 2 12s3.58 8 10 8a9.77 9.77 0 004.7-1.1M17.66 17.66C20.1 15.8 22 12 22 12s-3.58-8-10-8a9.76 9.76 0 00-4.93 1.16"/>
                        </svg>
                    </button>
                </div>
                <small class="error-msg" id="err-mot_de_passe"></small>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Se connecter <i class="fas fa-arrow-right"></i>
            </button>

        </form>

        <div class="auth-divider">
            <span>OU</span>
        </div>

        <div class="social-buttons">
            <button type="button" class="btn-social btn-google" id="googleBtn">
                <i class="fab fa-google"></i>
                Google
            </button>
            <button type="button" class="btn-social btn-facebook" id="facebookBtn">
                <i class="fab fa-facebook-f"></i>
                Facebook
            </button>
        </div>

        <p class="auth-footer">
            Pas encore de compte ?
            <a href="/register" class="link">S'inscrire</a>
        </p>

    </section>

</main>

<?= $this->endSection() ?>
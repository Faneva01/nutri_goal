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
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="input">
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
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
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
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="input">
                <small class="error-msg" id="err-mot_de_passe"></small>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Se connecter
            </button>

        </form>

        <p class="auth-footer">
            Pas encore de compte ?
            <a href="/register" class="link">S'inscrire</a>
        </p>

    </section>

</main>

<?= $this->endSection() ?>
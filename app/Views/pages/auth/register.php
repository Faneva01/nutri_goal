<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container auth-page">

    <section class="auth-card">

        <div class="auth-header">
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-subtitle">
                Rejoignez Nutri Goal et trouvez votre régime idéal
            </p>
        </div>

        <!-- PROGRESSION -->
        <div class="register-progress">
            <div class="circles">
                <div class="progress-step active">
                    <div class="step-circle">1</div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step">
                    <div class="step-circle">2</div>
                </div>
            </div>
            <div class="texts">
                <span>Compte</span>
                <span>Santé</span>
            </div>
        </div>

        <form method="post" class="form" id="registerForm">

            <?= csrf_field() ?>

            <!-- STEP 1 -->
            <div class="form-step active" id="step-1">
                <div id="err-step1" class="error-global"></div>

                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" id="nom_complet" name="nom_complet" class="input">
                    <small class="error-msg" id="err-nom_complet"></small>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="input">
                    <small class="error-msg" id="err-email"></small>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" class="input">
                        <button type="button" class="toggle-password" id="togglePassword1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="error-msg" id="err-mot_de_passe"></small>
                </div>

                <div class="form-group">
                    <label>Confirmation</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" class="input">
                        <button type="button" class="toggle-password" id="togglePassword2">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="error-msg" id="err-confirm"></small>
                </div>

                <div class="form-group">
                    <label>Genre</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="genre" value="M">
                            <span>Homme</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="genre" value="F">
                            <span>Femme</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="genre" value="Autre">
                            <span>Autre</span>
                        </label>
                    </div>
                    <small class="error-msg" id="err-genre"></small>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="next-btn" class="btn btn-primary w-full">
                        Continuer <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="form-step" id="step-2">
                <div class="form-group">
                    <label>Taille (cm)</label>
                    <input type="number" id="taille" name="taille" class="input">
                    <small class="error-msg" id="err-taille"></small>
                </div>

                <div class="form-group">
                    <label>Poids (kg)</label>
                    <input type="number" id="poids" name="poids" class="input">
                    <small class="error-msg" id="err-poids"></small>
                </div>

                <div class="form-actions">
                    <button type="button" id="prev-btn" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <button type="submit" class="btn btn-primary">
                        S'inscrire <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>

        </form>

        <p class="auth-footer">
            Déjà un compte ?
            <a href="/login" class="link">Se connecter</a>
        </p>

    </section>

</main>

<?= $this->endSection() ?>
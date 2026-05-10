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

        <form action="/register" method="post" class="form" id="registerForm">

            <?= csrf_field() ?>

            <!-- STEP 1 -->
            <div class="form-step active" id="step-1">

                <div id="err-step1" class="error-global"></div>

                <!-- NOM -->
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" id="nom_complet" name="nom_complet" class="input">
                    <small class="error-msg" id="err-nom_complet"></small>
                </div>

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
                        <input type="password" id="mot_de_passe" name="mot_de_passe" class="input" autocomplete="new-password" minlength="6">
                        <button type="button" class="toggle-password" id="togglePassword1" aria-label="Afficher le mot de passe" aria-pressed="false" title="Afficher / masquer">
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

                <!-- GENRE -->
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
                        S'inscrire <i class="fas fa-arrow-right"></i>
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
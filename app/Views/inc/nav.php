<header class="site-header">
    <nav class="site-nav container">

        <!-- LOGO -->
        <a href="<?= base_url('/dashboard') ?>" class="nav-brand">
            <i class="fas fa-leaf"></i>
            NutriGoal
        </a>

        <!-- LIENS -->
        <div class="nav-links">

    <!-- DASHBOARD -->
    <a href="<?= base_url('/dashboard') ?>"
       class="nav-link <?= (uri_string() === 'dashboard' || uri_string() === 'dashboard-user') ? 'active' : '' ?>">
        <i class="fas fa-gauge-high"></i>
        Dashboard
    </a>

    <!-- NUTRITION -->
    <div class="nav-dropdown">

        <button class="nav-dropdown-btn">
            <i class="fas fa-bowl-rice"></i>
            Nutrition
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </button>

        <div class="nav-dropdown-menu">

            <a href="<?= base_url('/regimes') ?>" class="dropdown-link">
                <i class="fas fa-plate-wheat"></i>
                Régimes
            </a>

            <a href="<?= base_url('/recommend') ?>" class="dropdown-link">
                <i class="fas fa-wand-magic-sparkles"></i>
                Recommandations
            </a>

        </div>
    </div>

    <!-- PORTEFEUILLE -->
    <div class="nav-dropdown">

        <button class="nav-dropdown-btn">
            <i class="fas fa-wallet"></i>
            Portefeuille
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </button>

        <div class="nav-dropdown-menu">

            <a href="<?= base_url('/paiement') ?>" class="dropdown-link">
                <i class="fas fa-credit-card"></i>
                Paiement
            </a>

            <a href="<?= base_url('/paiement') ?>" class="dropdown-link">
                <i class="fas fa-bag-shopping"></i>
                Acheter un code
            </a>

            <a href="<?= base_url('/code/validation') ?>" class="dropdown-link">
                <i class="fas fa-circle-check"></i>
                Valider un code
            </a>

            <a href="<?= base_url('/code/historique') ?>" class="dropdown-link">
                <i class="fas fa-clock-rotate-left"></i>
                Historique
            </a>

        </div>
    </div>

    <!-- COMPTE -->
    <div class="nav-dropdown">

        <button class="nav-dropdown-btn">
            <i class="fas fa-circle-user"></i>
            Compte
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </button>

        <div class="nav-dropdown-menu">

            <a href="<?= base_url('/profil') ?>" class="dropdown-link">
                <i class="fas fa-id-card"></i>
                Mon profil
            </a>

            <a href="<?= base_url('/objectif/me') ?>" class="dropdown-link">
                <i class="fas fa-bullseye"></i>
                Mes objectifs
            </a>

        </div>
    </div>

    <!-- ACTIONS -->
    <div class="nav-actions">

        <span class="nav-solde">
            <i class="fas fa-coins"></i>
            <?= esc(number_format((float)(session()->get('solde') ?? 0), 0)) ?> Ar
        </span>

        <a href="<?= base_url('/logout') ?>" class="btn-nav-logout">
            <i class="fas fa-arrow-right-from-bracket"></i>
            Déconnexion
        </a>

    </div>

</div>

        <!-- BURGER -->
        <button class="nav-burger" id="navBurger" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>

    </nav>
</header>
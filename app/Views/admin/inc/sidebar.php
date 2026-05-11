<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <span class="logo-icon">🥗</span>
            <span class="logo-text">NutriGoal</span>
        </div>
        <div class="sidebar-user">
            <span class="user-avatar"><i class="fas fa-user-shield"></i></span>
            <span class="user-name"><?= esc($admin_name ?? 'Admin') ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-section">
                <span class="nav-section-title">Statistiques</span>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/stats/utilisateurs') ?>" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/stats/types') ?>" class="nav-link">
                    <i class="fas fa-user-tag"></i>
                    <span>Types Utilisateurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/stats/ca') ?>" class="nav-link">
                    <i class="fas fa-euro-sign"></i>
                    <span>Chiffre d'Affaires</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/stats/regimes') ?>" class="nav-link">
                    <i class="fas fa-utensils"></i>
                    <span>Régimes</span>
                </a>
            </li>

            <li class="nav-section">
                <span class="nav-section-title">Gestion</span>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/regimes') ?>" class="nav-link">
                    <i class="fas fa-list"></i>
                    <span>Régimes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/activites') ?>" class="nav-link">
                    <i class="fas fa-running"></i>
                    <span>Activités</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/codes') ?>" class="nav-link">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Codes Portefeuille</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/gold') ?>" class="nav-link">
                    <i class="fas fa-crown"></i>
                    <span>Utilisateurs Gold</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= base_url('admin/logout') ?>" class="logout-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>
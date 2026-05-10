<aside class="admin-sidebar">
    <div class="admin-sidebar-top">
        <a href="<?= base_url('/admin/dashboard') ?>" class="sidebar-brand">
            <span class="sidebar-logo"><i class="fas fa-seedling"></i></span>
            <div>
                <strong>NutriGoal</strong>
                <small>Back Office</small>
            </div>
        </a>
    </div>

    <nav class="admin-sidebar-nav">
        <a href="<?= base_url('/admin/dashboard') ?>" class="admin-sidebar-link">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= base_url('/admin/stats/usuarios') ?>" class="admin-sidebar-link">
            <i class="fas fa-users"></i>
            <span>Utilisateurs</span>
        </a>
        <a href="<?= base_url('/admin/stats/type-usuarios') ?>" class="admin-sidebar-link">
            <i class="fas fa-chart-pie"></i>
            <span>Simple / Gold</span>
        </a>
        <a href="<?= base_url('/admin/stats/chiffre-affaire') ?>" class="admin-sidebar-link">
            <i class="fas fa-money-bill-wave"></i>
            <span>CA</span>
        </a>
        <a href="<?= base_url('/admin/stats/regime') ?>" class="admin-sidebar-link">
            <i class="fas fa-leaf"></i>
            <span>Régimes</span>
        </a>
    </nav>

    <div class="admin-sidebar-footer">
        <div class="admin-sidebar-user">
            <div class="sidebar-user-icon"><i class="fas fa-user"></i></div>
            <div>
                <strong><?= esc($admin_name ?? 'Administrateur') ?></strong>
                <small>Connecté</small>
            </div>
        </div>
        <a href="<?= base_url('/admin/auth/logout') ?>" class="admin-sidebar-logout">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</aside>

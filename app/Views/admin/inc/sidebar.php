<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <a href="<?= base_url('/admin/dashboard') ?>">
            <i class="fas fa-seedling"></i>
            <span>NutriGoal <strong>Admin</strong></span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <!-- DASHBOARD -->
        <a href="<?= base_url('/admin/dashboard') ?>" class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

<!-- STATISTIQUES (Dropdown) -->
<div class="nav-group">
    <button class="nav-group-header">
        <i class="fas fa-chart-line"></i>
        <span>Statistiques</span>
        <i class="fas fa-chevron-right arrow"></i>
    </button>
    <div class="nav-group-content">
        <a href="<?= base_url('/admin/stats/usuarios') ?>" class="<?= uri_string() == 'admin/stats/usuarios' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Utilisateurs
        </a>
        <a href="<?= base_url('/admin/stats/type-usuarios') ?>" class="<?= uri_string() == 'admin/stats/type-usuarios' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Simple / Gold
        </a>
        <a href="<?= base_url('/admin/stats/chiffre-affaire') ?>" class="<?= uri_string() == 'admin/stats/chiffre-affaire' ? 'active' : '' ?>">
            <i class="fas fa-money-bill-wave"></i> Chiffre d'Affaire
        </a>
        <a href="<?= base_url('/admin/stats/regime') ?>" class="<?= uri_string() == 'admin/stats/regime' ? 'active' : '' ?>">
            <i class="fas fa-leaf"></i> Régimes
        </a>
    </div>
</div>

<!-- GESTION (Dropdown) -->
<div class="nav-group">
    <button class="nav-group-header">
        <i class="fas fa-tools"></i>
        <span>Gestion</span>
        <i class="fas fa-chevron-right arrow"></i>
    </button>
    <div class="nav-group-content">
        <a href="<?= base_url('/admin/regimes') ?>" class="<?= str_contains(uri_string(), 'admin/regimes') ? 'active' : '' ?>">
            <i class="fas fa-utensils"></i> Régimes
        </a>
        <a href="<?= base_url('/admin/activites') ?>" class="<?= str_contains(uri_string(), 'admin/activites') ? 'active' : '' ?>">
            <i class="fas fa-running"></i> Activités
        </a>
        <a href="<?= base_url('/admin/codes') ?>" class="<?= str_contains(uri_string(), 'admin/codes') ? 'active' : '' ?>">
            <i class="fas fa-ticket-alt"></i> Codes
        </a>
    </div>
</div>

        <div class="sidebar-divider"></div>

        <!-- LOGOUT -->
        <a href="<?= base_url('/admin/auth/logout') ?>" class="nav-link logout-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <small>© 2026 NutriGoal Admin</small>
    </div>
</aside>

<script>
    document.querySelectorAll('.nav-group-header').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.parentElement;
            group.classList.toggle('open');
            
            // Close other open groups
            document.querySelectorAll('.nav-group').forEach(otherGroup => {
                if (otherGroup !== group && otherGroup.classList.contains('open')) {
                    otherGroup.classList.remove('open');
                }
            });
        });
    });
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', (e) => {
        const sidebar = document.querySelector('.admin-sidebar');
        const header = document.querySelector('.admin-header-minimal');
        
        if (window.innerWidth <= 992 && 
            sidebar && 
            !sidebar.contains(e.target) && 
            !header.contains(e.target) &&
            !e.target.closest('.sidebar-brand') &&
            !e.target.closest('.nav-link')) {
            sidebar.classList.remove('open-mobile');
        }
    });
    
    // Toggle sidebar on mobile
    const toggleSidebar = () => {
        const sidebar = document.querySelector('.admin-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('open-mobile');
        }
    };
    
    // Add toggle button to header for mobile
    window.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('.admin-header-minimal');
        if (header && window.innerWidth <= 992) {
            const toggleBtn = document.createElement('button');
            toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
            toggleBtn.className = 'sidebar-toggle-mobile';
            toggleBtn.onclick = toggleSidebar;
            header.insertBefore(toggleBtn, header.firstChild);
        }
    });
</script>

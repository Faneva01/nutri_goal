<?php
$title = $title ?? 'Nutri Goal Admin';
$styles = $styles ?? [];
$scripts = $scripts ?? [];
$adminName = $adminName ?? session()->get('admin_name');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS global -->
    <link rel="stylesheet" href="<?= css_url('style.css') ?>">
    <link rel="stylesheet" href="<?= css_url('admin/admin-dashboard.css') ?>">
    
    <!-- CSS spécifiques page -->
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= css_url($style) ?>">
    <?php endforeach; ?>
</head>

<body class="admin-body <?php if(str_contains(uri_string(), 'admin/auth') || str_contains(uri_string(), 'admin/login')): ?>no-sidebar<?php endif; ?>">
    
    <div class="admin-shell">
        <!-- SIDEBAR -->
        <?php if(!str_contains(uri_string(), 'admin/auth') && !str_contains(uri_string(), 'admin/login')): ?>
            <?= view('admin/inc/sidebar', ['admin_name' => $adminName]) ?>
        <?php endif; ?>

        <!-- MAIN CONTENT AREA -->
        <div class="admin-main-wrapper">
            <main class="admin-page-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <?php if(!str_contains(uri_string(), 'admin/auth') && !str_contains(uri_string(), 'admin/login')): ?>
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.admin-sidebar');
            
            if (toggle && sidebar) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open-mobile');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 992) {
                        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                            sidebar.classList.remove('open-mobile');
                        }
                    }
                });
            }
        });
    </script>
    <?php endif; ?>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>

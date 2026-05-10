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

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= css_url('style.css') ?>">
    <link rel="stylesheet" href="<?= css_url('profil-page.css') ?>">
    <link rel="stylesheet" href="<?= css_url('admin/admin-layout.css') ?>">

    <!-- CSS spécifiques page -->
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= css_url($style) ?>">
    <?php endforeach; ?>
</head>

<body>
    <div class="admin-shell">
        <?= view('admin/inc/sidebar', ['admin_name' => $adminName]) ?>

        <div class="admin-page">
            <header class="navbar admin-navbar">
                <div class="navbar-left">
                    <a href="<?= base_url('/admin/dashboard') ?>" class="logo">
                        <span class="logo-icon"><i class="fas fa-leaf"></i></span>
                        <span class="logo-text">NutriGoal Admin</span>
                    </a>
                </div>
                <div class="navbar-right">
                    <a class="nav-link" href="<?= base_url('/') ?>">Retour au site</a>
                    <a class="avatar-btn" href="<?= base_url('/admin/auth/logout') ?>">
                        <span class="nav-avatar"><i class="fas fa-user-shield"></i></span>
                        <span><?= esc($adminName ?? 'Admin') ?></span>
                    </a>
                </div>
            </header>

            <main class="admin-content container">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <?php foreach ($scripts as $script): ?>
        <script src="<?= js_url($script) ?>"></script>
    <?php endforeach; ?>
</body>

</html>

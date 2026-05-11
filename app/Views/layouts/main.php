<?php
$title = $title ?? 'Nutri Goal';
$styles = $styles ?? [];
$scripts = $scripts ?? [];
$navView = $navView ?? 'inc/nav';
$show_navbar = $show_navbar ?? true;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= css_url('style.css') ?>">
    <link rel="stylesheet" href="<?= css_url('nav.css') ?>">

    <!-- CSS spécifiques page -->
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= css_url($style) ?>">
    <?php endforeach; ?>

    <!-- CDN Font Awesom -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php if ($show_navbar): ?>
        <!-- NAV (composant global) -->
        <?= view($navView, ['user' => $user ?? null]) ?>
    <?php endif; ?>

    <!-- CONTENU PAGE -->
    <?= $this->renderSection('content') ?>

    <!-- JS GLOBALS -->
    <script>
      window.baseUrl = "<?= rtrim(base_url(), '/') ?>";
      window.csrfToken = "<?= csrf_hash() ?>";
      window.csrfHeader = "<?= csrf_header() ?>";
    </script>
    
    <!-- JS système -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    
    <!-- Toast Container -->
    <div id="toast" class="toast"></div>

    <!-- JS spécifiques page -->
    <?php foreach ($scripts as $script): ?>
        <script src="<?= js_url($script) ?>"></script>
    <?php endforeach; ?>
</body>
</html>
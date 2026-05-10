<?php
$title = $title ?? 'Nutri Goal';
$styles = $styles ?? [];
$scripts = $scripts ?? [];
$navView = $navView ?? 'inc/nav_profil';
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

    <!-- CSS spécifiques page -->
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= css_url($style) ?>">
    <?php endforeach; ?>
</head>

<body>

    <?php if ($show_navbar): ?>
        <!-- NAV (composant global) -->
        <?= view($navView, ['user' => $user ?? null]) ?>
    <?php endif; ?>

    <!-- CONTENU PAGE -->
    <?= $this->renderSection('content') ?>

    <!-- JS spécifiques page -->
    <?php foreach ($scripts as $script): ?>
        <script src="<?= js_url($script) ?>"></script>
    <?php endforeach; ?>

</body>
</html>
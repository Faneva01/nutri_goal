<?php
$title = $title ?? 'Nutri Goal';
$styles = $styles ?? [];
$scripts = $scripts ?? [];
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

    <!-- NAVBAR -->
    <?php if ($show_navbar): ?>
        <?= view('inc/nav') ?>
    <?php endif; ?>

    <!-- CONTENU -->
    <?= $this->renderSection('content') ?>

    <!-- JS spécifiques -->
    <?php foreach ($scripts as $script): ?>
        <script src="<?= js_url($script) ?>"></script>
    <?php endforeach; ?>

</body>
</html>
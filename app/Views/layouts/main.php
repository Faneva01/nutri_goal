<?php
$title = $title ?? 'Nutri Goal';
$styles = $styles ?? [];
$scripts = $scripts ?? [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= css_url('style.css') ?>">

    <!-- CSS spécifiques page -->
    <?php foreach ($styles as $style): ?>
        <link rel="stylesheet" href="<?= css_url($style) ?>">
    <?php endforeach; ?>
</head>

<body>

    <!-- NAV (composant global) -->
    <?= view('inc/nav') ?>

    <!-- CONTENU PAGE -->
    <?= $this->renderSection('content') ?>

    <!-- JS spécifiques page -->
    <?php foreach ($scripts as $script): ?>
        <script src="<?= js_url($script) ?>"></script>
    <?php endforeach; ?>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Validation du code portefeuille') ?></title>
</head>
<body>
    <h1>Validation du code portefeuille</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;"><?= esc(session()->getFlashdata('success')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;"><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if (!empty($errors) && is_array($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="<?= site_url('/code/validation') ?>">
        <div>
            <label for="code">Entrez votre code portefeuille</label><br>
            <input type="text" id="code" name="code" value="<?= esc(old('code')) ?>" required>
        </div>

        <div>
            <button type="submit">Valider le code</button>
        </div>
    </form>

    <p><a href="<?= site_url('/code/achat') ?>">Acheter un nouveau code</a></p>
</body>
</html>
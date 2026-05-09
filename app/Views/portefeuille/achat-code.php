<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Achat de code portefeuille') ?></title>
</head>
<body>
    <h1>Achat de code portefeuille</h1>

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

    <form method="post" action="<?= site_url('/code/achat') ?>">
        <div>
            <label for="montant">Montant (Ar)</label><br>
            <input type="number" step="0.01" id="montant" name="montant" value="<?= esc(old('montant')) ?>" required>
        </div>

        <div>
            <label for="moyen_paiement">Moyen de paiement</label><br>
            <select id="moyen_paiement" name="moyen_paiement" required>
                <option value="">-- Sélectionnez --</option>
                <option value="mvola" <?= old('moyen_paiement') === 'mvola' ? 'selected' : '' ?>>MVola</option>
                <option value="airtel_money" <?= old('moyen_paiement') === 'airtel_money' ? 'selected' : '' ?>>Airtel Money</option>
                <option value="orange_money" <?= old('moyen_paiement') === 'orange_money' ? 'selected' : '' ?>>Orange Money</option>
                <option value="carte_bancaire" <?= old('moyen_paiement') === 'carte_bancaire' ? 'selected' : '' ?>>Carte Bancaire</option>
            </select>
        </div>

        <div>
            <button type="submit">Acheter un code</button>
        </div>
    </form>

    <p><a href="<?= site_url('/code/validation') ?>">J'ai déjà un code, je veux le valider</a></p>
</body>
</html>
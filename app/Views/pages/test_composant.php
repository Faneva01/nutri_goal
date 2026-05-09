<?=  $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-3">

        <!-- TITRES -->
        <section class="mb-3">
            <h1 class="title">Titre Principal</h1>
            <p class="subtitle">
                Ceci est un sous-titre pour tester le style du texte.
            </p>
        </section>

        <!-- BOUTONS -->
        <section class="mb-3">
            <h2 class="title">Boutons</h2>

            <div class="flex gap-md mt-2">
                <button class="btn btn-primary">
                    Bouton Primary
                </button>

                <button class="btn btn-secondary">
                    Bouton Secondary
                </button>

                <button class="btn btn-outline">
                    Bouton Outline
                </button>
            </div>
        </section>

        <!-- INPUTS -->
        <section class="mb-3">
            <h2 class="title">Inputs</h2>

            <div class="input-group mt-2">
                <label>Nom</label>
                <input type="text" class="input" placeholder="Entrez votre nom">
            </div>

            <div class="input-group mt-2">
                <label>Email</label>
                <input type="email" class="input" placeholder="Entrez votre email">
            </div>

            <div class="input-group mt-2">
                <label>Mot de passe</label>
                <input type="password" class="input" placeholder="Mot de passe">
            </div>
        </section>

        <!-- BADGES -->
        <section class="mb-3">
            <h2 class="title">Badges</h2>

            <div class="flex gap-md mt-2">
                <span class="badge badge-success">
                    Succès
                </span>

                <span class="badge badge-warning">
                    Attention
                </span>
            </div>
        </section>

        <!-- CARDS -->
        <section class="mb-3">
            <h2 class="title">Cards</h2>

            <div class="flex gap-md mt-2">

                <div class="card">
                    <h3 class="title">Régime Keto</h3>

                    <p class="subtitle">
                        Régime pour réduction rapide du poids.
                    </p>

                    <button class="btn btn-primary mt-2">
                        Voir plus
                    </button>
                </div>

                <div class="card">
                    <h3 class="title">Prise de masse</h3>

                    <p class="subtitle">
                        Régime adapté pour augmenter le poids.
                    </p>

                    <button class="btn btn-secondary mt-2">
                        Choisir
                    </button>
                </div>

            </div>
        </section>

        <!-- FLEX -->
        <section class="mb-3">
            <h2 class="title">Flex Utilities</h2>

            <div class="flex-between card mt-2">

                <div>
                    <h3>Gauche</h3>
                </div>

                <div>
                    <button class="btn btn-primary">
                        Action
                    </button>
                </div>

            </div>
        </section>

        <!-- TABLE -->
        <section class="mb-3">
            <h2 class="title">Tableau</h2>

            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Objectif</th>
                        <th>Durée</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Jean</td>
                        <td>Perte de poids</td>
                        <td>30 jours</td>
                    </tr>

                    <tr>
                        <td>Sarah</td>
                        <td>IMC idéal</td>
                        <td>45 jours</td>
                    </tr>

                    <tr>
                        <td>Lucas</td>
                        <td>Prise de masse</td>
                        <td>60 jours</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </div>
<?= $this->endSection() ?>
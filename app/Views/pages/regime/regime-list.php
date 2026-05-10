<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container">

<section class="regime-section">

    <div class="regime-header">
        <h1 class="title">Nos Régimes</h1>
        <p class="subtitle">Trouvez le régime adapté à vos objectifs</p>
    </div>

    <div class="regime-filters">
        <div class="filter-group">
            <label>Type</label>
            <select id="filterType" class="input">
                <option value="">Tous</option>
                <option value="perte">Perte de poids</option>
                <option value="prise">Prise de poids</option>
                <option value="maintien">Maintien</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Intensité</label>
            <select id="filterIntensite" class="input">
                <option value="">Tous</option>
                <option value="legere">Légère</option>
                <option value="moderee">Modérée</option>
                <option value="intense">Intense</option>
            </select>
        </div>

        <button id="applyFilters" class="btn btn-primary">Filtrer</button>
    </div>

    <div class="regimes-grid" id="regimesContainer">

        <?php if (!empty($regimes)): ?>
            <?php foreach ($regimes as $regime): ?>
                <div class="regime-card card">

                    <div class="regime-header-card">
                        <h3><?= esc($regime['nom']) ?></h3>

                        <span class="badge badge-<?= esc($regime['type_regime']) ?>">
                            <?= ucfirst($regime['type_regime']) ?>
                        </span>
                    </div>

                    <p class="regime-description">
                        <?= esc(substr($regime['description'] ?? '', 0, 100)) ?>
                    </p>

                    <div class="regime-stats">
                        <div class="stat">
                            <span class="label">Variation</span>
                            <span class="value">
                                <?= esc($regime['variation_quotidienne']) ?> kg/jour
                            </span>
                        </div>

                        <div class="stat">
                            <span class="label">Intensité</span>
                            <span class="value">
                                <?= ucfirst($regime['intensite']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="regime-composition">
                        <span class="comp-item">🥩 <?= esc($regime['pourcentage_viande']) ?>%</span>
                        <span class="comp-item">🐟 <?= esc($regime['pourcentage_poisson']) ?>%</span>
                        <span class="comp-item">🍗 <?= esc($regime['pourcentage_volaille']) ?>%</span>
                    </div>

                    <div class="regime-price">
                        <span class="prix">
                            <?= number_format($regime['prix_jour'] ?? 0, 2) ?> €/jour
                        </span>
                    </div>

                    <a href="/regime/<?= $regime['id'] ?>" class="btn btn-primary w-full">
                        Voir plus
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>Aucun régime trouvé</p>
            </div>
        <?php endif; ?>

    </div>

</section>

</main>

<?= $this->endSection() ?>
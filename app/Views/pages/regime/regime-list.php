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

                    <div class="regime-header-card" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                        <h3 style="margin:0; font-size: 18px; font-weight: 800; color: #2d2926;"><?= esc($regime['nom']) ?></h3>

                        <span class="badge badge-<?= esc($regime['type_regime']) ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                            <?= ucfirst($regime['type_regime']) ?>
                        </span>
                    </div>

                    <p class="regime-description" style="font-size: 14px; color: #666; margin-bottom: 20px; line-height: 1.5; min-height: 42px;">
                        <?= esc(substr($regime['description'] ?? '', 0, 100)) ?>...
                    </p>

                    <div class="regime-stats" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; padding: 12px; background: #faf7f5; border-radius: 12px;">
                        <div class="stat">
                            <span class="label" style="display: block; font-size: 11px; color: #9a938e; font-weight: 700; text-transform: uppercase;">Variation</span>
                            <span class="value" style="font-size: 14px; font-weight: 800; color: #2d2926;">
                                <?= esc($regime['variation_quotidienne']) ?> kg/j
                            </span>
                        </div>

                        <div class="stat">
                            <span class="label" style="display: block; font-size: 11px; color: #9a938e; font-weight: 700; text-transform: uppercase;">Intensité</span>
                            <span class="value" style="font-size: 14px; font-weight: 800; color: #2d2926;">
                                <?= ucfirst($regime['intensite']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="regime-composition" style="display: flex; gap: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 700;">
                        <span class="comp-item" style="color: #e67e22;">🥩 <?= esc($regime['pourcentage_viande']) ?>%</span>
                        <span class="comp-item" style="color: #3498db;">🐟 <?= esc($regime['pourcentage_poisson']) ?>%</span>
                        <span class="comp-item" style="color: #f1c40f;">🍗 <?= esc($regime['pourcentage_volaille']) ?>%</span>
                    </div>

                    <div class="regime-price" style="border-top: 1px solid #f0ece8; padding-top: 16px; margin-bottom: 20px; display: flex; align-items: baseline; gap: 4px;">
                        <span class="prix" style="font-size: 20px; font-weight: 800; color: #E17864;">
                            <?= format_currency_smart($regime['prix_jour'] ?? 0) ?>
                        </span>
                        <span style="font-size: 12px; color: #9a938e; font-weight: 600;">/ jour</span>
                    </div>

                    <a href="<?= base_url('/regimes/'.$regime['id']) ?>" class="btn btn-primary w-full" style="padding: 12px; font-weight: 800; border-radius: 12px;">
                        Détails du programme <i class="fas fa-chevron-right" style="font-size: 10px; margin-left: 4px;"></i>
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
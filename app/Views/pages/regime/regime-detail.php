<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container">

<section class="regime-detail">

<a href="<?= base_url('/regimes') ?>" class="back-link">← Retour aux régimes</a>

<div class="regime-detail-header">

    <span class="badge badge-<?= esc($regime['type_regime']) ?>">
        <?= ucfirst($regime['type_regime']) ?>
    </span>

    <h1><?= esc($regime['nom']) ?></h1>

    <p class="subtitle">
        <?= esc($regime['description']) ?>
    </p>

</div>

<div class="regime-detail-content">

    <div class="regime-detail-main">

        <div class="detail-section">
            <h3>Caractéristiques</h3>

            <div class="characteristics">

                <div class="char-item">
                    <span>Variation</span>
                    <strong><?= esc($regime['variation_quotidienne']) ?> kg/jour</strong>
                </div>

                <div class="char-item">
                    <span>Intensité</span>
                    <strong><?= ucfirst($regime['intensite']) ?></strong>
                </div>

                <div class="char-item">
                    <span>Prix</span>
                    <strong><?= number_format($regime['prix_jour'], 2) ?> Ar</strong>
                </div>

            </div>
        </div>

        <?php if (!empty($activities)): ?>
        <div class="detail-section">
            <h3>Activités</h3>

            <div class="activities-list">

                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">

                        <h4><?= esc($activity['nom']) ?></h4>

                        <p><?= esc($activity['description']) ?></p>

                        <div class="activity-details">
                            <span>⏱ <?= esc($activity['duree_minutes']) ?> min</span>
                            <span>🔥 <?= esc($activity['calories_brulees']) ?> cal</span>
                            <span>📅 <?= esc($activity['frequence_par_semaine']) ?>x</span>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        <?php endif; ?>

    </div>

    <div class="regime-detail-sidebar">

        <div class="purchase-card card">

            <h3>Achat régime</h3>

            <div class="duration-selector">
                <?php foreach ([7,14,30,60,90] as $j): ?>
                    <button class="duration-btn" data-jours="<?= $j ?>">
                        <?= $j ?> jours
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="price-display">
                <div class="price-row total">
                    <span>Total</span>
                    <span id="prixTotal">-</span>
                </div>
            </div>

            <button id="buyBtn" class="btn btn-primary w-full" disabled>
                Acheter
            </button>

        </div>

    </div>

</div>

</section>

</main>

<?= $this->endSection() ?>
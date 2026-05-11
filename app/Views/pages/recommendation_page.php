<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container">

<section class="recommendation-page" style="margin-top:40px; margin-bottom: 60px;">

    <div class="page-header" style="text-align:center; margin-bottom: 40px;">
        <h1 style="font-size: 32px; color: #333;"><i class="fas fa-magic" style="color: #E17864;"></i> Votre Programme Personnalisé</h1>
        <p class="subtitle" style="color: #666; font-size: 18px;">Basé sur votre IMC actuel et votre objectif de <?= esc($result['type_regime'] ?? '') ?></p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-warning" style="padding: 20px; border-radius: 8px; background: #FFF9E6; border: 1px solid #FFE699; color: #856404; text-align: center;">
            <i class="fas fa-exclamation-circle"></i> <?= esc($error) ?>
            <br><br>
            <a href="<?= base_url('/profil') ?>" class="btn btn-primary">Définir mon objectif</a>
        </div>
    <?php elseif ($result['success']): ?>

        <div class="recommendation-grid" style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
            
            <div class="main-plan">
                <article class="dash-panel" style="padding: 30px;">
                    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                        <div>
                            <h2 style="margin:0;"><i class="fas fa-leaf"></i> <?= esc($result['regime']['nom']) ?></h2>
                            <p class="sub" style="margin-top:5px;"><?= esc($result['regime']['description']) ?></p>
                        </div>
                        <span class="badge badge-<?= esc($result['regime']['type_regime']) ?>" style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                            <?= strtoupper($result['regime']['type_regime']) ?>
                        </span>
                    </div>

                    <div class="plan-details" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; background: #f9f9f9; padding: 20px; border-radius: 12px;">
                        <div class="detail-item">
                            <small style="display:block; color:#888; margin-bottom:5px;">Durée estimée</small>
                            <strong style="font-size: 20px; color: #E17864;"><?= esc($result['duree']) ?> jours</strong>
                        </div>
                        <div class="detail-item">
                            <small style="display:block; color:#888; margin-bottom:5px;">Variation cible</small>
                            <strong style="font-size: 20px; color: #3498db;"><?= esc(abs($result['variation'])) ?> kg</strong>
                        </div>
                        <div class="detail-item">
                            <small style="display:block; color:#888; margin-bottom:5px;">Intensité</small>
                            <strong style="font-size: 20px; color: #27ae60;"><?= ucfirst($result['regime']['intensite']) ?></strong>
                        </div>
                    </div>

                    <h3 style="margin-bottom: 15px; font-size: 18px;"><i class="fas fa-running"></i> Activités suggérées</h3>
                    <div class="activities-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-card" style="padding: 15px; border: 1px solid #eee; border-radius: 10px;">
                                <h4 style="margin:0 0 5px 0; font-size: 15px;"><?= esc($activity['nom']) ?></h4>
                                <p style="font-size: 12px; color: #777; margin-bottom: 8px;"><?= esc($activity['description']) ?></p>
                                <div style="display:flex; gap:10px; font-size: 11px; font-weight: 600; color: #E17864;">
                                    <span><i class="fas fa-clock"></i> <?= esc($activity['duree_minutes']) ?>m</span>
                                    <span><i class="fas fa-fire"></i> <?= esc($activity['calories_brulees']) ?>kcal</span>
                                    <span><i class="fas fa-calendar-check"></i> <?= esc($activity['frequence_par_semaine']) ?>x/sem</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="export-actions" style="border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <p style="color: #666; font-size: 14px;"><i class="fas fa-info-circle"></i> Ce programme est calculé selon vos données de santé.</p>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-file-pdf"></i> Exporter en PDF (Imprimer)
                        </button>
                    </div>
                </article>
            </div>

            <aside class="sidebar-summary">
                <div class="dash-panel" style="padding: 25px; border: 2px solid #E17864; background: #FFF9F7;">
                    <h3 style="margin-top:0; font-size: 18px; color: #E17864;"><i class="fas fa-shopping-cart"></i> Récapitulatif</h3>
                    <div class="summary-row" style="display:flex; justify-content:space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px dashed #E17864;">
                        <span>Prix du régime</span>
                        <strong><?= number_format($result['regime']['prix_jour'] * $result['duree'], 0) ?> Ar</strong>
                    </div>
                    
                    <?php if ($result['gold']): ?>
                        <div class="summary-row" style="display:flex; justify-content:space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px dashed #E17864; color: #27ae60;">
                            <span>Remise Gold (-15%)</span>
                            <strong>- <?= number_format(($result['regime']['prix_jour'] * $result['duree']) * 0.15, 0) ?> Ar</strong>
                        </div>
                    <?php endif; ?>

                    <div class="summary-row total" style="display:flex; justify-content:space-between; margin-bottom: 25px; font-size: 20px; font-weight: 800; color: #333;">
                        <span>Total</span>
                        <span><?= number_format($result['prix_total'], 0) ?> Ar</span>
                    </div>

                    <form action="<?= base_url('/regimes/'.$result['regime']['id'].'/subscribe') ?>" method="POST" id="subscribeForm">
                        <input type="hidden" name="duree_jours" value="<?= $result['duree'] ?>">
                        <input type="hidden" name="poids_initial" value="<?= $user['poids'] ?>">
                        <input type="hidden" name="poids_cible" value="<?= $objectif['poids_cible'] ?>">
                        <input type="hidden" name="prix_total" value="<?= $result['prix_total'] ?>">
                        
                        <button type="submit" class="btn btn-primary w-full" style="padding: 15px; font-size: 16px; font-weight: 700;">
                            Activer ce programme
                        </button>
                    </form>
                </div>

                <div class="info-box" style="margin-top: 20px;">
                    <strong><i class="fas fa-lightbulb" style="color:#FAB863;"></i> Astuce</strong>
                    <p style="font-size: 13px; color: #666; margin-top: 5px;">Le saviez-vous ? Boire un verre d'eau avant chaque repas aide à mieux réguler l'appétit.</p>
                </div>
            </aside>

        </div>

    <?php else: ?>
        <div class="alert alert-danger" style="text-align:center; padding: 30px;">
            <i class="fas fa-frown" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
            <?= esc($result['message']) ?>
            <br><br>
            <a href="<?= base_url('/regimes') ?>" class="btn btn-secondary">Voir tous les régimes</a>
        </div>
    <?php endif; ?>

</section>

</main>

<style>
@media print {
    .site-header, .nav-actions, .sidebar-summary, .export-actions button, .back-link, .nav-burger {
        display: none !important;
    }
    body { background: white !important; }
    .container { width: 100% !important; max-width: none !important; padding: 0 !important; }
    .recommendation-grid { display: block !important; }
    .dash-panel { border: 1px solid #eee !important; box-shadow: none !important; }
}
</style>

<script>
document.getElementById('subscribeForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Activation...';

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        });
        const data = await res.json();
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => {
                window.location.href = '<?= base_url('/dashboard') ?>';
            }, 1000);
        } else {
            showToast('error', data.message);
            btn.disabled = false;
            btn.innerHTML = 'Activer ce programme';
        }
    } catch (err) {
        showToast('error', 'Une erreur est survenue.');
        btn.disabled = false;
        btn.innerHTML = 'Activer ce programme';
    }
});
</script>

<?= $this->endSection() ?>
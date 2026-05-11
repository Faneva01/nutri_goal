<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="panel-header">
    <div class="admin-header-content">
        <h1 class="admin-dashboard-title">
            <i class="fas fa-utensils"></i> <?= esc($title) ?>
        </h1>
        <p class="admin-dashboard-subtitle"><?= isset($regime) ? 'Modifier les détails du régime' : 'Créer un nouveau programme alimentaire' ?></p>
    </div>
    <div class="admin-header-actions">
        <a href="<?= base_url('/admin/regimes') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="dash-panel">
    <form action="<?= isset($regime) ? base_url('/admin/regimes/update/'.$regime['id']) : base_url('/admin/regimes/store') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Nom du régime</label>
                <input type="text" name="nom" class="input" placeholder="ex: Régime Keto Rapide" value="<?= esc($regime['nom'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Type de régime</label>
                <select name="type_regime" class="input">
                    <option value="perte" <?= (isset($regime) && $regime['type_regime'] == 'perte') ? 'selected' : '' ?>>Perte de poids</option>
                    <option value="prise" <?= (isset($regime) && $regime['type_regime'] == 'prise') ? 'selected' : '' ?>>Prise de masse</option>
                    <option value="maintien" <?= (isset($regime) && $regime['type_regime'] == 'maintien') ? 'selected' : '' ?>>Maintien</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Intensité</label>
                <select name="intensite" class="input">
                    <option value="legere" <?= (isset($regime) && $regime['intensite'] == 'legere') ? 'selected' : '' ?>>Légère</option>
                    <option value="moderee" <?= (isset($regime) && $regime['intensite'] == 'moderee') ? 'selected' : '' ?>>Modérée</option>
                    <option value="intense" <?= (isset($regime) && $regime['intensite'] == 'intense') ? 'selected' : '' ?>>Intense</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Variation quotidienne (kg/jour)</label>
                <input type="number" step="0.01" name="variation_quotidienne" class="input" value="<?= esc($regime['variation_quotidienne'] ?? '0.1') ?>" required>
                <small class="text-muted">Utilisez une valeur positive pour la prise, négative pour la perte (ex: -0.2)</small>
            </div>
            
            <div class="form-group">
                <label>Prix par jour (Ar)</label>
                <input type="number" name="prix_jour" class="input" value="<?= esc($regime['prix_jour'] ?? '10000') ?>" required>
            </div>
            
            <div class="form-group" style="display:flex; align-items:center; gap:10px; padding-top:35px;">
                <input type="checkbox" name="actif" id="actif" style="width:20px; height:20px; accent-color:var(--admin-primary);" <?= (!isset($regime) || $regime['actif']) ? 'checked' : '' ?>>
                <label for="actif" style="margin-bottom:0;">Régime actif et visible</label>
            </div>
        </div>

        <h3 class="section-title">Composition du régime (%)</h3>
        <div class="form-grid-3">
            <div class="form-group">
                <label>% Viande</label>
                <input type="number" name="pourcentage_viande" class="input" value="<?= esc($regime['pourcentage_viande'] ?? '33') ?>" required>
            </div>
            <div class="form-group">
                <label>% Poisson</label>
                <input type="number" name="pourcentage_poisson" class="input" value="<?= esc($regime['pourcentage_poisson'] ?? '33') ?>" required>
            </div>
            <div class="form-group">
                <label>% Volaille</label>
                <input type="number" name="pourcentage_volaille" class="input" value="<?= esc($regime['pourcentage_volaille'] ?? '34') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description détaillée</label>
            <textarea name="description" class="input" rows="4" placeholder="Décrivez les bénéfices et le contenu du régime..."><?= esc($regime['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer le Régime
            </button>
        </div>
    </form>
</div>

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px; }
    .section-title { font-size: 18px; font-weight: 800; color: #2d2926; margin: 32px 0 16px; padding-bottom: 8px; border-bottom: 2px solid #f0ece8; }
    .form-actions { margin-top: 32px; display: flex; justify-content: flex-end; }
    .text-muted { font-size: 12px; color: #9a938e; display: block; margin-top: 4px; }
</style>

<?= $this->endSection() ?>
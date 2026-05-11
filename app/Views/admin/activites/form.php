<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="panel-header">
    <div class="admin-header-content">
        <h1 class="admin-dashboard-title">
            <i class="fas fa-running"></i> <?= esc($title) ?>
        </h1>
        <p class="admin-dashboard-subtitle"><?= isset($activite) ? 'Modifier les détails de l\'activité' : 'Créer une nouvelle activité sportive' ?></p>
    </div>
    <div class="admin-header-actions">
        <a href="<?= base_url('/admin/activites') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="dash-panel">
    <form action="<?= isset($activite) ? base_url('/admin/activites/update/'.$activite['id']) : base_url('/admin/activites/store') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Nom de l'activité</label>
                <input type="text" name="nom" class="input" placeholder="ex: Natation, Course à pied" value="<?= esc($activite['nom'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Durée par défaut (minutes)</label>
                <input type="number" name="duree_minutes" class="input" value="<?= esc($activite['duree_minutes'] ?? '30') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Intensité</label>
                <select name="intensite" class="input">
                    <option value="faible" <?= (isset($activite) && $activite['intensite'] == 'faible') ? 'selected' : '' ?>>Faible</option>
                    <option value="modere" <?= (isset($activite) && $activite['intensite'] == 'modere') ? 'selected' : '' ?>>Modérée</option>
                    <option value="intense" <?= (isset($activite) && $activite['intensite'] == 'intense') ? 'selected' : '' ?>>Intense</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Calories brûlées (estimées)</label>
                <input type="number" name="calories_brulees" class="input" value="<?= esc($activite['calories_brulees'] ?? '200') ?>" required>
            </div>
            
            <div class="form-group" style="display:flex; align-items:center; gap:10px; padding-top:35px;">
                <input type="checkbox" name="actif" id="actif" style="width:20px; height:20px; accent-color:var(--admin-primary);" <?= (!isset($activite) || $activite['actif']) ? 'checked' : '' ?>>
                <label for="actif" style="margin-bottom:0;">Activité active et visible</label>
            </div>
        </div>

        <div class="form-group">
            <label>Description et recommandations</label>
            <textarea name="description" class="input" rows="4" placeholder="Décrivez l'activité et ses bienfaits..."><?= esc($activite['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer l'Activité
            </button>
        </div>
    </form>
</div>

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
    .form-actions { margin-top: 32px; display: flex; justify-content: flex-end; }
</style>

<?= $this->endSection() ?>
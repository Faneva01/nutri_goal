<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="panel-header">
    <div class="admin-header-content">
        <h1 class="admin-dashboard-title">
            <i class="fas fa-ticket-alt"></i> <?= esc($title) ?>
        </h1>
        <p class="admin-dashboard-subtitle">Générez des codes de recharge sécurisés pour vos utilisateurs</p>
    </div>
    <div class="admin-header-actions">
        <a href="<?= base_url('/admin/codes') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="dash-panel" style="max-width: 600px; margin: 0 auto;">
    <form action="<?= base_url('/admin/codes/store') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label>Montant des codes (Ar)</label>
            <input type="number" name="montant" class="input" value="10000" step="100" required>
            <small class="text-muted">Le montant qui sera crédité sur le portefeuille de l'utilisateur.</small>
        </div>
        
        <div class="form-group">
            <label>Quantité à générer</label>
            <input type="number" name="quantite" class="input" value="1" min="1" max="100" required>
            <small class="text-muted">Nombre de codes uniques à créer en une seule fois (Max 100).</small>
        </div>
        
        <div class="form-actions" style="margin-top: 32px;">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fas fa-magic"></i> Générer les codes maintenant
            </button>
        </div>
    </form>
</div>

<style>
    .text-muted { font-size: 12px; color: #9a938e; display: block; margin-top: 4px; }
</style>

<?= $this->endSection() ?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="panel-header">
    <div class="admin-header-content">
        <h1 class="admin-dashboard-title">
            <i class="fas fa-running"></i> <?= esc($title) ?>
        </h1>
        <p class="admin-dashboard-subtitle">Gérez les activités sportives suggérées aux utilisateurs</p>
    </div>
    <div class="admin-header-actions">
        <a href="<?= base_url('/admin/activites/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Activité
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="dash-panel">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Durée (min)</th>
                <th>Intensité</th>
                <th>Calories</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($activites as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><strong><?= esc($a['nom']) ?></strong></td>
                <td><?= $a['duree_minutes'] ?> min</td>
                <td><span class="badge badge-info"><?= ucfirst($a['intensite']) ?></span></td>
                <td><?= $a['calories_brulees'] ?> kcal</td>
                <td>
                    <span class="badge <?= $a['actif'] ? 'badge-success' : 'badge-danger' ?>">
                        <?= $a['actif'] ? 'Actif' : 'Inactif' ?>
                    </span>
                </td>
                <td class="action-btns">
                    <a href="<?= base_url('/admin/activites/edit/'.$a['id']) ?>" class="btn btn-secondary btn-sm" title="Modifier"><i class="fas fa-edit"></i></a>
                    <a href="<?= base_url('/admin/activites/delete/'.$a['id']) ?>" class="btn btn-outline btn-sm" title="Supprimer" onclick="return confirm('Supprimer cette activité ?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
    .data-table th, .data-table td { padding: 16px; border-bottom: 1px solid #f0ece8; text-align: left; font-size: 14px; }
    .data-table th { background: #faf7f5; font-weight: 700; color: #555; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
    .badge-info { background: #e3f2fd; color: #1976d2; }
    .badge-success { background: #f0fff4; color: #27ae60; }
    .badge-danger { background: #fff5f5; color: #e74c3c; }
    .action-btns { display: flex; gap: 8px; }
    .btn-sm { padding: 8px 12px; font-size: 12px; }
</style>

<?= $this->endSection() ?>

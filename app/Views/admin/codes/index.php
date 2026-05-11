<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="panel-header">
    <div class="admin-header-content">
        <h1 class="admin-dashboard-title">
            <i class="fas fa-ticket-alt"></i> <?= esc($title) ?>
        </h1>
        <p class="admin-dashboard-subtitle">Gérez et validez les codes de recharge pour les utilisateurs</p>
    </div>
    <div class="admin-header-actions">
        <a href="<?= base_url('/admin/codes/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Générer des Codes
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
                <th>Code</th>
                <th>Montant</th>
                <th>Status</th>
                <th>Utilisé par (ID)</th>
                <th>Date Création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($codes as $c): ?>
            <tr>
                <td><code style="background: #faf7f5; padding: 4px 8px; border-radius: 4px; font-weight: 700; color: #E17864;"><?= $c['code'] ?></code></td>
                <td><strong><?= number_format($c['montant'], 0) ?> Ar</strong></td>
                <td>
                    <span class="badge <?= $c['est_utilise'] ? 'badge-danger' : 'badge-success' ?>">
                        <?= $c['est_utilise'] ? 'Utilisé' : 'Disponible' ?>
                    </span>
                </td>
                <td><?= $c['utilisateur_id'] ?: '<span class="text-muted">—</span>' ?></td>
                <td><?= date('d/m/Y H:i', strtotime($c['date_creation'])) ?></td>
                <td class="action-btns">
                    <a href="<?= base_url('/admin/codes/delete/'.$c['id']) ?>" class="btn btn-outline btn-sm" title="Supprimer" onclick="return confirm('Supprimer ce code ?')"><i class="fas fa-trash"></i></a>
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
    .badge-success { background: #f0fff4; color: #27ae60; }
    .badge-danger { background: #fff5f5; color: #e74c3c; }
    .action-btns { display: flex; gap: 8px; }
    .btn-sm { padding: 8px 12px; font-size: 12px; }
    .text-muted { color: #9a938e; font-style: italic; }
</style>

<?= $this->endSection() ?>
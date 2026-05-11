<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<main class="container" style="margin-top: 40px; margin-bottom: 60px;">

    <div class="page-header" style="margin-bottom: 40px;">
        <h1 style="font-size: 28px; color: #333;"><i class="fas fa-history" style="color: #E17864;"></i> Historique des Codes</h1>
        <p class="subtitle" style="color: #666;">Retrouvez tous les codes que vous avez achetés et utilisés.</p>
    </div>

    <article class="dash-panel" style="padding: 30px;">
        <?php if (empty($codes)): ?>
            <div class="empty-state" style="text-align: center; padding: 40px;">
                <i class="fas fa-ticket-alt" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                <p style="color: #666;">Aucun code utilisé pour le moment.</p>
                <a href="<?= base_url('/code/achat') ?>" class="btn btn-primary" style="margin-top: 16px;">
                    Acheter un code
                </a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eee;">
                            <th style="padding: 15px; color: #888;">Code</th>
                            <th style="padding: 15px; color: #888;">Montant</th>
                            <th style="padding: 15px; color: #888;">Date d'utilisation</th>
                            <th style="padding: 15px; color: #888;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($codes as $code): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;">
                                    <code style="font-weight: 800; color: #333; background: #f4f4f4; padding: 4px 8px; border-radius: 4px;"><?= esc($code['code']) ?></code>
                                </td>
                                <td style="padding: 15px;">
                                    <strong><?= number_format((float)$code['montant'], 0, ',', ' ') ?> Ar</strong>
                                </td>
                                <td style="padding: 15px;">
                                    <?php if (!empty($code['date_utilisation'])): ?>
                                        <?= date('d/m/Y H:i', strtotime($code['date_utilisation'])) ?>
                                    <?php else: ?>
                                        <span style="color: #999;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php if ($code['est_utilise']): ?>
                                        <span style="background: #e8f5e9; color: #2e7d32; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check"></i> Utilisé
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #fff3e0; color: #ef6c00; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; display: flex; gap: 12px;">
            <a href="<?= base_url('/code/achat') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Acheter un code
            </a>
            <a href="<?= base_url('/code/validation') ?>" class="btn btn-secondary">
                <i class="fas fa-check"></i> Valider un code
            </a>
        </div>
    </article>
</main>

<?= $this->endSection() ?>
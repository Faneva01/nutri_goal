<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div class="dash-panel">
        <div class="panel-header">
            <div>
                <h2><i class="fas fa-history"></i> Historique des transactions</h2>
                <p class="sub">Retrouvez ici tous vos achats de régimes, abonnements Gold et recharges.</p>
            </div>
            <div class="balance-badge" style="background: #FFF9F7; padding: 8px 16px; border-radius: 12px; border: 1px solid #f0ece8;">
                <span style="font-size: 13px; color: #9a938e; font-weight: 600;">Solde actuel :</span>
                <strong style="font-size: 16px; color: #E17864; margin-left: 8px;"><?= number_format((float)($user['solde'] ?? 0), 0) ?> Ar</strong>
            </div>
        </div>

        <?php if (!empty($transactions)): ?>
            <div class="table-responsive" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
                    <thead>
                        <tr style="text-align: left; font-size: 13px; color: #9a938e; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 12px 16px;">Date</th>
                            <th style="padding: 12px 16px;">Description</th>
                            <th style="padding: 12px 16px;">Type</th>
                            <th style="padding: 12px 16px; text-align: right;">Montant</th>
                            <th style="padding: 12px 16px; text-align: right;">Nouveau Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <?php 
                                $isCredit = ($t['type_transaction'] === 'ajout_code' || $t['type_transaction'] === 'remboursement');
                                $typeLabel = '';
                                $typeClass = '';
                                switch($t['type_transaction']) {
                                    case 'ajout_code': $typeLabel = 'Recharge'; $typeClass = 'badge-success'; break;
                                    case 'achat_regime': $typeLabel = 'Régime'; $typeClass = 'badge-info'; break;
                                    case 'achat_gold': $typeLabel = 'Option Gold'; $typeClass = 'badge-gold'; break;
                                    case 'remboursement': $typeLabel = 'Remboursement'; $typeClass = 'badge-warning'; break;
                                }
                            ?>
                            <tr style="background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: transform 0.2s;">
                                <td style="padding: 16px; border-top: 1px solid #f0ece8; border-bottom: 1px solid #f0ece8; border-left: 1px solid #f0ece8; border-radius: 12px 0 0 12px; font-size: 14px; color: #2d2926;">
                                    <?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?>
                                </td>
                                <td style="padding: 16px; border-top: 1px solid #f0ece8; border-bottom: 1px solid #f0ece8; font-size: 14px; font-weight: 500; color: #2d2926;">
                                    <?= esc($t['description']) ?>
                                </td>
                                <td style="padding: 16px; border-top: 1px solid #f0ece8; border-bottom: 1px solid #f0ece8;">
                                    <span class="badge <?= $typeClass ?>" style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                        <?= $typeLabel ?>
                                    </span>
                                </td>
                                <td style="padding: 16px; border-top: 1px solid #f0ece8; border-bottom: 1px solid #f0ece8; text-align: right; font-weight: 700; color: <?= $isCredit ? '#27ae60' : '#e74c3c' ?>;">
                                    <?= $isCredit ? '+' : '-' ?> <?= number_format($t['montant'], 0) ?> Ar
                                </td>
                                <td style="padding: 16px; border-top: 1px solid #f0ece8; border-bottom: 1px solid #f0ece8; border-right: 1px solid #f0ece8; border-radius: 0 12px 12px 0; text-align: right; font-size: 14px; color: #9a938e;">
                                    <?= number_format($t['nouveau_solde'], 0) ?> Ar
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 48px; color: #f0ece8; margin-bottom: 16px;"><i class="fas fa-receipt"></i></div>
                <h3 style="color: #2d2926; margin-bottom: 8px;">Aucune transaction</h3>
                <p style="color: #9a938e;">Vous n'avez pas encore effectué de transactions sur votre compte.</p>
                <a href="<?= base_url('/code/achat') ?>" class="btn btn-primary" style="margin-top: 24px;">Recharger mon compte</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .badge-success { background: #e8f5e9; color: #2e7d32; }
    .badge-info { background: #e3f2fd; color: #1565c0; }
    .badge-gold { background: #fffde7; color: #f9a825; border: 1px solid #fff59d; }
    .badge-warning { background: #fff3e0; color: #ef6c00; }
    
    tbody tr:hover {
        transform: scale(1.005);
        background: #fafafa !important;
    }
</style>

<?= $this->endSection() ?>

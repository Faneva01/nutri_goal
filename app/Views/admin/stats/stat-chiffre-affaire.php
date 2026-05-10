<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="admin-stats-container">
    <!-- Hero Header -->
    <div class="admin-stats-header">
        <div>
            <h1 class="admin-stats-title">
                <i class="fas fa-money-bill-wave"></i> Chiffre d'Affaires
            </h1>
            <p style="color: #95a5a6; margin-top: 5px;">Analyse complète des revenus et des méthodes de paiement</p>
        </div>
        <div class="stats-controls">
            <button class="stats-filter active" onclick="loadChartChiffreAffaire()">
                <i class="fas fa-sync"></i> Rafraîchir
            </button>
            <button class="stats-filter" onclick="exportRevenueData()">
                <i class="fas fa-download"></i> Export CSV
            </button>
            <a href="<?= base_url('/admin/dashboard') ?>" class="stats-filter">
                <i class="fas fa-arrow-left"></i> Tableau de Bord
            </a>
        </div>
    </div>

    {{-- Global Stats --}}
    <div id="globalStats" data-url="<?= base_url('/admin/api/stats/chiffre-affaire/stats') ?>">
        {{-- Sera rempli par le JavaScript --}}
    </div>

    {{-- Charts Grid --}}
    <div class="admin-stats-grid">
        <!-- Evolution Chart -->
        <div class="stats-chart-card">
            <div class="stats-chart-header">
                <h3 class="stats-chart-title">
                    <i class="fas fa-chart-line"></i> Évolution Chiffre d'Affaires
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartChiffreAffaire" 
                    data-url="<?= base_url('/admin/api/stats/chiffre-affaire') ?>">
                </canvas>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="stats-chart-card">
            <div class="stats-chart-header">
                <h3 class="stats-chart-title">
                    <i class="fas fa-credit-card"></i> Par Méthode de Paiement
                </h3>
            </div>
            <div class="stats-chart-container">
                <canvas 
                    id="chartPaymentMethods" 
                    data-url="<?= base_url('/admin/api/stats/chiffre-affaire/payment-methods') ?>">
                </canvas>
            </div>
        </div>
    </div>

    {{-- Payment Methods Details Table --}}
    <div class="stats-table-card">
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Méthode de Paiement</th>
                    <th>Nombre de Transactions</th>
                    <th>Montant Total</th>
                    <th>Montant Moyen</th>
                    <th>Popularité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <i class="fas fa-mobile-alt"></i> MVola
                    </td>
                    <td>89</td>
                    <td>18,500 Ar</td>
                    <td>207.87 Ar</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div style="width: 30%; background: #FF6B35; height: 100%; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold;">27.7%</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-mobile-alt"></i> Airtel Money
                    </td>
                    <td>76</td>
                    <td>15,200 Ar</td>
                    <td>200 Ar</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div style="width: 24%; background: #004E89; height: 100%; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold;">23.8%</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-mobile-alt"></i> Orange Money
                    </td>
                    <td>92</td>
                    <td>22,300 Ar</td>
                    <td>242.39 Ar</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div style="width: 34%; background: #1B998B; height: 100%; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold;">34.9%</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-credit-card"></i> Carte Bancaire
                    </td>
                    <td>64</td>
                    <td>29,420.50 Ar</td>
                    <td>459.7 Ar</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div style="width: 46%; background: #F7DC6F; height: 100%; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: #333; font-size: 11px; font-weight: bold;">45.9%</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Key Insights --}}
    <div style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <h3 style="margin-top: 0; color: #2c3e50;">
            <i class="fas fa-chart-bar"></i> Analyse
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div style="border-left: 4px solid #FF6B35; padding: 10px; background-color: #fff8f3;">
                <small style="color: #666; display: block; margin-bottom: 5px;">CA MVola</small>
                <strong style="font-size: 18px; color: #FF6B35;">18,500 Ar (27.7%)</strong>
            </div>
            <div style="border-left: 4px solid #004E89; padding: 10px; background-color: #f0f5f9;">
                <small style="color: #666; display: block; margin-bottom: 5px;">CA Airtel Money</small>
                <strong style="font-size: 18px; color: #004E89;">15,200 Ar (23.8%)</strong>
            </div>
            <div style="border-left: 4px solid #1B998B; padding: 10px; background-color: #f0f5f4;">
                <small style="color: #666; display: block; margin-bottom: 5px;">CA Orange Money</small>
                <strong style="font-size: 18px; color: #1B998B;">22,300 Ar (34.9%)</strong>
            </div>
            <div style="border-left: 4px solid #F7DC6F; padding: 10px; background-color: #fffdf5;">
                <small style="color: #666; display: block; margin-bottom: 5px;">CA Carte Bancaire</small>
                <strong style="font-size: 18px; color: #F39C12;">29,420.50 Ar (45.9%)</strong>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

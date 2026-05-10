<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class StatChiffreAffaireController extends BaseController
{
    /**
     * Affiche la page des statistiques chiffre d'affaires
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/stats/stat-chiffre-affaire', [
            'title' => 'Statistiques Chiffre d\'Affaires',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-chiffre-affaire.js']
        ]);
    }

    /**
     * API: Récupère les données de chiffre d'affaires (JSON)
     */
    public function getChartData()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $transactions = $db->table('historique_transactions');

        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('d/m', strtotime($date));

            $amount = $transactions
                ->selectSum('montant')
                ->where('DATE(date_transaction)', $date)
                ->get()
                ->getRow()
                ->montant ?? 0;

            $data[] = (float) $amount;
            $transactions->resetQuery();
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Chiffre d\'Affaires (Ar)',
                    'data' => $data,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.12)',
                    'tension' => 0.3,
                    'fill' => true,
                    'pointBackgroundColor' => '#28a745',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4
                ]
            ]
        ]);
    }

    public function getPaymentMethods()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \\Config\\Database::connect();
        $transactions = $db->table('historique_transactions');

        $rows = $transactions
            ->select('type_transaction, SUM(montant) AS total, COUNT(*) AS count')
            ->groupBy('type_transaction')
            ->get()
            ->getResultArray();

        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $labels[] = ucfirst(str_replace('_', ' ', $row['type_transaction']));
            $data[] = (float) $row['total'];
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Montant des Transactions',
                    'data' => $data,
                    'backgroundColor' => ['#FF6B35', '#004E89', '#1B998B', '#F7DC6F'],
                    'borderColor' => ['#E55100', '#003366', '#0F6B5C', '#F39C12'],
                    'borderWidth' => 2
                ]
            ]
        ]);
    }

    public function getStats()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \\Config\\Database::connect();
        $transactions = $db->table('historique_transactions');

        $totalRevenue = (float) ($db->table('historique_transactions')->selectSum('montant')->get()->getRow()->montant ?? 0);
        $revenueToday = (float) ($db->table('historique_transactions')->selectSum('montant')->where('DATE(date_transaction)', date('Y-m-d'))->get()->getRow()->montant ?? 0);
        $revenueThisMonth = (float) ($db->table('historique_transactions')->selectSum('montant')->where('MONTH(date_transaction)', date('n'))->where('YEAR(date_transaction)', date('Y'))->get()->getRow()->montant ?? 0);
        $revenueLastMonth = (float) ($db->table('historique_transactions')->selectSum('montant')->where('MONTH(date_transaction)', date('n', strtotime('-1 month')))->where('YEAR(date_transaction)', date('Y', strtotime('-1 month')))->get()->getRow()->montant ?? 0);
        $totalTransactions = (int) $db->table('historique_transactions')->countAllResults();
        $averageTransaction = $totalTransactions > 0 ? round($totalRevenue / $totalTransactions, 2) : 0;
        $growth = $revenueLastMonth > 0 ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1) : 0;

        $paymentRows = $db->table('historique_transactions')->select('type_transaction, SUM(montant) AS total, COUNT(*) AS count')->groupBy('type_transaction')->get()->getResultArray();
        $paymentMethods = [];

        foreach ($paymentRows as $row) {
            $paymentMethods[$row['type_transaction']] = [
                'count' => (int) $row['count'],
                'amount' => (float) $row['total']
            ];
        }

        return $this->response->setJSON([
            'total_revenue' => $totalRevenue,
            'revenue_today' => $revenueToday,
            'revenue_this_month' => $revenueThisMonth,
            'revenue_last_month' => $revenueLastMonth,
            'growth' => $growth,
            'average_transaction' => $averageTransaction,
            'total_transactions' => $totalTransactions,
            'payment_methods' => $paymentMethods
        ]);
    }
}

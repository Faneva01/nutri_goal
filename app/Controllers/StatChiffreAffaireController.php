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
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        // Récupérer les données des 30 derniers jours
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Chiffre d\'Affaires (Ar)',
                    'data' => [],
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
                    'pointBackgroundColor' => '#28a745',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4
                ]
            ]
        ];

        // Générer données pour 30 jours
        for ($i = 29; $i >= 0; $i--) {
            $date = date('d/m', strtotime("-{$i} days"));
            $chartData['labels'][] = $date;
            $chartData['datasets'][0]['data'][] = rand(1500, 8000);
        }

        return $this->response->setJSON($chartData);
    }

    /**
     * API: Récupère les données par méthode de paiement
     */
    public function getPaymentMethods()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $chartData = [
            'labels' => ['MVola', 'Airtel Money', 'Orange Money', 'Carte Bancaire'],
            'datasets' => [
                [
                    'label' => 'Montant des Transactions',
                    'data' => [18500, 15200, 22300, 29420.50],
                    'backgroundColor' => [
                        '#FF6B35',
                        '#004E89',
                        '#1B998B',
                        '#F7DC6F'
                    ],
                    'borderColor' => [
                        '#E55100',
                        '#003366',
                        '#0F6B5C',
                        '#F39C12'
                    ],
                    'borderWidth' => 2
                ]
            ]
        ];

        return $this->response->setJSON($chartData);
    }

    /**
     * API: Récupère les statistiques globales de chiffre d'affaires
     */
    public function getStats()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $stats = [
            'total_revenue' => 85420.50,
            'revenue_today' => 3250.00,
            'revenue_this_month' => 85420.50,
            'revenue_last_month' => 67850.00,
            'growth' => 25.9,
            'average_transaction' => 265.20,
            'total_transactions' => 321,
            'payment_methods' => [
                'mvola' => ['count' => 89, 'amount' => 18500],
                'airtel' => ['count' => 76, 'amount' => 15200],
                'orange' => ['count' => 92, 'amount' => 22300],
                'card' => ['count' => 64, 'amount' => 29420.50]
            ]
        ];

        return $this->response->setJSON($stats);
    }
}

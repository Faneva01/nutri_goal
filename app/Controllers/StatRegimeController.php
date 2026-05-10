<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class StatRegimeController extends BaseController
{
    /**
     * Affiche la page des statistiques régimes et plats populaires
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/stats/stat-regime', [
            'title' => 'Statistiques Régimes et Plats',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-regime.js']
        ]);
    }

    /**
     * API: Récupère les données des régimes populaires
     */
    public function getChartData()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $chartData = [
            'labels' => ['Régime Keto', 'Régime Paleo', 'Régime Vegan', 'Régime Équilibré', 'Régime Low Carb', 'Régime Méditerranéen'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'Utilisateurs',
                    'data' => [142, 98, 76, 215, 56, 189],
                    'backgroundColor' => [
                        '#FF6B6B',
                        '#4ECDC4',
                        '#45B7D1',
                        '#FFA07A',
                        '#98D8C8',
                        '#F7DC6F'
                    ],
                    'borderColor' => [
                        '#E55039',
                        '#16A085',
                        '#2980B9',
                        '#D35400',
                        '#1ABC9C',
                        '#F39C12'
                    ],
                    'borderWidth' => 2
                ]
            ]
        ];

        return $this->response->setJSON($chartData);
    }

    /**
     * API: Récupère les plats populaires
     */
    public function getDishesChart()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Plats Populaires (Consommations)',
                    'data' => [],
                    'borderColor' => '#FF6B6B',
                    'backgroundColor' => 'rgba(255, 107, 107, 0.1)',
                    'tension' => 0.3,
                    'fill' => true
                ]
            ]
        ];

        // Top 10 plats populaires
        $dishes = ['Riz Gras', 'Brochette', 'Poulet Rôti', 'Salade Fraîche', 'Viande Grillée', 'Poisson Fumet', 'Légumes Vapeur', 'Pâtes Complètes', 'Oeufs Brouillés', 'Salade Composée'];
        
        foreach ($dishes as $dish) {
            $chartData['labels'][] = $dish;
            $chartData['datasets'][0]['data'][] = rand(20, 150);
        }

        return $this->response->setJSON($chartData);
    }

    /**
     * API: Récupère les statistiques détaillées des régimes
     */
    public function getDetailedStats()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $stats = [
            'regimes' => [
                [
                    'name' => 'Régime Équilibré',
                    'users' => 215,
                    'percentage' => 32.1,
                    'rating' => 4.6,
                    'status' => 'Populaire'
                ],
                [
                    'name' => 'Régime Méditerranéen',
                    'users' => 189,
                    'percentage' => 28.2,
                    'rating' => 4.8,
                    'status' => 'Très Populaire'
                ],
                [
                    'name' => 'Régime Keto',
                    'users' => 142,
                    'percentage' => 21.2,
                    'rating' => 4.3,
                    'status' => 'Populaire'
                ],
                [
                    'name' => 'Régime Paleo',
                    'users' => 98,
                    'percentage' => 14.6,
                    'rating' => 4.1,
                    'status' => 'Modéré'
                ],
                [
                    'name' => 'Régime Vegan',
                    'users' => 76,
                    'percentage' => 11.4,
                    'rating' => 4.5,
                    'status' => 'Modéré'
                ]
            ],
            'popular_dishes' => [
                ['name' => 'Riz Gras', 'count' => 287, 'regime' => 'Équilibré'],
                ['name' => 'Brochette', 'count' => 245, 'regime' => 'Paleo'],
                ['name' => 'Poulet Rôti', 'count' => 198, 'regime' => 'Équilibré'],
                ['name' => 'Salade Fraîche', 'count' => 176, 'regime' => 'Vegan'],
                ['name' => 'Viande Grillée', 'count' => 154, 'regime' => 'Keto']
            ]
        ];

        return $this->response->setJSON($stats);
    }
}

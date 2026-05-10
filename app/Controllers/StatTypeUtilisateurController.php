<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class StatTypeUtilisateurController extends BaseController
{
    /**
     * Affiche la page des statistiques types d'utilisateurs
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/stats/stat-type-usuarios', [
            'title' => 'Statistiques Types Utilisateurs',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-type-usuarios.js']
        ]);
    }

    /**
     * API: Récupère les données de répartition des types d'utilisateurs (JSON)
     * Simple, Gold, Premium
     */
    public function getChartData()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        // Récupérer les types d'abonnements
        $chartData = [
            'labels' => ['Simple', 'Gold', 'Premium'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'Utilisateurs',
                    'data' => [312, 187, 43],
                    'backgroundColor' => [
                        '#007bff',  // Blue pour Simple
                        '#ffc107',  // Gold pour Gold
                        '#e83e8c'   // Pink pour Premium
                    ],
                    'borderColor' => [
                        '#0056b3',
                        '#e0a800',
                        '#c2185b'
                    ],
                    'borderWidth' => 2
                ]
            ]
        ];

        return $this->response->setJSON($chartData);
    }

    /**
     * API: Récupère les détails statistiques des types d'utilisateurs
     */
    public function getDetailedStats()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        $stats = [
            'simple' => [
                'count' => 312,
                'percentage' => 50.2,
                'revenue' => 0,
                'active' => 267,
                'inactive' => 45
            ],
            'gold' => [
                'count' => 187,
                'percentage' => 30.1,
                'revenue' => 56100,
                'active' => 175,
                'inactive' => 12
            ],
            'premium' => [
                'count' => 43,
                'percentage' => 6.9,
                'revenue' => 21500,
                'active' => 41,
                'inactive' => 2
            ]
        ];

        return $this->response->setJSON($stats);
    }
}

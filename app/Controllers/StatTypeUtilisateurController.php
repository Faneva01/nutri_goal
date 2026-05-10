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
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();

        $totalUsers = (int) $db->table('utilisateurs')->countAll();
        $goldUsers = (int) $db->table('utilisateurs')->where('option_gold', 1)->countAllResults();
        $simpleUsers = max(0, $totalUsers - $goldUsers);

        return $this->response->setJSON([
            'labels' => ['Simple', 'Gold'],
            'datasets' => [
                [
                    'label' => 'Nombre d\'Utilisateurs',
                    'data' => [$simpleUsers, $goldUsers],
                    'backgroundColor' => ['#007bff', '#ffc107'],
                    'borderColor' => ['#0056b3', '#e0a800'],
                    'borderWidth' => 2
                ]
            ]
        ]);
    }

    public function getDetailedStats()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \\Config\\Database::connect();
        $totalUsers = (int) $db->table('utilisateurs')->countAll();
        $goldUsers = (int) $db->table('utilisateurs')->where('option_gold', 1)->countAllResults();
        $simpleUsers = max(0, $totalUsers - $goldUsers);

        $stats = [
            'simple' => [
                'count' => $simpleUsers,
                'percentage' => $totalUsers > 0 ? round(($simpleUsers / $totalUsers) * 100, 1) : 0,
                'active' => $simpleUsers,
                'inactive' => 0
            ],
            'gold' => [
                'count' => $goldUsers,
                'percentage' => $totalUsers > 0 ? round(($goldUsers / $totalUsers) * 100, 1) : 0,
                'active' => $goldUsers,
                'inactive' => 0
            ]
        ];

        return $this->response->setJSON($stats);
    }
}

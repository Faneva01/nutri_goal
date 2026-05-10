<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class StatUtilisateurController extends BaseController
{
    /**
     * Affiche la page des statistiques utilisateurs
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/stats/stat-usuarios', [
            'title' => 'Statistiques Utilisateurs',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-usuarios.js']
        ]);
    }

    /**
     * API: Récupère les données de variation des utilisateurs (JSON)
     */
    public function getChartData()
    {
        // Vérifier que l'utilisateur est connecté en tant qu'admin
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // TODO: Remplacer par une vraie requête base de données
        // Récupérer les data des 30 derniers jours
        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Nouveaux Utilisateurs',
                    'data' => [],
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ],
                [
                    'label' => 'Utilisateurs Actifs',
                    'data' => [],
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ]
        ];

        // Générer données pour 30 jours
        for ($i = 29; $i >= 0; $i--) {
            $date = date('d/m', strtotime("-{$i} days"));
            $chartData['labels'][] = $date;
            $chartData['datasets'][0]['data'][] = rand(5, 25);
            $chartData['datasets'][1]['data'][] = rand(100, 500);
        }

        return $this->response->setJSON($chartData);
    }
}

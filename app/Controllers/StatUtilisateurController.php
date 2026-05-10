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
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $userBuilder = $db->table('utilisateurs');
        $subscriptionBuilder = $db->table('abonnements_regimes');

        $labels = [];
        $newUsers = [];
        $activeUsers = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('d/m', strtotime($date));

            $newUsers[] = (int) $userBuilder
                ->where('DATE(date_inscription)', $date)
                ->countAllResults(false);

            $activeUsers[] = (int) $subscriptionBuilder
                ->select('COUNT(DISTINCT utilisateur_id) AS total')
                ->where('statut', 'actif')
                ->where('date_debut <=', $date)
                ->where('date_fin >=', $date)
                ->get()
                ->getRow()
                ->total ?? 0;

            $subscriptionBuilder->resetQuery();
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nouveaux Utilisateurs',
                    'data' => $newUsers,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.12)',
                    'tension' => 0.3,
                    'fill' => true
                ],
                [
                    'label' => 'Abonnements Actifs',
                    'data' => $activeUsers,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.12)',
                    'tension' => 0.3,
                    'fill' => true
                ]
            ]
        ]);
    }
}

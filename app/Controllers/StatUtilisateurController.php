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

        $db = \Config\Database::connect();
        
        // Données pour le tableau (10 derniers jours)
        $tableData = [];
        for ($i = 0; $i < 10; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            
            $newUsers = $db->table('utilisateurs')
                ->where('DATE(date_inscription)', $date)
                ->countAllResults();
                
            $activeUsers = $db->table('abonnements_regimes')
                ->where('statut', 'actif')
                ->where('date_debut <=', $date)
                ->where('date_fin >=', $date)
                ->countAllResults();

            // Calcul variation (simplifié)
            $prevDate = date('Y-m-d', strtotime("-".($i+1)." days"));
            $prevActive = $db->table('abonnements_regimes')
                ->where('statut', 'actif')
                ->where('date_debut <=', $prevDate)
                ->where('date_fin >=', $prevDate)
                ->countAllResults();
            
            $diff = $activeUsers - $prevActive;
            $percent = $prevActive > 0 ? round(($diff / $prevActive) * 100, 1) : 0;

            $tableData[] = [
                'date' => date('d/m/Y', strtotime($date)),
                'new' => $newUsers,
                'active' => $activeUsers,
                'diff' => ($diff >= 0 ? '+' : '').$diff.' ('.($percent >= 0 ? '+' : '').$percent.'%)',
                'status' => $diff >= 0 ? 'Croissance' : 'Baisse'
            ];
        }

        // Résumé global
        $totalUsers = $db->table('utilisateurs')->countAllResults();
        $goldUsers = $db->table('abonnements_gold')->countAllResults();
        $activationRate = $totalUsers > 0 ? round(($goldUsers / $totalUsers) * 100, 1) : 0;

        return view('admin/stats/stat-usuarios', [
            'title' => 'Statistiques Utilisateurs',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-usuarios.js'],
            'tableData' => $tableData,
            'summary' => [
                'total' => $totalUsers,
                'gold' => $goldUsers,
                'activation' => $activationRate
            ]
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

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

        $db = \Config\Database::connect();
        
        // Régime le plus populaire
        $popularRegime = $db->table('abonnements_regimes ar')
            ->select('r.nom, COUNT(ar.id) as count')
            ->join('regimes r', 'r.id = ar.regime_id')
            ->groupBy('r.id')
            ->orderBy('count', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $totalSubscribers = $db->table('abonnements_regimes')->countAllResults();
        
        // Plat (activité) le plus populaire
        $popularActivity = $db->table('regime_activite ra')
            ->select('a.nom, COUNT(ra.id) as count')
            ->join('activites_sportives a', 'a.id = ra.activite_id')
            ->groupBy('a.id')
            ->orderBy('count', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $totalRegimes = $db->table('regimes')->countAll();

        return view('admin/stats/stat-regime', [
            'title' => 'Statistiques Régimes et Plats',
            'styles' => ['admin/admin-stats.css'],
            'scripts' => ['admin/stat-regime.js'],
            'popularRegime' => $popularRegime,
            'totalSubscribers' => $totalSubscribers,
            'popularActivity' => $popularActivity,
            'totalRegimes' => $totalRegimes
        ]);
    }

    /**
     * API: Récupère les données des régimes populaires
     */
    public function getChartData()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $rows = $db->table('abonnements_regimes ar')
            ->select('r.nom AS regime, COUNT(ar.id) AS total')
            ->join('regimes r', 'r.id = ar.regime_id')
            ->groupBy('r.id')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        $labels = array_column($rows, 'regime');
        $data = array_map(fn($row) => (int) $row['total'], $rows);

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Utilisateurs par régime',
                    'data' => $data,
                    'backgroundColor' => ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F'],
                    'borderColor' => ['#E55039', '#16A085', '#2980B9', '#D35400', '#1ABC9C', '#F39C12'],
                    'borderWidth' => 2
                ]
            ]
        ]);
    }

    public function getDishesChart()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $rows = $db->table('regime_activite ra')
            ->select('a.nom AS activite, COUNT(ra.id) AS total')
            ->join('activites_sportives a', 'a.id = ra.activite_id')
            ->groupBy('a.id')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $labels = array_column($rows, 'activite');
        $data = array_map(fn($row) => (int) $row['total'], $rows);

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Activités Populaires',
                    'data' => $data,
                    'borderColor' => '#FF6B6B',
                    'backgroundColor' => 'rgba(255, 107, 107, 0.12)',
                    'tension' => 0.3,
                    'fill' => true
                ]
            ]
        ]);
    }

    public function getDetailedStats()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $regimes = $db->table('regimes r')
            ->select('r.nom AS name, COUNT(ar.id) AS users')
            ->join('abonnements_regimes ar', 'ar.regime_id = r.id')
            ->groupBy('r.id')
            ->orderBy('users', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $totalSubscriptions = (int) $db->table('abonnements_regimes')->countAllResults();

        $formattedRegimes = array_map(function ($row) use ($totalSubscriptions) {
            $percentage = $totalSubscriptions > 0 ? round(($row['users'] / $totalSubscriptions) * 100, 1) : 0;
            return [
                'name' => $row['name'],
                'users' => (int) $row['users'],
                'percentage' => $percentage,
                'rating' => round(3.5 + ($percentage / 20), 1),
                'status' => $percentage > 20 ? 'Très Populaire' : 'Populaire'
            ];
        }, $regimes);

        $dishes = $db->table('regime_activite ra')
            ->select('a.nom AS name, COUNT(ra.id) AS count')
            ->join('activites_sportives a', 'a.id = ra.activite_id')
            ->groupBy('a.id')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'regimes' => $formattedRegimes,
            'popular_dishes' => array_map(fn($row) => [
                'name' => $row['name'],
                'count' => (int) $row['count'],
                'regime' => 'N/A'
            ], $dishes)
        ]);
    }
}

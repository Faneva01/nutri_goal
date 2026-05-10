<?php

namespace App\Controllers;

use App\Models\DashboardUserModel;

class DashboardUserController extends BaseController
{
    protected DashboardUserModel $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardUserModel();
    }

    public function index()
    {
        //  non connecté → retour au login
        if (!session()->get('logged')) {
            return redirect()->to('/login');
        }

        $userId    = (int) session()->get('user_id');
        $dashboard = $this->dashboardModel->getDashboardData($userId ?: null);

        return view('pages/dashboardUser/dashboard_user', [
            'title'        => 'Mon tableau de bord | Nutri Goal',
            'navView'      => 'inc/nav',
            'user'         => $dashboard['user'],
            'stats'        => $dashboard['stats'],
            'regimes'      => $dashboard['regimes'],
            'historique'   => $dashboard['historique'],
            'weightSeries' => $dashboard['weightSeries'],
            'currentRegime' => $dashboard['currentRegime'],
            'caloriesSeries' => $dashboard['caloriesSeries'],
            'dbDown'       => $dashboard['db_down'] ?? false,
            'styles'       => [
                'dashboard-user.css',
            ],
            'scripts'      => [
                'dashboard-user.js',
            ],
        ]);
    }
}
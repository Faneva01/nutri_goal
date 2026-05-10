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
        $userId = session()->get('user_id');
        $dashboard = $this->dashboardModel->getDashboardData($userId ? (int) $userId : null);

        return view('pages/dashboardUser/dashboard_user', [
            'title' => 'Dashboard utilisateur',
            'navView' => 'inc/nav_profil',
            'user' => $dashboard['user'],
            'stats' => $dashboard['stats'],
            'regimes' => $dashboard['regimes'],
            'historique' => $dashboard['historique'],
            'weightSeries' => $dashboard['weightSeries'],
            'currentRegime' => $dashboard['currentRegime'],
            'caloriesSeries' => $dashboard['caloriesSeries'],
            'dbDown' => $dashboard['db_down'] ?? false,
            'styles' => [
                'profil-page.css',
                'dashboard-user.css',
            ],
            'scripts' => [
                'dashboard-user.js',
            ],
        ]);
    }

}

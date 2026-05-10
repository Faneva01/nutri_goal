<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminAuthController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    /**
     * Page login admin
     */
    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/admin-login', [
            'title' => 'Connexion Administrateur',
            'styles' => ['admin/admin-login.css']
        ]);
    }

    /**
     * Vérification login
     */
    public function verify()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->getAdminByEmail($email);

        if (!$admin) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Administrateur introuvable');
        }

        $storedPassword = $admin['mot_de_passe'];
        $validPassword = password_verify($password, $storedPassword) || $storedPassword === $password;

        if (!$validPassword) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Mot de passe incorrect');
        }

        session()->set([
            'admin_logged_in' => true,
            'admin_id' => $admin['id'],
            'admin_name' => $admin['nom_complet'],
            'admin_email' => $admin['email'],
            'admin_role' => $admin['role']
        ]);

        $this->adminModel->update($admin['id'], [
            'derniere_connexion' => date('Y-m-d H:i:s')
        ]);

        return redirect()
            ->to('/admin/dashboard')
            ->with('success', 'Connexion réussie');
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()
                ->to('/admin/login')
                ->with('error', 'Connexion requise');
        }

        return view('admin/dashboard-admin', [
            'title' => 'Dashboard Administrateur',
            'styles' => ['admin/admin-dashboard.css'],
            'scripts' => ['admin/dashboard.js'],
            'stats' => $this->getGlobalStats(),
            'recent_activity' => $this->getRecentActivity()
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->remove([
            'admin_logged_in',
            'admin_id',
            'admin_name',
            'admin_email',
            'admin_role'
        ]);

        return redirect()
            ->to('/admin/login')
            ->with('success', 'Déconnexion réussie');
    }

    /**
     * Statistiques dashboard
     */
    private function getGlobalStats(): array
    {
        $db = \Config\Database::connect();

        $today = date('Y-m-d');
        $newUsersToday = $db
            ->table('utilisateurs')
            ->where('DATE(date_inscription) =', $today)
            ->countAllResults();

        $revenueToday = $db
            ->table('historique_transactions')
            ->selectSum('montant')
            ->where('DATE(date_transaction) =', $today)
            ->get()
            ->getRow()
            ->montant ?? 0;

        $regimesUsers = $db
            ->table('abonnements_regimes')
            ->select('COUNT(DISTINCT utilisateur_id) AS total')
            ->get()
            ->getRow()
            ->total ?? 0;

        return [
            'total_users' => $db
                ->table('utilisateurs')
                ->countAll(),
            'new_users_today' => $newUsersToday,
            'total_regimes' => $db
                ->table('regimes')
                ->countAll(),
            'total_codes' => $db
                ->table('codes_solde')
                ->countAll(),
            'codes_used' => $db
                ->table('codes_solde')
                ->where('utilisateur_id IS NOT NULL')
                ->countAllResults(),
            'gold_users' => $db
                ->table('abonnements_gold')
                ->countAll(),
            'total_revenue' => $db
                ->table('historique_transactions')
                ->selectSum('montant')
                ->get()
                ->getRow()
                ->montant ?? 0,
            'revenue_today' => $revenueToday,
            'regimes_users' => $regimesUsers
        ];
    }

    /**
     * Activité récente du dashboard
     */
    private function getRecentActivity(): array
    {
        return [
            [
                'icon' => 'fas fa-user-plus',
                'message' => 'Nouvel utilisateur inscrit : un compte a été créé',
                'time' => 'Il y a 5 minutes',
                'color' => '#28a745'
            ],
            [
                'icon' => 'fas fa-credit-card',
                'message' => 'Achat de code portefeuille effectué',
                'time' => 'Il y a 15 minutes',
                'color' => '#007bff'
            ],
            [
                'icon' => 'fas fa-check-circle',
                'message' => 'Code validé et crédité au compte',
                'time' => 'Il y a 20 minutes',
                'color' => '#17a2b8'
            ],
            [
                'icon' => 'fas fa-leaf',
                'message' => 'Nouveau régime créé',
                'time' => 'Il y a 1 heure',
                'color' => '#ffc107'
            ]
        ];
    }
}

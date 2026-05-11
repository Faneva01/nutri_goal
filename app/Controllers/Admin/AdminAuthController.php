<?php
// ============================================================
// app/Controllers/Admin/AdminAuthController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AdminAuthController extends BaseController
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    // ── GET /admin/login ────────────────────────────────────
    public function index()
    {
        if (session()->get('admin_id')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/admin-login', [
            'title'       => 'Connexion Admin – NutriGoal',
            'show_navbar' => false,
        ]);
    }

    // ── POST /admin/login ───────────────────────────────────
    public function login()
    {
        $email = $this->request->getPost('email');
        $mdp   = $this->request->getPost('mot_de_passe');

        $admin = $this->adminModel->where('email', $email)->where('actif', 1)->first();

        if (! $admin) {
            return redirect()->back()->with('error', 'Identifiants incorrects.')->withInput();
        }

        // Accepte hash bcrypt OU mot de passe brut (données test)
        $ok = (strlen($admin['mot_de_passe']) > 30)
            ? password_verify($mdp, $admin['mot_de_passe'])
            : ($mdp === $admin['mot_de_passe']);

        if (! $ok) {
            return redirect()->back()->with('error', 'Identifiants incorrects.')->withInput();
        }

        // Mise à jour dernière connexion
        $this->adminModel->update($admin['id'], ['derniere_connexion' => date('Y-m-d H:i:s')]);

        session()->set([
            'admin_id'    => $admin['id'],
            'admin_nom'   => $admin['nom_complet'],
            'admin_email' => $admin['email'],
            'admin_role'  => $admin['role'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    // ── GET /admin/logout ───────────────────────────────────
    public function logout()
    {
        session()->remove(['admin_id','admin_nom','admin_email','admin_role']);
        return redirect()->to('/admin/login');
    }
}

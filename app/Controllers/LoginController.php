<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Déjà connecté → rediriger directement
        if (session()->get('logged')) {
            return redirect()->to('/dashboard');
        }

        return view("pages/auth/login", [
            "title"      => "Connexion | Nutri Goal",
            "show_navbar" => false,
            "styles"     => ["auth/auth.css"],
            "scripts"    => ["auth/login.js"]
        ]);
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('mot_de_passe');

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['email' => 'Email introuvable']
            ]);
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => ['mot_de_passe' => 'Mot de passe incorrect']
            ]);
        }

        session()->set([
            'user_id'    => $user['id'],
            'nom_complet' => $user['nom_complet'],
            'email'      => $user['email'],
            'solde' => $user['solde'],
            'logged'     => true
        ]);

        // redirect renvoyé pour que le JS puisse rediriger
        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'Connexion réussie',
            'redirect' => base_url('/dashboard')
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
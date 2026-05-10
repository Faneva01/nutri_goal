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
        if (session()->get('logged') || session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view("pages/auth/login", [
            "title" => "Connexion | Nutri Goal",
            "show_navbar" => false,
            "styles" => ["auth/auth.css"], 
            "scripts" => ["auth/login.js"]
        ]);
    }

    public function login()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('mot_de_passe');

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => [
                    'email' => 'Email introuvable'
                ]
            ]);
        }

        $stored = $user['mot_de_passe'];
        $ok = password_verify($password, $stored);

        // Comptes créés avec l'ancien bug (mot de passe en clair en base) : migrer vers un hash
        if (! $ok && password_get_info($stored)['algoName'] === 'unknown' && hash_equals($stored, $password)) {
            $this->userModel->skipValidation(true)->update($user['id'], ['mot_de_passe' => $password]);
            $ok = true;
        }

        if (! $ok) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => [
                    'mot_de_passe' => 'Mot de passe incorrect'
                ]
            ]);
        }

        session()->set([
            'user_id' => $user['id'],
            'email'   => $user['email'],
            'logged'  => true
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Connexion réussie'
        ]);
    }
}
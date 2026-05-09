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
        return view("pages/auth/login", [
            "title" => "Connexion | Nutri Goal",
            "show_navbar" => false,
            "styles" => ["auth/auth.css"], 
            "scripts" => ["auth/login.js"]
        ]);
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('mot_de_passe');

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => [
                    'email' => 'Email introuvable'
                ]
            ]);
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
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
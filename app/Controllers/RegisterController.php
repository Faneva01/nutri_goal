<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class RegisterController extends BaseController
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

        return view("pages/auth/register", [
            "title"      => "Inscription | Nutri Goal",
            "show_navbar" => false,
            "styles"     => ["auth/auth.css"],
            "scripts"    => ["auth/register.js"]
        ]);
    }

    // AJAX VALIDATION FIELD BY FIELD
    public function validationInput()
    {
        try {
            $input  = $this->request->getPost('input');
            $value  = $this->request->getPost('value');
            $errors = [];

            switch ($input) {
                case 'nom_complet':
                    if (strlen($value) < 3)   $errors[] = "Nom trop court";
                    if (strlen($value) > 100)  $errors[] = "Nom trop long";
                    break;

                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Email invalide";
                    } elseif ($this->userModel->where('email', $value)->first()) {
                        $errors[] = "Email déjà utilisé";
                    }
                    break;

                case 'genre':
                    if (!in_array($value, ['M', 'F', 'Autre'])) {
                        $errors[] = "Genre invalide";
                    }
                    break;

                case 'mot_de_passe':
                    if (strlen($value) < 6) $errors[] = "Mot de passe trop faible";
                    break;

                case 'taille':
                    if (!is_numeric($value) || $value < 50 || $value > 250) {
                        $errors[] = "Taille invalide";
                    }
                    break;

                case 'poids':
                    if (!is_numeric($value) || $value < 20 || $value > 300) {
                        $errors[] = "Poids invalide";
                    }
                    break;

                default:
                    $errors[] = "Champ inconnu";
            }

            return $this->response->setStatusCode(200)->setJSON([
                'valid'  => empty($errors),
                'errors' => $errors
            ]);

        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'valid'  => false,
                'errors' => ["Erreur serveur: " . $e->getMessage()]
            ]);
        }
    }

    // REGISTER FINAL SUBMIT
    public function store()
    {
        $data = [
            'nom_complet'  => $this->request->getPost('nom_complet'),
            'email'        => $this->request->getPost('email'),
            'mot_de_passe' => password_hash($this->request->getPost('mot_de_passe'), PASSWORD_BCRYPT),
            'genre'        => $this->request->getPost('genre'),
            'taille'       => $this->request->getPost('taille'),
            'poids'        => $this->request->getPost('poids'),
            'solde'        => 0
        ];

        if (!$this->userModel->validate($data)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->userModel->errors()
            ], 400);
        }

        try {
            $id = $this->userModel->insert($data);

            if (!$id) {
                throw new \Exception("Insertion échouée");
            }

            // Connecter automatiquement après inscription
            $user = $this->userModel->find($id);
            session()->set([
                'user_id'     => $user['id'],
                'nom_complet' => $user['nom_complet'],
                'email'       => $user['email'],
                'logged'      => true
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => "Compte créé avec succès",
                'user_id'  => $id,
                'redirect' => base_url('/dashboard')
            ]);

        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
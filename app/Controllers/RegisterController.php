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
        if (session()->get('logged')) {
            return redirect()->to('/dashboard');
        }

        // Retour à la vue unique qui gère le JS multi-step (style original)
        return view("pages/auth/register", [
            "title"       => "Inscription | Nutri Goal",
            "show_navbar" => false,
            "styles"      => ["auth/auth.css"],
            "scripts"     => ["auth/register.js"]
        ]);
    }

    /**
     * AJAX VALIDATION FIELD BY FIELD (Utilisé par register.js sur blur)
     */
    public function validationInput()
    {
        try {
            $input  = $this->request->getPost('input');
            $value  = $this->request->getPost('value');
            $errors = [];

            switch ($input) {
                case 'nom_complet':
                    if (strlen($value) < 3)   $errors[] = "Nom trop court (min 3)";
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

                case 'taille':
                    if (!is_numeric($value) || $value < 50 || $value > 250) {
                        $errors[] = "Taille invalide (50-250 cm)";
                    }
                    break;

                case 'poids':
                    if (!is_numeric($value) || $value < 20 || $value > 300) {
                        $errors[] = "Poids invalide (20-300 kg)";
                    }
                    break;
            }

            return $this->response->setStatusCode(200)->setJSON([
                'valid'  => empty($errors),
                'errors' => $errors
            ]);

        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'valid'  => false,
                'errors' => ["Erreur serveur"]
            ]);
        }
    }

    /**
     * FINAL STORE (AJAX)
     */
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
            ]);
        }

        try {
            if (!$this->userModel->insert($data)) {
                return $this->response->setJSON(['success' => false, 'message' => "Erreur d'insertion"]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => "Inscription réussie"
            ]);

        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => "Erreur serveur"]);
        }
    }
}

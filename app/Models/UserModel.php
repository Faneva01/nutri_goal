<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table            = 'utilisateurs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    // CHAMPS AUTORISÉS
    protected $allowedFields = [
        'nom_complet',
        'email',
        'mot_de_passe',
        'genre',
        'taille',
        'poids',
        'imc',
        'solde'
    ];

    // TIMESTAMPS
    protected $useTimestamps = false;

    // VALIDATION
    protected $validationRules = [
        'nom_complet'  => 'required|min_length[3]|max_length[100]',
        'email'        => 'required|valid_email|is_unique[utilisateurs.email]',
        'mot_de_passe' => 'required|min_length[6]',
        'genre'        => 'required|in_list[M,F,Autre]',
        'taille'       => 'required|numeric|greater_than_equal_to[50]|less_than_equal_to[250]',
        'poids'        => 'required|numeric|greater_than_equal_to[20]|less_than_equal_to[300]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé'
        ]
    ];

    // CALLBACKS
    protected $beforeInsert = ['hashPassword', 'calculateIMC'];
    protected $beforeUpdate = ['hashPassword', 'calculateIMC'];

    /**
     * HASH PASSWORD
     */
    protected function hashPassword(array $data) {
        if (!isset($data['data']['mot_de_passe'])) {
            return $data;
        }

        $password = $data['data']['mot_de_passe'];

        // éviter double hash
        if (password_get_info($password)['algo'] !== 0) {
            return $data;
        }

        $data['data']['mot_de_passe'] = password_hash($password, PASSWORD_BCRYPT);

        return $data;
    }

    /**
     * CALCUL IMC
     */
    protected function calculateIMC(array $data) {
        if (isset($data['data']['taille'], $data['data']['poids'])) {

            $taille = $data['data']['taille'];
            $poids  = $data['data']['poids'];

            if ($taille > 0) {
                $data['data']['imc'] = round($poids / (($taille / 100) ** 2), 2);
            }
        }

        return $data;
    }

    // AUTH LOGIN
    public function authenticate(string $email, string $password) {
        $user = $this->where('email', $email)->first();

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
            return null;
        }

        return $user;
    }

    // GET USER
    public function getUserByEmail(string $email) {
        return $this->where('email', $email)->first();
    }
}
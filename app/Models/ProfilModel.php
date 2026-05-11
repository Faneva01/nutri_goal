<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table            = 'utilisateurs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'nom_complet',
        'email',
        'mot_de_passe',
        'genre',
        'taille',
        'poids',
        'imc',
        'option_gold',
        'solde'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nom_complet' => 'required|min_length[2]|max_length[100]',
        'email'       => 'required|valid_email|max_length[100]',
        'genre'       => 'required|in_list[M,F,Autre]',
        'taille'      => 'required|integer|greater_than_equal_to[50]|less_than_equal_to[250]',
        'poids'       => 'required|numeric|greater_than_equal_to[20]|less_than_equal_to[300]',
    ];

    protected $validationMessages = [
        'nom_complet' => ['required'    => 'Le nom complet est obligatoire.'],
        'email'       => ['valid_email' => "L'adresse email est invalide."],
        'genre'       => ['in_list'     => 'Le genre sélectionné est invalide.'],
        'taille'      => [
            'greater_than_equal_to' => 'La taille doit être supérieure ou égale à 50 cm.',
            'less_than_equal_to'    => 'La taille doit être inférieure ou égale à 250 cm.',
        ],
        'poids'       => [
            'greater_than_equal_to' => 'Le poids doit être supérieur ou égal à 20 kg.',
            'less_than_equal_to'    => 'Le poids doit être inférieur ou égal à 300 kg.',
        ],
    ];

    protected $beforeInsert = ['calculateIMC'];
    protected $beforeUpdate = ['calculateIMC'];

    protected function calculateIMC(array $data): array
    {
        if (isset($data['data']['taille'], $data['data']['poids'])) {
            $taille = $data['data']['taille'];
            $poids  = $data['data']['poids'];

            if ($taille > 0) {
                $data['data']['imc'] = round($poids / (($taille / 100) ** 2), 2);
            }
        }

        return $data;
    }
    
    public function rechargerSolde(int $id, float $montant): bool
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->set('solde', "solde + {$montant}", false)
            ->update();
    }
}

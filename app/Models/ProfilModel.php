<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table      = 'utilisateurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom_complet',
        'email',
        'mot_de_passe',
        'genre',
        'taille',
        'poids',
        'imc',
        'option_gold',
        'solde',
    ];

    protected $validationRules = [
        'nom_complet' => 'required|min_length[2]|max_length[100]',
        'email'       => 'required|valid_email|max_length[100]',
        'genre'       => 'required|in_list[M,F,Autre]',
        'taille'      => 'required|integer|greater_than[99]|less_than[251]',
        'poids'       => 'required|decimal|greater_than[29]|less_than[301]',
    ];

    protected $validationMessages = [
        'nom_complet' => ['required' => 'Le nom complet est obligatoire.'],
        'email'       => ['valid_email' => 'L\'adresse email est invalide.'],
        'genre'       => ['in_list' => 'Le genre sélectionné est invalide.'],
        'taille'      => ['greater_than' => 'La taille doit être supérieure à 100 cm.'],
        'poids'       => ['greater_than' => 'Le poids doit être supérieur à 30 kg.'],
    ];

    public function rechargerSolde(int $id, float $montant): bool
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->set('solde', "solde + {$montant}", false)
            ->update();
    }
}
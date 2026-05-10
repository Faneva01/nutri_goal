<?php

namespace App\Models;

use CodeIgniter\Model;

class AbonnementRegimeModel extends Model
{
    protected $table = 'abonnements_regimes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'utilisateur_id',
        'regime_id',
        'poids_initial',
        'poids_cible',
        'duree_estimee_jours',
        'date_debut',
        'date_fin',
        'prix_total',
        'statut'
    ];

    public function getActive(int $userId)
    {
        return $this->where('utilisateur_id', $userId)
                    ->where('statut', 'actif')
                    ->first();
    }

    public function getAllByUser(int $userId)
    {
        return $this->select('abonnements_regimes.*, regimes.nom as regime_nom')
                    ->join('regimes', 'regimes.id = abonnements_regimes.regime_id')
                    ->where('abonnements_regimes.utilisateur_id', $userId)
                    ->orderBy('abonnements_regimes.date_debut', 'DESC')
                    ->findAll();
    }
}
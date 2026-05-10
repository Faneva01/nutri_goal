<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table = 'activites_sportives';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'description',
        'duree_minutes',
        'intensite',
        'calories_brulees',
        'actif'
    ];

    public function getAllActive()
    {
        return $this->where('actif', 1)->findAll();
    }

    public function getByRegime(int $regimeId)
    {
        return $this->select('activites_sportives.*, regime_activite.frequence_par_semaine')
                    ->join('regime_activite', 'regime_activite.activite_id = activites_sportives.id')
                    ->where('regime_activite.regime_id', $regimeId)
                    ->where('activites_sportives.actif', 1)
                    ->findAll();
    }
}
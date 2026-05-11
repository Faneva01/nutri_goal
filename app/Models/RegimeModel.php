<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'description',
        'type_regime',
        'intensite',
        'variation_quotidienne',
        'prix_jour',
        'pourcentage_viande',
        'pourcentage_poisson',
        'pourcentage_volaille',
        'actif'
    ];

    public function getAllActive()
    {
        return $this->where('actif', 1)->findAll();
    }

    public function getByType(string $type)
    {
        return $this->where('type_regime', $type)
                    ->where('actif', 1)
                    ->findAll();
    }

    public function getById(int $id): ?array
    {
        return $this->where('id', $id)->where('actif', 1)->first();
    }
}

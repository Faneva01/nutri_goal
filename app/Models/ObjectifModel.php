<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table = 'objectifs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'utilisateur_id',
        'type_objectif',
        'poids_cible'
    ];

    public function getLatestByUser(int $userId)
    {
        return $this->where('utilisateur_id', $userId)
                    ->orderBy('id', 'DESC')
                    ->first();
    }
}
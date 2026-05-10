<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'administrateurs';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom_complet',
        'email',
        'mot_de_passe',
        'role',
        'actif',
        'derniere_connexion'
    ];

    protected $returnType = 'array';

    public function getAdminByEmail(string $email)
    {
        return $this
            ->where('email', $email)
            ->where('actif', 1)
            ->first();
    }
}
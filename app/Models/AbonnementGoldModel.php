<?php

namespace App\Models;

use CodeIgniter\Model;

class AbonnementGoldModel extends Model
{
    protected $table      = 'abonnements_gold';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'utilisateur_id',
        'prix_paye',
        'remise_percent',
        'actif',
        'date_achat'
    ];

    protected $useTimestamps = false;

    public function activateGold(int $userId, float $prix): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Désactiver les anciens abonnements gold s'il y en a
        $db->table($this->table)
           ->where('utilisateur_id', $userId)
           ->update(['actif' => 0]);

        // 2. Créer le nouvel abonnement
        $this->insert([
            'utilisateur_id' => $userId,
            'prix_paye'      => $prix,
            'remise_percent' => 15,
            'actif'          => 1,
            'date_achat'     => date('Y-m-d H:i:s')
        ]);

        // 3. Mettre à jour le flag dans utilisateurs
        $db->table('utilisateurs')
           ->where('id', $userId)
           ->update(['option_gold' => 1]);

        $db->transComplete();
        return $db->transStatus();
    }
}

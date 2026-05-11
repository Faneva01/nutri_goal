<?php
// ============================================================
// app/Models/CodePortefeuilleModel.php
// ============================================================
namespace App\Models;

use CodeIgniter\Model;

class CodePortefeuilleModel extends Model
{
    protected $table         = 'codes_solde';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'code','montant','utilisateur_id','date_utilisation','est_utilise',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'date_creation';
    protected $updatedField  = false;

    /**
     * Cherche un code libre (non encore utilisé par un utilisateur)
     */
    public function findCode(string $code): ?array
    {
        return $this->where('code', $code)
                    ->where('est_utilise', 0)
                    ->first();
    }

    /**
     * Utilise le code : associe à l'utilisateur et date
     */
    public function utiliserCode(int $codeId, int $userId): bool
    {
        return $this->update($codeId, [
            'utilisateur_id'   => $userId,
            'date_utilisation' => date('Y-m-d H:i:s'),
            'est_utilise'      => 1,
        ]);
    }
}

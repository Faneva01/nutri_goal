<?php
// ============================================================
// app/Models/RegimeModel.php
// ============================================================
namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table         = 'regimes';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'nom','description','type_regime','intensite',
        'variation_quotidienne','prix_jour',
        'pourcentage_viande','pourcentage_poisson','pourcentage_volaille','actif',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'date_creation';
    protected $updatedField  = 'date_modification';

    public function getActifs()
    {
        return $this->where('actif', 1)->orderBy('nom')->findAll();
    }

    public function getByType(string $type)
    {
        return $this->where('actif', 1)->where('type_regime', $type)->findAll();
    }
}

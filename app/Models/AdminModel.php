<?php
// ============================================================
// app/Models/AdminModel.php
// ============================================================
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'administrateurs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nom_complet','email','mot_de_passe',
        'role','actif','derniere_connexion',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'date_creation';
    protected $updatedField     = 'date_modification';
}

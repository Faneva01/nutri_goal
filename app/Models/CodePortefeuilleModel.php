<?php
namespace App\Models;

use CodeIgniter\Model;

/*

-- Table codes solde
CREATE TABLE codes_solde (
  id INT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(20) UNIQUE NOT NULL,
  montant DECIMAL(10, 2) NOT NULL,
  utilisateur_id INT COMMENT 'NULL si non utilisé',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_utilisation TIMESTAMP NULL,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

*/
class CodePortefeuilleModel extends Model
{
    protected $table = 'codes_solde';
    protected $allowedFields = ['code', 'montant', 'utilisateur_id', 'date_utilisation'];

    public function creerCode($code, $montant)
    {
        $data = [
            'code' => $code,
            'montant' => $montant
        ];
        return $this->insert($data);
    }
}
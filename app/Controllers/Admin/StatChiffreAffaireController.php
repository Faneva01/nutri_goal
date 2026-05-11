<?php
// ============================================================
// app/Controllers/Admin/StatChiffreAffaireController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StatChiffreAffaireController extends BaseController
{
    public function index()
    {
        if (! session()->get('admin_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Non autorisé']);
        }

        $db = \Config\Database::connect();

        $rows = $db->query(
            "SELECT DATE_FORMAT(date_transaction,'%Y-%m') AS mois,
                    SUM(CASE WHEN type_transaction='achat_regime' THEN montant ELSE 0 END) AS regimes,
                    SUM(CASE WHEN type_transaction='achat_gold'   THEN montant ELSE 0 END) AS gold
             FROM historique_transactions
             WHERE type_transaction IN ('achat_regime','achat_gold')
             GROUP BY mois ORDER BY mois ASC LIMIT 12"
        )->getResultArray();

        return $this->response->setJSON([
            'labels'  => array_column($rows, 'mois'),
            'regimes' => array_map('floatval', array_column($rows, 'regimes')),
            'gold'    => array_map('floatval', array_column($rows, 'gold')),
        ]);
    }
}

<?php
// ============================================================
// app/Controllers/Admin/StatRegimeController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StatRegimeController extends BaseController
{
    public function index()
    {
        if (! session()->get('admin_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Non autorisé']);
        }

        $db = \Config\Database::connect();

        $rows = $db->query(
            "SELECT r.nom, COUNT(ar.id) AS abonnes
             FROM abonnements_regimes ar
             JOIN regimes r ON r.id = ar.regime_id
             GROUP BY ar.regime_id ORDER BY abonnes DESC LIMIT 7"
        )->getResultArray();

        return $this->response->setJSON([
            'labels' => array_column($rows, 'nom'),
            'data'   => array_map('intval', array_column($rows, 'abonnes')),
        ]);
    }
}

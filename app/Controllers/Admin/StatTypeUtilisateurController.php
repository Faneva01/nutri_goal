<?php
// ============================================================
// app/Controllers/Admin/StatTypeUtilisateurController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StatTypeUtilisateurController extends BaseController
{
    public function index()
    {
        if (! session()->get('admin_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Non autorisé']);
        }

        $db    = \Config\Database::connect();
        $total = $db->table('utilisateurs')->countAllResults();
        $gold  = $db->table('abonnements_gold')->where('actif', 1)
                    ->select('COUNT(DISTINCT utilisateur_id) AS n')
                    ->get()->getRow()->n ?? 0;

        return $this->response->setJSON([
            'labels' => ['Abonnés Gold', 'Utilisateurs Standard'],
            'data'   => [(int)$gold, max(0, $total - (int)$gold)],
        ]);
    }
}

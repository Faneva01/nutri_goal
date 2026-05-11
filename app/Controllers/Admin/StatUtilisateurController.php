<?php
// ============================================================
// app/Controllers/Admin/StatUtilisateurController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StatUtilisateurController extends BaseController
{
    public function index()
    {
        if (! session()->get('admin_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Non autorisé']);
        }

        $db = \Config\Database::connect();

        $rows = $db->query(
            "SELECT DATE_FORMAT(date_inscription,'%Y-%m') AS mois, COUNT(*) AS total
             FROM utilisateurs
             GROUP BY mois ORDER BY mois ASC LIMIT 12"
        )->getResultArray();

        return $this->response->setJSON([
            'labels' => array_column($rows, 'mois'),
            'data'   => array_column($rows, 'total'),
        ]);
    }
}

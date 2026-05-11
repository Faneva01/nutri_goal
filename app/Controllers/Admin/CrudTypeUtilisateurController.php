<?php
// ============================================================
// app/Controllers/Admin/CrudTypeUtilisateurController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class CrudTypeUtilisateurController extends BaseController
{
    public function __construct()
    {
        if (! session()->get('admin_id')) {
            redirect()->to('/admin/login')->send(); exit;
        }
    }

    // ── Liste utilisateurs avec statut Gold ─────────────────
    public function index()
    {
        $db = \Config\Database::connect();

        $utilisateurs = $db->query(
            "SELECT u.*,
                    ag.id AS gold_id,
                    ag.actif AS gold_actif,
                    ag.date_achat
             FROM utilisateurs u
             LEFT JOIN abonnements_gold ag ON ag.utilisateur_id = u.id
             ORDER BY u.id DESC"
        )->getResultArray();

        return view('pages/admin/gold/index', [
            'title'        => 'Utilisateurs Gold',
            'utilisateurs' => $utilisateurs,
        ]);
    }

    // ── Basculer statut Gold ────────────────────────────────
    public function toggle(int $userId)
    {
        $db = \Config\Database::connect();
        $gold = $db->table('abonnements_gold')->where('utilisateur_id', $userId)->get()->getRow();

        if ($gold) {
            $newActif = $gold->actif ? 0 : 1;
            $db->table('abonnements_gold')
               ->where('utilisateur_id', $userId)
               ->update(['actif' => $newActif]);
        } else {
            $db->table('abonnements_gold')->insert([
                'utilisateur_id' => $userId,
                'prix_paye'      => 84.99,
                'remise_percent' => 15,
                'actif'          => 1,
            ]);
        }

        return redirect()->to('/admin/gold')->with('success', 'Statut Gold mis à jour.');
    }
}

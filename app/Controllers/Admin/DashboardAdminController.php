<?php
// ============================================================
// app/Controllers/Admin/DashboardAdminController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Database\BaseConnection;

class DashboardAdminController extends BaseController
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        if (! session()->get('admin_id')) {
            redirect()->to('/admin/login')->send(); exit;
        }
    }

    public function index()
    {
        // ── KPI globaux ─────────────────────────────────────
        $totalUsers   = $this->db->table('utilisateurs')->countAllResults();
        $totalGold    = $this->db->table('abonnements_gold')->where('actif', 1)->countAllResults();
        $totalRegimes = $this->db->table('abonnements_regimes')->where('statut', 'actif')->countAllResults();

        $ca = $this->db->query(
            "SELECT COALESCE(SUM(montant),0) AS total
             FROM historique_transactions
             WHERE type_transaction IN ('achat_regime','achat_gold')"
        )->getRow();

        // ── Évolution inscriptions (6 derniers mois) ────────
        $inscriptions = $this->db->query(
            "SELECT DATE_FORMAT(date_inscription,'%Y-%m') AS mois, COUNT(*) AS total
             FROM utilisateurs
             GROUP BY mois ORDER BY mois ASC LIMIT 6"
        )->getResultArray();

        // ── Répartition Simple / Gold ───────────────────────
        $goldIds = $this->db->query(
            "SELECT DISTINCT utilisateur_id FROM abonnements_gold WHERE actif=1"
        )->getResultArray();
        $goldCount   = count($goldIds);
        $simpleCount = max(0, $totalUsers - $goldCount);

        // ── CA mensuel ──────────────────────────────────────
        $caMonthly = $this->db->query(
            "SELECT DATE_FORMAT(date_transaction,'%Y-%m') AS mois,
                    SUM(montant) AS total
             FROM historique_transactions
             WHERE type_transaction IN ('achat_regime','achat_gold')
             GROUP BY mois ORDER BY mois ASC LIMIT 6"
        )->getResultArray();

        // ── Régimes populaires ──────────────────────────────
        $regimesPopulaires = $this->db->query(
            "SELECT r.nom, COUNT(ar.id) AS abonnes
             FROM abonnements_regimes ar
             JOIN regimes r ON r.id = ar.regime_id
             GROUP BY ar.regime_id ORDER BY abonnes DESC LIMIT 7"
        )->getResultArray();

        // ── Derniers abonnements ─────────────────────────────
        $derniersAbonnements = $this->db->query(
            "SELECT u.nom_complet, r.nom AS regime, ar.date_debut, ar.statut
             FROM abonnements_regimes ar
             JOIN utilisateurs u ON u.id = ar.utilisateur_id
             JOIN regimes r ON r.id = ar.regime_id
             ORDER BY ar.id DESC LIMIT 8"
        )->getResultArray();

        return view('pages/admin/dashboard-admin', [
            'title'               => 'Dashboard Admin – NutriGoal',
            'show_navbar'         => false,
            'admin_nom'           => session()->get('admin_nom'),
            'totalUsers'          => $totalUsers,
            'totalGold'           => $totalGold,
            'totalRegimes'        => $totalRegimes,
            'caTotal'             => $ca->total ?? 0,
            'inscriptions'        => $inscriptions,
            'goldCount'           => $goldCount,
            'simpleCount'         => $simpleCount,
            'caMonthly'           => $caMonthly,
            'regimesPopulaires'   => $regimesPopulaires,
            'derniersAbonnements' => $derniersAbonnements,
        ]);
    }
}

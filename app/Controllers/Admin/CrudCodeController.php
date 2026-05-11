<?php
// ============================================================
// app/Controllers/Admin/CrudCodeController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodePortefeuilleModel;

class CrudCodeController extends BaseController
{
    protected CodePortefeuilleModel $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePortefeuilleModel();
        if (! session()->get('admin_id')) {
            redirect()->to('/admin/login')->send(); exit;
        }
    }

    // ── Liste des codes ─────────────────────────────────────
    public function index()
    {
        $db = \Config\Database::connect();

        $codes = $db->query(
            "SELECT cs.*, u.nom_complet AS utilisateur_nom
             FROM codes_solde cs
             LEFT JOIN utilisateurs u ON u.id = cs.utilisateur_id
             ORDER BY cs.id DESC"
        )->getResultArray();

        return view('pages/admin/codes/index', [
            'title' => 'Gestion des Codes Portefeuille',
            'codes' => $codes,
        ]);
    }

    // ── Valider un code (associer à un utilisateur) ─────────
    public function valider(int $id)
    {
        $code = $this->codeModel->find($id);
        if (! $code || $code['utilisateur_id'] !== null) {
            return redirect()->to('/admin/codes')->with('error', 'Code déjà utilisé ou introuvable.');
        }
        // Validation manuelle admin (sans utilisateur = code libre disponible)
        // Ici on marque la date d'utilisation pour confirmer
        $this->codeModel->update($id, ['date_utilisation' => date('Y-m-d H:i:s')]);
        return redirect()->to('/admin/codes')->with('success', 'Code validé.');
    }

    // ── Générer de nouveaux codes ───────────────────────────
    public function generer()
    {
        $montant  = (float) $this->request->getPost('montant');
        $quantite = (int)   $this->request->getPost('quantite');

        if ($montant <= 0 || $quantite <= 0 || $quantite > 50) {
            return redirect()->to('/admin/codes')->with('error', 'Paramètres invalides.');
        }

        $codes = [];
        for ($i = 0; $i < $quantite; $i++) {
            $code = strtoupper(bin2hex(random_bytes(5)));
            $this->codeModel->insert(['code' => $code, 'montant' => $montant]);
            $codes[] = $code;
        }

        return redirect()->to('/admin/codes')->with('success', $quantite . ' code(s) généré(s) : ' . implode(', ', $codes));
    }
}

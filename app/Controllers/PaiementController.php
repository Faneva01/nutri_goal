<?php
// ============================================================
// app/Controllers/PaiementController.php
// ============================================================
namespace App\Controllers;

use App\Models\CodePortefeuilleModel;

class PaiementController extends BaseController
{
    public function __construct()
    {
        if (! session()->get('user_id')) {
            redirect()->to('/login')->send(); exit;
        }
    }

    // ── GET /portefeuille/acheter ────────────────────────────
    public function index()
    {
        return view('pages/portefeuille/achat-code', [
            'title'  => 'Recharger – NutriGoal',
            'styles' => ['portefeuille/achat-code.css'],
            'user'   => $this->_getUser(),
        ]);
    }

    // ── POST /portefeuille/payer ─────────────────────────────
    public function payer()
    {
        $montant = (float) $this->request->getPost('montant');
        $moyen   = $this->request->getPost('moyen'); // mvola | orange_money | airtel

        $moyensValides = ['mvola', 'orange_money', 'airtel'];
        if ($montant < 1000 || ! in_array($moyen, $moyensValides)) {
            return redirect()->back()->with('error', 'Montant ou moyen de paiement invalide.')->withInput();
        }

        // Simulation paiement mobile money : génération d'un code que l'utilisateur va saisir
        $db      = \Config\Database::connect();
        $codeStr = strtoupper(bin2hex(random_bytes(5)));

        $db->table('codes_solde')->insert([
            'code'    => $codeStr,
            'montant' => $montant,
        ]);

        // Stocker en session pour la page confirmation
        session()->set([
            'paiement_code'    => $codeStr,
            'paiement_montant' => $montant,
            'paiement_moyen'   => $moyen,
        ]);

        return redirect()->to('/portefeuille/confirmation');
    }

    // ── GET /portefeuille/confirmation ───────────────────────
    public function confirmation()
    {
        $code    = session()->get('paiement_code');
        $montant = session()->get('paiement_montant');
        $moyen   = session()->get('paiement_moyen');

        if (! $code) return redirect()->to('/portefeuille/acheter');

        // On efface après affichage
        session()->remove(['paiement_code','paiement_montant','paiement_moyen']);

        return view('pages/portefeuille/confirmation', [
            'title'   => 'Confirmation – NutriGoal',
            'styles'  => ['portefeuille/achat-code.css'],
            'user'    => $this->_getUser(),
            'code'    => $code,
            'montant' => $montant,
            'moyen'   => $moyen,
        ]);
    }

    private function _getUser(): array
    {
        $db = \Config\Database::connect();
        return $db->table('utilisateurs')->where('id', session()->get('user_id'))->get()->getRowArray() ?? [];
    }
}
<?php
// ============================================================
// app/Controllers/ValidationCodeController.php
// ============================================================
namespace App\Controllers;

use App\Models\CodePortefeuilleModel;

class ValidationCodeController extends BaseController
{
    protected CodePortefeuilleModel $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePortefeuilleModel();
        if (! session()->get('user_id')) {
            redirect()->to('/login')->send(); exit;
        }
    }

    // ── GET /portefeuille ────────────────────────────────────
    public function index()
    {
        $db      = \Config\Database::connect();
        $userId  = session()->get('user_id');
        $user    = $db->table('utilisateurs')->where('id', $userId)->get()->getRowArray();

        $transactions = $db->table('historique_transactions')
            ->where('utilisateur_id', $userId)
            ->orderBy('date_transaction', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        return view('pages/portefeuille/validation-code', [
            'title'        => 'Mon Portefeuille – NutriGoal',
            'styles'       => ['portefeuille/validation-code.css'],
            'user'         => $user,
            'transactions' => $transactions,
        ]);
    }

    public function valider()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $userId  = (int) session()->get('user_id');
            $codeStr = strtoupper(trim($this->request->getPost('code') ?? ''));

            if (empty($codeStr)) {
                return $this->response->setJSON([
                    'ok' => false,
                    'message' => 'Code vide.'
                ]);
            }

            // LOCK utilisateur (évite double update simultané)
            $user = $db->table('utilisateurs')
                ->where('id', $userId)
                ->get()
                ->getRowArray();

            if (!$user) {
                throw new \Exception("Utilisateur introuvable");
            }

            // VERROUILLAGE DU CODE (anti double usage)
            $code = $db->table('codes_solde')
                ->where('code', $codeStr)
                ->where('est_utilise', 0)
                ->get()
                ->getRowArray();

            if (!$code || !isset($code['id'])) {
                return $this->response->setJSON([
                    'ok' => false,
                    'message' => 'Code invalide ou déjà utilisé.'
                ]);
            }

            $ancienSolde  = (float) $user['solde'];
            $montant      = (float) $code['montant'];
            $nouveauSolde = $ancienSolde + $montant;

            // 1. UPDATE SOLDE UTILISATEUR
            $db->table('utilisateurs')
                ->where('id', $userId)
                ->update(['solde' => $nouveauSolde]);

            // 2. MARQUER CODE COMME UTILISÉ (IMPORTANT: sécurisé)
            $updated = $db->table('codes_solde')
                ->where('id', (int) $code['id'])
                ->where('est_utilise', 0) // anti double clic
                ->update([
                    'utilisateur_id'   => $userId,
                    'est_utilise'      => 1,
                    'date_utilisation' => date('Y-m-d H:i:s')
                ]);

            if (!$updated) {
                throw new \Exception("Code déjà utilisé pendant l'opération");
            }

            // 3. HISTORIQUE
            $db->table('historique_transactions')->insert([
                'utilisateur_id'   => $userId,
                'type_transaction' => 'ajout_code',
                'montant'          => $montant,
                'ancien_solde'     => $ancienSolde,
                'nouveau_solde'    => $nouveauSolde,
                'description'      => 'Code rechargé : ' . $codeStr,
            ]);

            // COMMIT
            $db->transCommit();

            return $this->response->setJSON([
                'ok' => true,
                'message' => '+ ' . number_format($montant, 2) . ' Ar ajouté à votre solde.',
                'nouveau_solde' => number_format($nouveauSolde, 2),
            ]);

        } catch (\Throwable $e) {

            // ROLLBACK sécurité
            $db->transRollback();

            log_message('error', $e->getMessage());

            return $this->response->setJSON([
                'ok' => false,
                'message' => 'Erreur lors de la validation du code.'
            ]);
        }
    }
}
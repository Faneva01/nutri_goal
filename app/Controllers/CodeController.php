<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;
use App\Models\TransactionModel;

class CodeController extends BaseController
{
    protected CodePortefeuilleModel $codeModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->codeModel = new CodePortefeuilleModel();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * PAGE 1 : ACHAT (Saisie Montant)
     */
    public function achat()
    {
        return view('pages/portefeuille/achat_step1', [
            'title'  => 'Acheter un code | Nutri Goal',
            'styles' => ['portefeuille.css']
        ]);
    }

    /**
     * TRAITEMENT ÉTAPE 1 -> REDIRECT VERS PAIEMENT
     */
    public function traiterAchat()
    {
        $montant = $this->request->getPost('montant');
        
        if (!$montant || $montant < 1000) {
            return redirect()->back()->with('error', 'Le montant minimum est de 1 000 Ar.');
        }

        // Création du code (est_utilise = 0)
        $codeId = $this->codeModel->creerCode((float)$montant);

        if (!$codeId) {
            return redirect()->back()->with('error', 'Erreur lors de la génération du code.');
        }

        return redirect()->to('/paiement/choisir/' . $codeId);
    }

    /**
     * PAGE : VALIDATION (J'ai un code)
     */
    public function validation()
    {
        return view('pages/portefeuille/validation_page', [
            'title'  => 'Valider un code | Nutri Goal',
            'styles' => ['portefeuille.css'],
            'scripts' => ['portefeuille/portefeuille.js']
        ]);
    }

    /**
     * TRAITEMENT VALIDATION (Crédit réel)
     */
    public function traiterValidation()
    {
        $userId = session('user_id');
        if (!$userId) return redirect()->to('/login');

        $code = strtoupper(trim($this->request->getPost('code')));

        if (empty($code)) {
            return redirect()->back()->with('error', 'Veuillez saisir un code.');
        }

        if (!$this->codeModel->estValide($code)) {
            return redirect()->back()->with('error', 'Code invalide, déjà utilisé ou expiré.');
        }

        if ($this->codeModel->utiliserCode($code, $userId)) {
            // Mettre à jour la session
            $db = \Config\Database::connect();
            $user = $db->table('utilisateurs')->select('solde')->where('id', $userId)->get()->getRowArray();
            session()->set('solde', $user['solde']);

            return redirect()->to('/dashboard')->with('success', 'Votre compte a été crédité avec succès !');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation.');
    }

    /**
     * AJAX POUR LE JS
     */
    public function verifierCode()
    {
        $code = strtoupper(trim($this->request->getPost('code')));
        if ($this->codeModel->estValide($code)) {
            $data = $this->codeModel->trouverParCode($code);
            return $this->response->setJSON(['valid' => true, 'montant' => $data['montant']]);
        }
        return $this->response->setJSON(['valid' => false, 'message' => 'Code invalide']);
    }
}

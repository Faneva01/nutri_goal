<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;
use CodeIgniter\HTTP\ResponseInterface;

class CodeController extends BaseController
{
    protected $codePortefeuilleModel;

    public function __construct()
    {
        $this->codePortefeuilleModel = new CodePortefeuilleModel();
    }

    /**
     * Affiche le formulaire d'achat de code portefeuille
     */
    public function achat()
    {
<<<<<<< HEAD
        return view('pages/achat_code', [
            'title' => 'Achat de Code Portefeuille',
            'styles' => ['style.css']
=======
        return view('portefeuille/achat-code', [
            'title' => 'Achat de Code Portefeuille',
>>>>>>> e323c95 (Added payment validation feature)
        ]);
    }

    /**
     * Traite l'achat d'un code portefeuille
     */
    public function traiterAchat()
    {
        $montant = $this->request->getPost('montant');
        $moyenPaiement = $this->request->getPost('moyen_paiement');

        // Validation des données
        if (!$this->validate([
            'montant' => 'required|numeric|greater_than[0]',
            'moyen_paiement' => 'required|in_list[mvola,airtel_money,orange_money,carte_bancaire]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Générer le code
        $codeId = $this->codePortefeuilleModel->creerCode((float) $montant);

        if ($codeId) {
            $codeData = $this->codePortefeuilleModel->find($codeId);

            // Rediriger vers la page de paiement avec les détails
            return redirect()->to('/paiement/process/' . $codeId)->with('success', 'Code généré avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la génération du code');
    }

    /**
     * Affiche la page de validation du code
     */
    public function validation()
    {
<<<<<<< HEAD
        return view('pages/validation_code', [
            'title' => 'Validation du Code Portefeuille',
            'styles' => ['style.css']
=======
        return view('portefeuille/validation-code', [
            'title' => 'Validation du Code Portefeuille',
>>>>>>> e323c95 (Added payment validation feature)
        ]);
    }

    /**
     * Traite la validation d'un code
     */
    public function traiterValidation()
    {
        $code = $this->request->getPost('code');
        $utilisateurId = session()->get('user_id'); // À adapter selon votre système d'authentification

        if (!$utilisateurId) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        // Validation du code
        if (!$this->codePortefeuilleModel->estValide($code)) {
            return redirect()->back()->with('error', 'Code invalide ou déjà utilisé');
        }

        // Utiliser le code
        if ($this->codePortefeuilleModel->utiliserCode($code, $utilisateurId)) {
            return redirect()->back()->with('success', 'Code validé avec succès ! Le montant a été ajouté à votre portefeuille.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la validation du code');
    }

    /**
     * API endpoint pour vérifier la validité d'un code (AJAX)
     */
    public function verifierCode()
    {
        $code = $this->request->getPost('code');

        if (empty($code)) {
            return $this->response->setJSON(['valid' => false, 'message' => 'Code requis']);
        }

        $estValide = $this->codePortefeuilleModel->estValide($code);

        if ($estValide) {
            $codeData = $this->codePortefeuilleModel->trouverParCode($code);
            return $this->response->setJSON([
                'valid' => true,
                'montant' => $codeData['montant'],
                'message' => 'Code valide'
            ]);
        }

        return $this->response->setJSON(['valid' => false, 'message' => 'Code invalide ou déjà utilisé']);
    }

    /**
     * Liste des codes utilisés par l'utilisateur (pour l'historique)
     */
    public function historique()
    {
        $utilisateurId = session()->get('user_id');

        if (!$utilisateurId) {
            return redirect()->to('/login');
        }

        $codes = $this->codePortefeuilleModel->getCodesUtilisateurs($utilisateurId);

        return view('pages/historique_codes', [
            'title' => 'Historique des Codes',
            'codes' => $codes,
            'styles' => ['style.css']
        ]);
    }
}
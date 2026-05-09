<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;

class ValidationCodeController extends BaseController
{
    protected $codePortefeuilleModel;

    public function __construct()
    {
        $this->codePortefeuilleModel = new CodePortefeuilleModel();
    }

    /**
     * Affiche la page d'intégration du code portefeuille
     */
    public function index()
    {
        return view('pages/integration_code', [
            'title' => 'Intégration du Code Portefeuille',
            'styles' => ['style.css']
        ]);
    }

    /**
     * Traite l'intégration du code saisi par l'utilisateur
     */
    public function integrer()
    {
        $code = $this->request->getPost('code');
        $utilisateurId = $this->getCurrentUserId();

        if (!$utilisateurId) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour intégrer un code.');
        }

        if (empty($code)) {
            return redirect()->back()->withInput()->with('error', 'Le code est requis.');
        }

        if (!$this->codePortefeuilleModel->estValide($code)) {
            return redirect()->back()->with('error', 'Le code est invalide, expiré ou déjà utilisé.');
        }

        if ($this->codePortefeuilleModel->utiliserCode($code, $utilisateurId)) {
            return redirect()->back()->with('success', 'Code intégré avec succès. Votre solde a été mis à jour.');
        }

        return redirect()->back()->with('error', 'Impossible d’intégrer le code pour le moment.');
    }

    protected function getCurrentUserId()
    {
        $session = session();

        if ($session->has('user_id')) {
            return $session->get('user_id');
        }

        if ($session->has('id')) {
            return $session->get('id');
        }

        $user = $session->get('user');
        if (is_array($user) && isset($user['id'])) {
            return $user['id'];
        }

        if (is_object($user) && isset($user->id)) {
            return $user->id;
        }

        return null;
    }

    /**
     * Vérifie la validité d'un code via AJAX
     */
    public function verifier()
    {
        $code = $this->request->getPost('code');

        if (empty($code)) {
            return $this->response->setJSON([
                'valid' => false,
                'message' => 'Le code est requis.'
            ]);
        }

        if ($this->codePortefeuilleModel->estValide($code)) {
            $codeData = $this->codePortefeuilleModel->trouverParCode($code);
            return $this->response->setJSON([
                'valid' => true,
                'montant' => $codeData['montant'],
                'message' => 'Code valide et utilisable.'
            ]);
        }

        return $this->response->setJSON([
            'valid' => false,
            'message' => 'Code invalide ou déjà utilisé.'
        ]);
    }
}

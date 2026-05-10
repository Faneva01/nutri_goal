<?php

namespace App\Controllers;

use App\Models\ProfilModel;

class ProfilController extends BaseController
{
    protected ProfilModel $profilModel;

    public function __construct()
    {
        $this->profilModel = new ProfilModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user   = $userId ? $this->profilModel->find($userId) : null;

        if ($user === null) {
            return redirect()->to('/login');
        }

        return view('pages/profil/profil_page', [
            'title'               => 'Mon profil',
            'user'                => $user,
            'navView'             => 'inc/nav',
            'prochainRappel'      => 'Lundi 08:30 - Hydratation et petit-déjeuner',
            'historiqueActivites' => [
                ['date' => '2026-05-08', 'label' => 'Marche rapide',       'valeur' => '35 min'],
                ['date' => '2026-05-07', 'label' => 'Objectif eau',        'valeur' => '2.0 L'],
                ['date' => '2026-05-06', 'label' => 'Poids enregistré',    'valeur' => ($user['poids'] ?? 70) . ' kg'],
            ],
            'styles'  => ['profil-page.css'],
            'scripts' => ['profil-page.js'],
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $data = [
            'nom_complet' => $this->request->getPost('fullname'),
            'email'       => $this->request->getPost('email'),
            'genre'       => $this->request->getPost('genre'),
            'taille'      => (int)   $this->request->getPost('taille'),
            'poids'       => (float) $this->request->getPost('poids'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $existing = $this->profilModel->where('email', $data['email'])
                                      ->where('id !=', $userId)
                                      ->first();
        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cet email est déjà utilisé par un autre compte.',
            ]);
        }

        if (!$this->profilModel->update($userId, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $this->profilModel->errors()),
            ]);
        }

        $updated = $this->profilModel->find($userId);

        session()->set([
            'nom_complet' => $updated['nom_complet'],
            'email'       => $updated['email'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'imc'     => $updated['imc'],
        ]);
    }

    public function toggleGold()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $optionGold = (int) $this->request->getPost('option_gold');

        // skipValidation car on ne touche qu'à option_gold, pas aux champs requis.
        if (!$this->profilModel->skipValidation(true)->update($userId, ['option_gold' => $optionGold])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Erreur lors de la mise à jour de l'option Gold.",
            ]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function rechargerSolde()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $code = strtoupper(trim($this->request->getPost('code')));

        // Codes valides — à terme, mettre en base via la table codes_solde.
        $codesValides = [
            'PROMO10'   => 10.00,
            'PROMO20'   => 20.00,
            'BIENVENUE' =>  5.00,
        ];

        if (!array_key_exists($code, $codesValides)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide ou expiré.',
            ]);
        }

        $montant   = $codesValides[$code];
        $recharged = $this->profilModel->rechargerSolde($userId, $montant);

        if (!$recharged) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la recharge.',
            ]);
        }

        $user = $this->profilModel->find($userId);

        // Mettre à jour le solde en session.
        session()->set('solde', $user['solde']);

        return $this->response->setJSON([
            'success'       => true,
            'message'       => '+' . number_format($montant, 2) . ' € ajoutés à votre solde.',
            'nouveau_solde' => number_format((float) $user['solde'], 2),
        ]);
    }
}
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
        $user = $userId ? $this->profilModel->find($userId) : null;

        // Meme logique "simple" que vos anciens controllers:
        // on rend la vue meme si la session n'est pas encore en place.
        if ($user === null) {
            $user = [
                'nom_complet' => 'Utilisateur',
                'email' => '',
                'genre' => 'Autre',
                'taille' => 170,
                'poids' => 70,
                'imc' => 24.22,
                'option_gold' => 0,
                'solde' => 0,
                'objectif_principal' => 'Maintien du poids',
                'objectif_cible' => '70 kg',
                'dernier_poids' => '70.0 kg',
            ];
        }

        return view('pages/profil/profil_page', [
            'title' => 'Mon profil',
            'user'  => $user,
            'navView' => 'inc/nav_profil',
            'prochainRappel' => 'Lundi 08:30 - Hydratation et petit-dejeuner',
            'historiqueActivites' => [
                ['date' => '2026-05-08', 'label' => 'Marche rapide', 'valeur' => '35 min'],
                ['date' => '2026-05-07', 'label' => 'Objectif eau', 'valeur' => '2.0 L'],
                ['date' => '2026-05-06', 'label' => 'Poids enregistre', 'valeur' => ($user['poids'] ?? 70) . ' kg'],
            ],
            'styles' => [
                'profil-page.css',
            ],
            'scripts' => [
                'profil-page.js',
            ],
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $nomComplet = $this->request->getPost('fullname');
        $email      = $this->request->getPost('email');
        $genre      = $this->request->getPost('genre');
        $taille     = (int) $this->request->getPost('taille');
        $poids      = (float) $this->request->getPost('poids');
        $password   = $this->request->getPost('password');

        $imc = $taille > 0
            ? round($poids / (($taille / 100) ** 2), 2)
            : 0.0;

        $data = [
            'nom_complet' => $nomComplet,
            'email'       => $email,
            'genre'       => $genre,
            'taille'      => $taille,
            'poids'       => $poids,
            'imc'         => $imc,
        ];

        if (!empty($password)) {
            $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->profilModel->skipValidation(false);

        if (!$this->profilModel->update($userId, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $this->profilModel->errors()),
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'imc'     => $imc,
        ]);
    }

    public function toggleGold()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $optionGold = (int) $this->request->getPost('option_gold');

        $updated = $this->profilModel->update($userId, ['option_gold' => $optionGold]);

        if (!$updated) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'option Gold.',
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

        $codesValides = [
            'PROMO10'    => 10.00,
            'PROMO20'    => 20.00,
            'BIENVENUE'  => 5.00,
        ];

        if (!array_key_exists($code, $codesValides)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide ou expiré.',
            ]);
        }

        $montant = $codesValides[$code];
        $recharged = $this->profilModel->rechargerSolde($userId, $montant);

        if (!$recharged) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur lors de la recharge.',
            ]);
        }

        $user = $this->profilModel->find($userId);

        return $this->response->setJSON([
            'success'       => true,
            'message'       => '+' . number_format($montant, 2) . ' € ajoutés à votre solde.',
            'nouveau_solde' => number_format((float) $user['solde'], 2),
        ]);
    }
}
<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\AbonnementRegimeModel;
use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Services\RegimeService;

class RegimeSelectController extends BaseController
{
    protected RegimeModel $regimeModel;
    protected AbonnementRegimeModel $abonnementModel;
    protected UserModel $userModel;
    protected ObjectifModel $objectifModel;
    protected RegimeService $regimeService;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->abonnementModel = new AbonnementRegimeModel();
        $this->userModel = new UserModel();
        $this->objectifModel = new ObjectifModel();
        $this->regimeService = new RegimeService();
    }

    /**
     * PREVIEW
     */
    public function preview(int $regimeId)
    {
        $userId = session('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $regime = $this->regimeModel->find($regimeId);

        if (!$regime) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Régime introuvable'
            ]);
        }

        $user = $this->userModel->find($userId);
        $objectif = $this->objectifModel->getLatestByUser($userId);

        return $this->response->setJSON([
            'success' => true,
            'regime' => $regime,
            'user_weight' => $user['poids'],
            'target_weight' => $objectif['poids_cible'] ?? null
        ]);
    }

    /**
     * CALCUL PRIX (SERVICE CLEAN)
     */
    public function calculatePrice(int $regimeId)
    {
        $duree = $this->request->getPost('duree_jours');

        $regime = $this->regimeModel->find($regimeId);

        if (!$regime) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Régime introuvable'
            ]);
        }

        $prix = $this->regimeService->calculatePrice(
            $regime['prix_jour'],
            (int)$duree
        );

        $userId = session('user_id');
        $gold = $this->regimeService->isGold($userId);

        if ($gold) {
            $prix = $this->regimeService->applyGoldDiscount($prix);
        }

        return $this->response->setJSON([
            'success' => true,
            'prix_total' => round($prix, 2),
            'remise' => $gold
        ]);
    }

    /**
     * SUBSCRIBE CLEAN
     */
    public function subscribe(int $regimeId)
    {
        $userId = session('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $regime = $this->regimeModel->find($regimeId);

        if (!$regime) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Régime introuvable'
            ]);
        }

        $duree = (int)$this->request->getPost('duree_jours');
        $user = $this->userModel->find($userId);
        $objectif = $this->objectifModel->getLatestByUser($userId);

        $poidsInitial = $this->request->getPost('poids_initial') ?? $user['poids'];
        $poidsCible = $this->request->getPost('poids_cible') ?? ($objectif['poids_cible'] ?? $user['poids']);
        
        // RE-CALC PRIX POUR SÉCURITÉ
        $prixTotal = $this->regimeService->calculatePrice($regime['prix_jour'], $duree);
        if ($this->regimeService->isGold($userId)) {
            $prixTotal = $this->regimeService->applyGoldDiscount($prixTotal);
        }
        $prixTotal = round($prixTotal, 2);

        $user = $this->userModel->find($userId);

        if ($user['solde'] < $prixTotal) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant'
            ]);
        }

        $data = [
            'utilisateur_id' => $userId,
            'regime_id' => $regimeId,
            'poids_initial' => $poidsInitial,
            'poids_cible' => $poidsCible,
            'duree_estimee_jours' => $duree,
            'date_debut' => date('Y-m-d'),
            'date_fin' => date('Y-m-d', strtotime("+$duree days")),
            'prix_total' => $prixTotal,
            'statut' => 'actif'
        ];

        $this->abonnementModel->insert($data);

        // Déduire le solde
        $nouveauSolde = $user['solde'] - $prixTotal;
        $this->userModel->update($userId, [
            'solde' => $nouveauSolde
        ]);

        // Enregistrer la transaction
        $transactionModel = new \App\Models\TransactionModel();
        $transactionModel->insert([
            'utilisateur_id' => $userId,
            'type_transaction' => 'achat_regime',
            'montant' => $prixTotal,
            'ancien_solde' => $user['solde'],
            'nouveau_solde' => $nouveauSolde,
            'description' => "Achat régime : " . $regime['nom'] . " (" . $duree . " jours)",
            'date_transaction' => date('Y-m-d H:i:s')
        ]);

        session()->set('solde', $nouveauSolde);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Régime activé avec succès',
            'redirect' => base_url('/dashboard')
        ]);
    }
}
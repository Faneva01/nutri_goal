<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Services\RegimeService;

class RecommendationController extends BaseController
{
    protected UserModel $userModel;
    protected ObjectifModel $objectifModel;
    protected RegimeService $regimeService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->objectifModel = new ObjectifModel();
        $this->regimeService = new RegimeService();
    }

    /**
     * RECOMMANDATION PROPRE
     */
    public function recommend(int $userId)
    {
        // USER
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Utilisateur introuvable'
            ]);
        }

        // OBJECTIF
        $objectif = $this->objectifModel->getLatestByUser($userId);

        if (!$objectif) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Objectif manquant'
            ]);
        }

        // LOGIQUE DÉPLACÉE DANS SERVICE
        $result = $this->regimeService->recommend($user, $objectif);

        return $this->response->setJSON($result);
    }
}
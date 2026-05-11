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

    public function recommend()
    {
        $userId = session('user_id');

        if (!$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Non authentifié']);
            }
            return redirect()->to('/login');
        }

        // USER
        $user = $this->userModel->find($userId);
        $objectif = $this->objectifModel->getLatestByUser($userId);

        if (!$objectif) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Objectif manquant']);
            }
            return view('pages/recommendation_page', [
                'title' => 'Recommandations | Nutri Goal',
                'error' => 'Veuillez d\'abord définir un objectif dans votre profil.'
            ]);
        }

        $result = $this->regimeService->recommend($user, $objectif);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        // Récupérer les activités du régime recommandé
        $activities = [];
        if ($result['success'] && isset($result['regime']['id'])) {
            $activityModel = new \App\Models\ActivityModel();
            $activities = $activityModel->getByRegime($result['regime']['id']);
        }

        return view('pages/recommendation_page', [
            'title'      => 'Votre Programme | Nutri Goal',
            'result'     => $result,
            'user'       => $user,
            'objectif'   => $objectif,
            'activities' => $activities,
            'styles'     => ['regime/regime.css']
        ]);
    }
}
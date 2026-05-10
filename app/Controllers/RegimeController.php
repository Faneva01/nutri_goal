<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\ActivityModel;
use App\Models\ObjectifModel;
use App\Models\UserModel;

class RegimeController extends BaseController {
    protected RegimeModel $regimeModel;
    protected ActivityModel $activityModel;
    protected ObjectifModel $objectifModel;
    protected UserModel $userModel;

    public function __construct() {
        $this->regimeModel = new RegimeModel();
        $this->activityModel = new ActivityModel();
        $this->objectifModel = new ObjectifModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        $type = $this->request->getGet('type');
        $intensite = $this->request->getGet('intensite');

        $query = $this->regimeModel;

        if ($type && in_array($type, ['perte', 'prise', 'maintien'])) {
            $query = $query->where('type_regime', $type);
        }

        if ($intensite && in_array($intensite, ['legere', 'moderee', 'intense'])) {
            $query = $query->where('intensite', $intensite);
        }

        $regimes = $query->getAllActive();

        return view('pages/regime/regime-list', [
            'regimes' => $regimes,
            'title' => 'Régimes | Nutri Goal',
            'show_navbar' => true,
            'styles' => ['regime/regime.css'],
            'scripts' => ['regime/regime-list.js']
        ]);
    }

    public function show(int $id) {
        $regime = $this->regimeModel->getById($id);

        if (!$regime) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Régime introuvable");
        }

        $activities = $this->activityModel->getByRegime($id);

        $userId = session('user_id');
        $objectif = null;

        if ($userId) {
            $objectif = $this->objectifModel->getLatestByUser($userId);
        }

        return view('pages/regime/regime-detail', [
            'regime' => $regime,
            'activities' => $activities,
            'objectif' => $objectif,
            'title' => $regime['nom'] . ' | Nutri Goal',
            'show_navbar' => true,
            'styles' => ['regime/regime.css'],
            'scripts' => ['regime/regime-detail.js']
        ]);
    }

    public function getActivities(int $regimeId) {
        $activities = $this->activityModel->getByRegime($regimeId);

        return $this->response->setJSON([
            'success' => true,
            'activities' => $activities
        ]);
    }
}
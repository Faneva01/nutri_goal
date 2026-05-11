<?php

namespace App\Controllers;

use App\Models\ObjectifModel;

class ObjectifController extends BaseController
{
    protected ObjectifModel $objectifModel;

    public function __construct()
    {
        $this->objectifModel = new ObjectifModel();
    }

    /**
     * CRÉER OBJECTIF
     */
    public function store()
    {
        $userId = session('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ]);
        }

        $data = [
            'utilisateur_id' => $userId,
            'type_objectif'  => $this->request->getPost('type_objectif'),
            'poids_cible'    => $this->request->getPost('poids_cible'),
        ];

        // VALIDATION SIMPLE MAIS PROPRE
        if (
            empty($data['utilisateur_id']) ||
            empty($data['type_objectif']) ||
            !in_array($data['type_objectif'], ['perte', 'prise', 'imc_ideal'])
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données invalides'
            ]);
        }

        if ($data['poids_cible'] !== null && $data['poids_cible'] < 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Poids cible invalide'
            ]);
        }

        $this->objectifModel->insert($data);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Objectif enregistré'
        ]);
    }

    /**
     * OBJECTIF UTILISATEUR (par ID)
     */
    public function getByUser(int $userId)
    {
        $objectif = $this->objectifModel->getLatestByUser($userId);

        if (!$objectif) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun objectif trouvé'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $objectif
        ]);
    }

    /**
     * OBJECTIF DE L'UTILISATEUR CONNECTÉ
     */
    public function getMyObjectif()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        if (!$this->request->isAJAX()) {
            return redirect()->to('/profil#objectifs');
        }

        $objectif = $this->objectifModel->getLatestByUser($userId);

        if (!$objectif) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun objectif trouvé'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $objectif
        ]);
    }
}
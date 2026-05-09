<?php

namespace App\Controllers;

class TestPaiementController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Test controller works',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function testModel()
    {
        try {
            $model = new \App\Models\CodePortefeuilleModel();
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Model loaded successfully',
                'table' => $model->getTable()
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
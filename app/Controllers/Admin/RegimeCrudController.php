<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\ActivityModel;

class RegimeCrudController extends BaseController
{
    protected RegimeModel $regimeModel;
    protected ActivityModel $activityModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->activityModel = new ActivityModel();
    }

    public function index()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        return view('admin/regimes/index', [
            'title'   => 'Gestion des Régimes',
            'regimes' => $this->regimeModel->findAll(),
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function create()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        
        return view('admin/regimes/form', [
            'title' => 'Nouveau Régime',
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['actif'] = isset($data['actif']) ? 1 : 0;
        
        $this->regimeModel->insert($data);
        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès');
    }

    public function edit(int $id)
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        $regime = $this->regimeModel->find($id);
        if (!$regime) return redirect()->to('/admin/regimes')->with('error', 'Régime introuvable');

        return view('admin/regimes/form', [
            'title'  => 'Modifier le Régime',
            'regime' => $regime,
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        $data['actif'] = isset($data['actif']) ? 1 : 0;

        $this->regimeModel->update($id, $data);
        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour');
    }

    public function delete(int $id)
    {
        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé');
    }
}

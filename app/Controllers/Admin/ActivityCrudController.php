<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityModel;

class ActivityCrudController extends BaseController
{
    protected ActivityModel $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    public function index()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        return view('admin/activites/index', [
            'title'      => 'Gestion des Activités',
            'activites'  => $this->activityModel->findAll(),
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function create()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        
        return view('admin/activites/form', [
            'title' => 'Nouvelle Activité',
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['actif'] = isset($data['actif']) ? 1 : 0;
        
        $this->activityModel->insert($data);
        return redirect()->to('/admin/activites')->with('success', 'Activité créée');
    }

    public function edit(int $id)
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        $activite = $this->activityModel->find($id);
        return view('admin/activites/form', [
            'title'    => 'Modifier l\'Activité',
            'activite' => $activite,
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        $data['actif'] = isset($data['actif']) ? 1 : 0;

        $this->activityModel->update($id, $data);
        return redirect()->to('/admin/activites')->with('success', 'Activité mise à jour');
    }

    public function delete(int $id)
    {
        $this->activityModel->delete($id);
        return redirect()->to('/admin/activites')->with('success', 'Activité supprimée');
    }
}

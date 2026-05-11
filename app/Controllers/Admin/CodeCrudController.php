<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodePortefeuilleModel;

class CodeCrudController extends BaseController
{
    protected CodePortefeuilleModel $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePortefeuilleModel();
    }

    public function index()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');

        return view('admin/codes/index', [
            'title' => 'Validation des Codes',
            'codes' => $this->codeModel->orderBy('date_creation', 'DESC')->findAll(),
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function create()
    {
        if (!session()->get('admin_logged_in')) return redirect()->to('/admin/login');
        
        return view('admin/codes/form', [
            'title' => 'Générer des Codes',
            'admin_name' => session()->get('admin_name')
        ]);
    }

    public function store()
    {
        $montant = $this->request->getPost('montant');
        $quantite = $this->request->getPost('quantite') ?: 1;
        
        for($i=0; $i<$quantite; $i++) {
            $this->codeModel->creerCode((float)$montant);
        }

        return redirect()->to('/admin/codes')->with('success', "$quantite code(s) généré(s)");
    }

    public function delete(int $id)
    {
        $this->codeModel->delete($id);
        return redirect()->to('/admin/codes')->with('success', 'Code supprimé');
    }
}

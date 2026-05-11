<?php
// ============================================================
// app/Controllers/Admin/CrudRegimeController.php
// ============================================================
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;

class CrudRegimeController extends BaseController
{
    protected RegimeModel $regimeModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        if (! session()->get('admin_id')) {
            redirect()->to('/admin/login')->send(); exit;
        }
    }

    // ── Liste ───────────────────────────────────────────────
    public function index()
    {
        return view('pages/admin/regimes/index', [
            'title'   => 'Gestion des Régimes',
            'regimes' => $this->regimeModel->orderBy('id','DESC')->findAll(),
        ]);
    }

    // ── Formulaire création ─────────────────────────────────
    public function create()
    {
        return view('pages/admin/regimes/form', [
            'title'  => 'Nouveau Régime',
            'regime' => null,
        ]);
    }

    // ── Enregistrer ─────────────────────────────────────────
    public function store()
    {
        $data = $this->request->getPost([
            'nom','description','type_regime','intensite',
            'variation_quotidienne','prix_jour',
            'pourcentage_viande','pourcentage_poisson','pourcentage_volaille',
        ]);

        // Validation basique
        if (array_sum([$data['pourcentage_viande'],$data['pourcentage_poisson'],$data['pourcentage_volaille']]) !== 100) {
            return redirect()->back()->with('error','La somme viande+poisson+volaille doit être 100 %.')->withInput();
        }

        $data['actif'] = 1;
        $this->regimeModel->insert($data);
        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès.');
    }

    // ── Formulaire édition ──────────────────────────────────
    public function edit(int $id)
    {
        $regime = $this->regimeModel->find($id);
        if (! $regime) return redirect()->to('/admin/regimes')->with('error','Régime introuvable.');
        return view('pages/admin/regimes/form', ['title' => 'Modifier Régime', 'regime' => $regime]);
    }

    // ── Mettre à jour ───────────────────────────────────────
    public function update(int $id)
    {
        $data = $this->request->getPost([
            'nom','description','type_regime','intensite',
            'variation_quotidienne','prix_jour',
            'pourcentage_viande','pourcentage_poisson','pourcentage_volaille','actif',
        ]);
        $this->regimeModel->update($id, $data);
        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour.');
    }

    // ── Supprimer ───────────────────────────────────────────
    public function delete(int $id)
    {
        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé.');
    }
}

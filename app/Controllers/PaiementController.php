<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;

class PaiementController extends BaseController
{
    protected CodePortefeuilleModel $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePortefeuilleModel();
    }

    /**
     * PAGE 2 : CHOIX MOYEN DE PAIEMENT
     */
    public function choisir(int $codeId)
    {
        $code = $this->codeModel->find($codeId);
        if (!$code) return redirect()->to('/code/achat');

        return view('pages/portefeuille/paiement_step2', [
            'title'  => 'Choisir le paiement | Nutri Goal',
            'code'   => $code,
            'styles' => ['portefeuille.css']
        ]);
    }

    /**
     * PAGE 3 : FORMULAIRE FINAL SELON LE MOYEN
     */
    public function formulaire()
    {
        $codeId = $this->request->getPost('code_id');
        $moyen  = $this->request->getPost('moyen_paiement');

        $code = $this->codeModel->find($codeId);
        if (!$code) return redirect()->to('/code/achat');

        return view('pages/portefeuille/paiement_step3', [
            'title'  => 'Finaliser le paiement | Nutri Goal',
            'code'   => $code,
            'moyen'  => $moyen,
            'styles' => ['portefeuille.css']
        ]);
    }

    /**
     * TRAITEMENT FINAL (Simulation)
     */
    public function traiter()
    {
        $codeId = $this->request->getPost('code_id');
        $code = $this->codeModel->find($codeId);
        
        // Simulation réussie
        session()->setFlashdata('purchased_code', $code['code']);
        session()->setFlashdata('purchased_amount', $code['montant']);

        return redirect()->to('/paiement/success');
    }

    /**
     * PAGE 4 : SUCCÈS (Affichage du code à copier)
     */
    public function success()
    {
        $code = session()->getFlashdata('purchased_code');
        if (!$code) return redirect()->to('/code/achat');

        return view('pages/portefeuille/paiement_success', [
            'title'  => 'Code Prêt ! | Nutri Goal',
            'code'   => $code,
            'amount' => session()->getFlashdata('purchased_amount'),
            'styles' => ['portefeuille.css']
        ]);
    }
}

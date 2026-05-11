<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\ProfilModel;

class TransactionController extends BaseController
{
    protected TransactionModel $transactionModel;
    protected ProfilModel $profilModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->profilModel = new ProfilModel();
    }

    public function index()
    {
        $userId = session('user_id');
        if (!$userId) return redirect()->to('/login');

        $user = $this->profilModel->find($userId);
        $transactions = $this->transactionModel->obtenirHistoriqueUtilisateur($userId);

        return view('pages/portefeuille/transaction_history', [
            'title'        => 'Historique des transactions | Nutri Goal',
            'user'         => $user,
            'transactions' => $transactions,
            'styles'       => ['portefeuille.css']
        ]);
    }
}

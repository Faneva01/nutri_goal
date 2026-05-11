<?php

namespace App\Controllers;

use App\Models\ProfilModel;
use App\Models\ObjectifModel;
use App\Models\AbonnementGoldModel;
use App\Models\TransactionModel;
use App\Models\CodePortefeuilleModel;

class ProfilController extends BaseController
{
    protected ProfilModel $profilModel;
    protected ObjectifModel $objectifModel;
    protected AbonnementGoldModel $goldModel;
    protected TransactionModel $transactionModel;
    protected CodePortefeuilleModel $codeModel;

    public function __construct()
    {
        $this->profilModel = new ProfilModel();
        $this->objectifModel = new ObjectifModel();
        $this->goldModel = new AbonnementGoldModel();
        $this->transactionModel = new TransactionModel();
        $this->codeModel = new CodePortefeuilleModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user   = $userId ? $this->profilModel->find($userId) : null;

        if ($user === null) {
            return redirect()->to('/login');
        }

        // Récupérer l'objectif de l'utilisateur
        $objectif = $userId ? $this->objectifModel->getLatestByUser($userId) : null;

        // Récupérer les activités réelles
        $historiqueActivites = [];
        if ($userId) {
            $db = \Config\Database::connect();
            $historiqueActivites = $db->table('suivi_activites')
                ->where('utilisateur_id', $userId)
                ->orderBy('date_activite', 'DESC')
                ->get(5)->getResultArray();
            
            // Formatage pour la vue si nécessaire
            $historiqueActivites = array_map(function($a) {
                return [
                    'date'  => $a['date_activite'],
                    'label' => $a['nom_activite'],
                    'valeur' => $a['valeur']
                ];
            }, $historiqueActivites);
        }

        // Récupérer le prochain rappel
        $prochainRappel = 'Aucun rappel prévu';
        if ($userId) {
            $db = \Config\Database::connect();
            $rappel = $db->table('rappels')
                ->where('utilisateur_id', $userId)
                ->where('date_rappel >=', date('Y-m-d H:i:s'))
                ->orderBy('date_rappel', 'ASC')
                ->get(1)->getRowArray();
            
            if ($rappel) {
                $prochainRappel = $rappel['message'];
            }
        }

        return view('pages/profil/profil_page', [
            'title'               => 'Mon profil',
            'user'                => $user,
            'objectif'            => $objectif,
            'navView'             => 'inc/nav',
            'prochainRappel'      => $prochainRappel,
            'historiqueActivites' => $historiqueActivites,
            'styles'  => ['profil-page.css'],
            'scripts' => ['profil-page.js'],
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $data = [
            'nom_complet' => $this->request->getPost('fullname'),
            'email'       => $this->request->getPost('email'),
            'genre'       => $this->request->getPost('genre'),
            'taille'      => (int)   $this->request->getPost('taille'),
            'poids'       => (float) $this->request->getPost('poids'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $existing = $this->profilModel->where('email', $data['email'])
                                      ->where('id !=', $userId)
                                      ->first();
        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cet email est déjà utilisé par un autre compte.',
            ]);
        }

        if (!$this->profilModel->update($userId, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(' ', $this->profilModel->errors()),
            ]);
        }

        $updated = $this->profilModel->find($userId);

        session()->set([
            'nom_complet' => $updated['nom_complet'],
            'email'       => $updated['email'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'imc'     => $updated['imc'],
        ]);
    }

    public function toggleGold()
    {
        $userId = session()->get('user_id');
        $prixGold = 50000; // Prix fixe pour l'option Gold

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $user = $this->profilModel->find($userId);
        
        // Si déjà Gold, on ne fait rien (ou on pourrait gérer un désabonnement, mais le sujet dit "payer en une fois")
        if ($user['option_gold']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Vous êtes déjà membre Gold.']);
        }

        if ($user['solde'] < $prixGold) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Solde insuffisant pour passer en mode Gold (Requis: ' . number_format($prixGold, 0) . ' Ar).'
            ]);
        }

        // Transaction
        $this->profilModel->db->transStart();

        // 1. Déduire le solde
        $this->profilModel->update($userId, ['solde' => $user['solde'] - $prixGold]);

        // 2. Activer Gold
        $this->goldModel->activateGold($userId, $prixGold);

        // 3. Créer historique transaction
        $this->transactionModel->insert([
            'utilisateur_id' => $userId,
            'type_transaction' => 'achat_gold',
            'montant' => $prixGold,
            'ancien_solde' => $user['solde'],
            'nouveau_solde' => $user['solde'] - $prixGold,
            'description' => "Achat Option Gold — 15% de remise à vie",
            'date_transaction' => date('Y-m-d H:i:s')
        ]);

        $this->profilModel->db->transComplete();

        if ($this->profilModel->db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Une erreur est survenue lors de l\'achat.']);
        }

        session()->set('solde', $user['solde'] - $prixGold);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Félicitations ! Vous êtes maintenant membre Gold.',
            'nouveau_solde' => number_format($user['solde'] - $prixGold, 0) . ' Ar'
        ]);
    }

    public function rechargerSolde()
    {
        $userId = session()->get('user_id');

        if ($userId === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non connecté.']);
        }

        $code = strtoupper(trim($this->request->getPost('code')));

        if (!$this->codeModel->utiliserCode($code, $userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide, déjà utilisé ou expiré.',
            ]);
        }

        $user = $this->profilModel->find($userId);

        // Mettre à jour le solde en session.
        session()->set('solde', $user['solde']);

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Votre compte a été crédité avec succès.',
            'nouveau_solde' => number_format((float) $user['solde'], 0) . ' Ar',
        ]);
    }
}
<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaiementController extends BaseController
{
    protected $codePortefeuilleModel;

    public function __construct()
    {
        $this->codePortefeuilleModel = new CodePortefeuilleModel();
    }

    /**
     * Affiche la page de choix du moyen de paiement
     */
    public function index()
    {
        return view('pages/choix_paiement', [
            'title' => 'Choisir un Moyen de Paiement',
            'styles' => ['style.css']
        ]);
    }

    /**
     * Traite le choix du moyen de paiement et affiche le formulaire approprié
     */
    public function choisir($codeId = null)
    {
        // Si la requête est GET, afficher le formulaire de sélection de moyen de paiement
        if ($this->request->getMethod() === 'get') {
            $codeData = null;

            if ($codeId) {
                $codeData = $this->codePortefeuilleModel->find($codeId);
                if (!$codeData) {
                    return redirect()->to('/code/achat')->with('error', 'Code introuvable');
                }
            }

            return view('pages/choix_paiement', [
                'title' => 'Choisir un Moyen de Paiement',
                'code_data' => $codeData,
                'styles' => ['style.css'],
            ]);
        }

        $moyenPaiement = $this->request->getPost('moyen_paiement');

        if (!$moyenPaiement) {
            return redirect()->back()->with('error', 'Veuillez choisir un moyen de paiement');
        }

        // Validation du moyen de paiement
        $moyensValides = ['mvola', 'airtel_money', 'orange_money', 'carte_bancaire'];
        if (!in_array($moyenPaiement, $moyensValides)) {
            return redirect()->back()->with('error', 'Moyen de paiement invalide');
        }

        $codeData = null;
        $codeId = $this->request->getPost('code_id') ?? $codeId;
        if ($codeId) {
            $codeData = $this->codePortefeuilleModel->find($codeId);
            if (!$codeData) {
                return redirect()->to('/code/achat')->with('error', 'Code introuvable');
            }
        }

        return view('pages/formulaire_paiement', [
            'title' => 'Paiement - ' . $this->getNomMoyenPaiement($moyenPaiement),
            'moyen_paiement' => $moyenPaiement,
            'nom_moyen' => $this->getNomMoyenPaiement($moyenPaiement),
            'code_data' => $codeData,
            'styles' => ['style.css']
        ]);
    }

    /**
     * Traite le paiement selon le moyen choisi
     */
    public function traiter($moyenPaiement)
    {
        // Validation du moyen de paiement
        $moyensValides = ['mvola', 'airtel_money', 'orange_money', 'carte_bancaire'];
        if (!in_array($moyenPaiement, $moyensValides)) {
            return redirect()->to('/paiement')->with('error', 'Moyen de paiement invalide');
        }

        $codeId = $this->request->getPost('code_id');

        // Validation selon le moyen de paiement
        $validationRules = $this->getValidationRules($moyenPaiement);

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simuler le traitement du paiement (en production, intégrer les APIs réelles)
        $resultatPaiement = $this->traiterPaiement($moyenPaiement, $this->request->getPost());

        if ($resultatPaiement['success']) {
            // Marquer le code comme payé et générer le code final
            if ($codeId) {
                $codeData = $this->codePortefeuilleModel->find($codeId);
                // Ici, on pourrait mettre à jour un statut de paiement si nécessaire
            }

            return redirect()->to('/paiement/success')->with('success', 'Paiement effectué avec succès ! Votre code : ' . $resultatPaiement['code']);
        }

        return redirect()->back()->with('error', $resultatPaiement['message']);
    }

    /**
     * Page de succès de paiement
     */
    public function success()
    {
        $successMessage = session()->getFlashdata('success');

        return view('pages/paiement_success', [
            'title' => 'Paiement Réussi',
            'message' => $successMessage,
            'styles' => ['style.css']
        ]);
    }

    /**
     * API endpoint pour vérifier le statut d'un paiement (AJAX)
     */
    public function verifierStatut($transactionId)
    {
        // Simuler la vérification du statut (en production, interroger l'API du fournisseur)
        $statut = $this->verifierStatutPaiement($transactionId);

        return $this->response->setJSON([
            'transaction_id' => $transactionId,
            'statut' => $statut,
            'message' => $this->getMessageStatut($statut)
        ]);
    }

    /**
     * Traite le paiement selon le moyen choisi (simulation)
     */
    private function traiterPaiement($moyenPaiement, $donnees)
    {
        // Simulation du traitement - En production, intégrer les APIs des opérateurs

        switch ($moyenPaiement) {
            case 'mvola':
                return $this->traiterMvola($donnees);
            case 'airtel_money':
                return $this->traiterAirtelMoney($donnees);
            case 'orange_money':
                return $this->traiterOrangeMoney($donnees);
            case 'carte_bancaire':
                return $this->traiterCarteBancaire($donnees);
            default:
                return ['success' => false, 'message' => 'Moyen de paiement non supporté'];
        }
    }

    private function traiterMvola($donnees)
    {
        // Simulation MVola - numéro et montant requis
        $numero = $donnees['numero_mvola'];
        $montant = $donnees['montant'];

        // Simuler un appel API MVola
        // En production: intégrer l'API MVola

        return [
            'success' => true,
            'code' => $this->genererCodeTransaction(),
            'transaction_id' => 'MVOLA_' . time(),
            'message' => 'Paiement MVola traité avec succès'
        ];
    }

    private function traiterAirtelMoney($donnees)
    {
        // Simulation Airtel Money
        $numero = $donnees['numero_airtel'];
        $montant = $donnees['montant'];

        return [
            'success' => true,
            'code' => $this->genererCodeTransaction(),
            'transaction_id' => 'AIRTEL_' . time(),
            'message' => 'Paiement Airtel Money traité avec succès'
        ];
    }

    private function traiterOrangeMoney($donnees)
    {
        // Simulation Orange Money
        $numero = $donnees['numero_orange'];
        $montant = $donnees['montant'];

        return [
            'success' => true,
            'code' => $this->genererCodeTransaction(),
            'transaction_id' => 'ORANGE_' . time(),
            'message' => 'Paiement Orange Money traité avec succès'
        ];
    }

    private function traiterCarteBancaire($donnees)
    {
        // Simulation Carte Bancaire
        $numeroCarte = $donnees['numero_carte'];
        $dateExpiration = $donnees['date_expiration'];
        $cvv = $donnees['cvv'];
        $montant = $donnees['montant'];

        return [
            'success' => true,
            'code' => $this->genererCodeTransaction(),
            'transaction_id' => 'CARTE_' . time(),
            'message' => 'Paiement par carte traité avec succès'
        ];
    }

    private function verifierStatutPaiement($transactionId)
    {
        // Simulation de vérification de statut
        // En production: interroger l'API du fournisseur de paiement

        // Simuler différents statuts possibles
        $statuts = ['pending', 'completed', 'failed', 'cancelled'];
        return $statuts[array_rand($statuts)];
    }

    private function getMessageStatut($statut)
    {
        $messages = [
            'pending' => 'Paiement en cours de traitement',
            'completed' => 'Paiement effectué avec succès',
            'failed' => 'Échec du paiement',
            'cancelled' => 'Paiement annulé'
        ];

        return $messages[$statut] ?? 'Statut inconnu';
    }

    private function getValidationRules($moyenPaiement)
    {
        $rulesCommunes = [
            'montant' => 'required|numeric|greater_than[0]',
        ];

        switch ($moyenPaiement) {
            case 'mvola':
                return $rulesCommunes + [
                    'numero_mvola' => 'required|regex_match[/^[0-9]{10}$/]',
                ];
            case 'airtel_money':
                return $rulesCommunes + [
                    'numero_airtel' => 'required|regex_match[/^[0-9]{10}$/]',
                ];
            case 'orange_money':
                return $rulesCommunes + [
                    'numero_orange' => 'required|regex_match[/^[0-9]{10}$/]',
                ];
            case 'carte_bancaire':
                return $rulesCommunes + [
                    'numero_carte' => 'required|regex_match[/^[0-9]{13,19}$/]',
                    'date_expiration' => 'required|regex_match[/^(0[1-9]|1[0-2])\/([0-9]{2})$/]',
                    'cvv' => 'required|regex_match[/^[0-9]{3,4}$/]',
                ];
            default:
                return $rulesCommunes;
        }
    }

    private function getNomMoyenPaiement($moyenPaiement)
    {
        $noms = [
            'mvola' => 'MVola',
            'airtel_money' => 'Airtel Money',
            'orange_money' => 'Orange Money',
            'carte_bancaire' => 'Carte Bancaire'
        ];

        return $noms[$moyenPaiement] ?? $moyenPaiement;
    }

    private function genererCodeTransaction()
    {
        return 'TXN' . strtoupper(bin2hex(random_bytes(8)));
    }
}
<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class TransactionModel extends Model
{
    // FIX: la table dans sql-final.sql s'appelle 'historique_transactions',
    //      pas 'transactions'. On aligne le modèle sur le schéma SQL réel.
    protected $table      = 'historique_transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    // FIX: allowedFields alignés sur les colonnes réelles de historique_transactions
    protected $allowedFields = [
        'utilisateur_id',
        'type_transaction',   // ENUM: ajout_code | achat_regime | achat_gold | remboursement
        'montant',
        'ancien_solde',
        'nouveau_solde',
        'description',
        'date_transaction',
    ];

    protected $useTimestamps = false;

    public function __construct(ConnectionInterface $db = null)
    {
        parent::__construct($db);
    }

    /**
     * Crée une transaction d'achat de code (type = achat_regime côté enum,
     * description précise le contexte).
     *
     * FIX: on supprime les champs inexistants (transaction_id, code_id,
     *      moyen_paiement, statut, type, reference_externe, etc.) et on
     *      utilise uniquement les colonnes réelles du schéma.
     *
     * @return int|null  L'ID inséré, ou null en cas d'échec
     */
    public function creerTransactionAchat(
        float $montant,
        string $moyenPaiement,
        ?int $codeId       = null,
        ?int $utilisateurId = null
    ): ?int {
        // Récupérer l'ancien solde si on a un utilisateur
        $ancienSolde = null;
        if ($utilisateurId) {
            $user = $this->db->table('utilisateurs')
                ->select('solde')
                ->where('id', $utilisateurId)
                ->get()->getRowArray();
            $ancienSolde = $user['solde'] ?? null;
        }

        $description = "Achat code portefeuille via {$moyenPaiement}"
            . ($codeId ? " (code_id: {$codeId})" : '')
            . " — {$montant} Ar";

        $data = [
            'utilisateur_id'  => $utilisateurId,
            'type_transaction' => 'achat_regime', // type le plus proche dans l'ENUM existant
            'montant'          => $montant,
            'ancien_solde'     => $ancienSolde,
            'nouveau_solde'    => $ancienSolde,   // l'achat ne crédite pas encore le solde
            'description'      => $description,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];

        $inserted = $this->insert($data);
        return $inserted ? $this->getInsertID() : null;
    }

    /**
     * Crée une transaction d'utilisation de code (crédit du solde)
     */
    public function creerTransactionUtilisation(
        int $utilisateurId,
        int $codeId,
        float $montant
    ): ?int {
        // Récupérer le solde APRÈS la mise à jour (déjà effectuée par utiliserCode())
        $user = $this->db->table('utilisateurs')
            ->select('solde')
            ->where('id', $utilisateurId)
            ->get()->getRowArray();

        $nouveauSolde = $user['solde'] ?? null;
        $ancienSolde  = $nouveauSolde !== null ? ($nouveauSolde - $montant) : null;

        $data = [
            'utilisateur_id'   => $utilisateurId,
            'type_transaction' => 'ajout_code',
            'montant'          => $montant,
            'ancien_solde'     => $ancienSolde,
            'nouveau_solde'    => $nouveauSolde,
            'description'      => "Utilisation code portefeuille (code_id: {$codeId}) — +{$montant} Ar",
            'date_transaction' => date('Y-m-d H:i:s'),
        ];

        $inserted = $this->insert($data);
        return $inserted ? $this->getInsertID() : null;
    }

    /**
     * Récupère l'historique des transactions d'un utilisateur
     */
    public function obtenirHistoriqueUtilisateur(int $utilisateurId, int $limite = 50): array
    {
        return $this->where('utilisateur_id', $utilisateurId)
            ->orderBy('date_transaction', 'DESC')
            ->limit($limite)
            ->findAll();
    }

    /**
     * Calcule le total des transactions d'un type donné sur une période
     */
    public function calculerTotalPeriode(
        string $dateDebut,
        string $dateFin,
        string $type = 'ajout_code'
    ): float {
        $result = $this->selectSum('montant')
            ->where('type_transaction', $type)
            ->where('date_transaction >=', $dateDebut)
            ->where('date_transaction <=', $dateFin)
            ->first();

        return (float) ($result['montant'] ?? 0);
    }

    /**
     * Statistiques par type de transaction
     */
    public function obtenirStatistiques(): array
    {
        $total = $this->db->table($this->table)
            ->selectSum('montant')
            ->get()->getRowArray();

        $parType = $this->db->table($this->table)
            ->select('type_transaction, SUM(montant) as total, COUNT(*) as nombre')
            ->groupBy('type_transaction')
            ->get()->getResultArray();

        return [
            'total'    => (float) ($total['montant'] ?? 0),
            'par_type' => $parType,
        ];
    }
}
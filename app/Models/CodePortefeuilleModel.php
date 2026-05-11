<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class CodePortefeuilleModel extends Model
{
    protected $table      = 'codes_solde';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'code',
        'montant',
        'utilisateur_id',
        'est_utilise',        // FIX: manquait dans allowedFields
        'date_creation',
        'date_utilisation',
        'date_expiration',    // FIX: manquait dans allowedFields
    ];

    protected $useTimestamps = false;

    public function __construct(ConnectionInterface $db = null)
    {
        parent::__construct($db);
    }

    /**
     * Crée un nouveau code portefeuille
     */
    public function creerCode(float $montant, ?string $code = null, ?string $dateExpiration = null): int|false
    {
        if (empty($code)) {
            $code = $this->generateUniqueCode();
        }

        $data = [
            'code'            => $code,
            'montant'         => $montant,
            'est_utilise'     => 0,
            'date_creation'   => date('Y-m-d H:i:s'),
            'date_expiration' => $dateExpiration,
        ];

        return $this->insert($data) ? $this->getInsertID() : false;
    }

    /**
     * Trouve un code par sa valeur
     */
    public function trouverParCode(string $code): ?array
    {
        return $this->where('code', $code)->first() ?: null;
    }

    /**
     * Vérifie si un code existe déjà
     */
    public function codeExiste(string $code): bool
    {
        return $this->where('code', $code)->countAllResults() > 0;
    }

    /**
     * Vérifie si un code est valide (non utilisé, non expiré)
     */
    public function estValide(string $code): bool
    {
        $result = $this->trouverParCode($code);

        if (empty($result) || (int) $result['est_utilise'] === 1) {
            return false;
        }

        if (!empty($result['date_expiration']) && $result['date_expiration'] <= date('Y-m-d H:i:s')) {
            return false;
        }

        return true;
    }

    /**
     * Utilise un code et crédite le solde de l'utilisateur
     *
     * FIX: suppression du bloc de code mort qui apparaissait après le return
     * (les deux lignes orphelines $this->db->transComplete() / return transStatus()
     *  ont été retirées ; le transComplete() et le transStatus() sont déjà
     *  appelés dans le bloc try/finally ci-dessous).
     */
    public function utiliserCode(string $code, int $utilisateurId): bool
    {
        $this->db->transStart();

        try {
            // Verrouillage en lecture pour éviter la double-utilisation
            $builder  = $this->builder();
            $codeData = $builder->where('code', $code)->get()->getRowArray();

            if (empty($codeData) || (int) $codeData['est_utilise'] === 1) {
                $this->db->transRollback();
                return false;
            }

            if (!empty($codeData['date_expiration']) && $codeData['date_expiration'] <= date('Y-m-d H:i:s')) {
                $this->db->transRollback();
                return false;
            }

            // Ajouter le solde à l'utilisateur
            $this->db->table('utilisateurs')
                ->set('solde', 'solde + ' . (float) $codeData['montant'], false)
                ->where('id', $utilisateurId)
                ->update();

            // Marquer le code comme utilisé
            $this->update($codeData['id'], [
                'utilisateur_id'   => $utilisateurId,
                'est_utilise'      => 1,
                'date_utilisation' => date('Y-m-d H:i:s'),
            ]);

            // Créer une transaction de suivi
            $transactionModel = new TransactionModel();
            $transactionModel->creerTransactionUtilisation(
                $utilisateurId,
                $codeData['id'],
                (float) $codeData['montant']
            );

            $this->db->transComplete();
            return $this->db->transStatus() === true;

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', '[CodePortefeuilleModel::utiliserCode] ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne les codes utilisés par un utilisateur
     */
    public function getCodesUtilisateurs(int $utilisateurId): array
    {
        return $this->where('utilisateur_id', $utilisateurId)
            ->orderBy('date_utilisation', 'DESC')
            ->findAll();
    }

    /**
     * Retourne tous les codes disponibles (non utilisés, non expirés)
     */
    public function getCodesDisponibles(): array
    {
        return $this->where('est_utilise', 0)
            ->groupStart()
                ->where('date_expiration', null)
                ->orWhere('date_expiration >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->orderBy('date_creation', 'DESC')
            ->findAll();
    }

    /**
     * Génère un code unique de la forme CODE + hex aléatoire
     */
    protected function generateUniqueCode(int $length = 12): string
    {
        do {
            $raw  = strtoupper(bin2hex(random_bytes((int) ceil($length / 2))));
            $code = 'CODE' . substr($raw, 0, $length);
        } while ($this->codeExiste($code));

        return $code;
    }
}
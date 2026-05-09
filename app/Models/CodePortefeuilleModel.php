<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class CodePortefeuilleModel extends Model
{
    protected $table = 'codes_solde';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'code',
        'montant',
        'utilisateur_id',
        'est_utilise',
        'date_creation',
        'date_utilisation',
        'date_expiration',
    ];

    protected $useTimestamps = false;

    public function __construct(ConnectionInterface $db = null)
    {
        parent::__construct($db);
    }

    public function creerCode(float $montant, ?string $code = null, ?string $dateExpiration = null)
    {
        if (empty($code)) {
            $code = $this->generateUniqueCode();
        }

        $data = [
            'code' => $code,
            'montant' => $montant,
            'est_utilise' => 0,
            'date_expiration' => $dateExpiration,
        ];

        return $this->insert($data);
    }

    public function trouverParCode(string $code): ?array
    {
        return $this->where('code', $code)->first() ?: null;
    }

    public function codeExiste(string $code): bool
    {
        return $this->where('code', $code)->countAllResults() > 0;
    }

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

    public function utiliserCode(string $code, int $utilisateurId): bool
    {
        $this->db->transStart();

        $codeData = $this->forUpdate()->where('code', $code)->first();

        if (empty($codeData) || (int) $codeData['est_utilise'] === 1) {
            $this->db->transComplete();
            return false;
        }

        if (!empty($codeData['date_expiration']) && $codeData['date_expiration'] <= date('Y-m-d H:i:s')) {
            $this->db->transComplete();
            return false;
        }

        $utilisateurTable = $this->db->table('utilisateurs');
        $utilisateurTable->set('solde', 'solde + ' . (float) $codeData['montant'], false)
            ->where('id', $utilisateurId)
            ->update();

        $this->update($codeData['id'], [
            'utilisateur_id' => $utilisateurId,
            'est_utilise' => 1,
            'date_utilisation' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function getCodesUtilisateurs(int $utilisateurId): array
    {
        return $this->where('utilisateur_id', $utilisateurId)
            ->orderBy('date_utilisation', 'DESC')
            ->findAll();
    }

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

    protected function generateUniqueCode(int $length = 12): string
    {
        do {
            $code = 'CODE' . strtoupper(bin2hex(random_bytes(max(4, (int) ceil($length / 2)))));
            $code = substr($code, 0, $length + 4);
        } while ($this->codeExiste($code));

        return $code;
    }
}

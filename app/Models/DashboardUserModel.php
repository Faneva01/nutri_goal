<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardUserModel extends Model
{
    protected $table      = 'utilisateurs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom_complet',
        'email',
        'poids',
        'taille',
        'imc',
        'solde',
    ];

    public function getDashboardData(?int $userId): array
    {
        try {
            $user           = $this->resolveUser($userId);
            $resolvedUserId = (int) ($user['id'] ?? 0);
            $imc            = (float) ($user['imc'] ?? 0);

            return [
                'user'          => $user,
                'stats'         => $this->getStats($resolvedUserId, $imc),
                'regimes'       => $this->getRegimesProgress($resolvedUserId),
                'historique'    => $this->getTransactionHistory($resolvedUserId),
                'weightSeries'  => $this->getDailyWeightSeries($resolvedUserId, (float) ($user['poids'] ?? 0)),
                'currentRegime' => $this->getCurrentRegime($resolvedUserId),
                'caloriesSeries' => $this->buildCaloriesSeries(),
                'db_down'       => false,
            ];
        } catch (\Throwable $e) {
            // $fallbackUser = [
            //     'id'         => 0,
            //     'nom_complet' => 'Utilisateur',
            //     'email'      => '',
            //     'poids'      => 70,
            //     'taille'     => 170,
            //     'imc'        => 24.22,
            //     'solde'      => 0,
            // ];

            // return [
            //     'user'          => $fallbackUser,
            //     'stats'         => [
            //         'objectif_journalier_kcal' => 2200,
            //         'kcal_consommees'          => 0,
            //         'activites_semaine'        => 0,
            //         'eau_litres'               => 0,
            //         'objectif_type'            => null,
            //         'objectif_cible'           => null,
            //         'imc_status'               => $this->imcStatus((float) $fallbackUser['imc']),
            //     ],
            //     'regimes'       => [],
            //     'historique'    => [],
            //     'weightSeries'  => $this->getDailyWeightSeries(0, (float) $fallbackUser['poids']),
            //     'currentRegime' => ['nom' => 'Regime actuel', 'proteines' => 35, 'glucides' => 40, 'lipides' => 25],
            //     'caloriesSeries' => $this->buildCaloriesSeries(),
            //     'db_down'       => true,
            // ];
        }
    }

    private function imcStatus(float $imc): string
    {
        if ($imc < 18.5) return 'Insuffisance pondérale';
        if ($imc < 25)   return 'Corpulence normale';
        if ($imc < 30)   return 'Surpoids';
        return 'Obésité';
    }

    private function resolveUser(?int $userId): array
    {
        $user = null;
        if ($userId !== null) $user = $this->find($userId);
        if ($user === null)   $user = $this->orderBy('id', 'ASC')->first();

        if ($user === null) {
            return ['id' => 0, 'nom_complet' => 'Utilisateur', 'email' => '', 'poids' => 70, 'taille' => 170, 'imc' => 24.22, 'solde' => 0];
        }

        return $user;
    }

    private function getStats(int $userId, float $imc): array
    {
        $objectifType  = null;
        $objectifCible = null;

        if ($this->safeTableExists('objectifs') && $userId > 0) {
            $objectif = $this->db->table('objectifs')
                ->select('type_objectif, poids_cible')
                ->where('utilisateur_id', $userId)
                ->orderBy('date_creation', 'DESC')
                ->get(1)->getRowArray();

            if ($objectif) {
                $objectifType  = $objectif['type_objectif'] ?? null;
                $objectifCible = isset($objectif['poids_cible']) ? (float) $objectif['poids_cible'] : null;
            }
        }

        $activitesSemaine = 0;
        if ($this->safeTableExists('regime_activite') && $userId > 0) {
            $sub = $this->db->table('abonnements_regimes')
                ->select('regime_id')
                ->where('utilisateur_id', $userId)
                ->where('statut', 'actif')
                ->get()->getResultArray();

            $regimeIds = array_column($sub, 'regime_id');

            if (!empty($regimeIds)) {
                $row = $this->db->table('regime_activite')
                    ->selectSum('frequence_par_semaine')
                    ->whereIn('regime_id', $regimeIds)
                    ->get()->getRowArray();

                $activitesSemaine = (int) ($row['frequence_par_semaine'] ?? 0);
            }
        }

        return [
            'objectif_journalier_kcal' => $this->mapGoalToKcal($objectifType),
            'kcal_consommees'          => 0,
            'activites_semaine'        => $activitesSemaine,
            'eau_litres'               => 0,
            'objectif_type'            => $objectifType,
            'objectif_cible'           => $objectifCible,
            'imc_status'               => $this->imcStatus($imc),
        ];
    }

    private function getRegimesProgress(int $userId): array
    {
        if (!$this->safeTableExists('abonnements_regimes') || !$this->safeTableExists('regimes') || $userId <= 0) {
            return [];
        }

        $rows = $this->db->table('abonnements_regimes ar')
            ->select('r.nom, r.variation_quotidienne, ar.date_debut, ar.date_fin, ar.statut')
            ->join('regimes r', 'r.id = ar.regime_id')
            ->where('ar.utilisateur_id', $userId)
            ->orderBy('ar.date_debut', 'DESC')
            ->get()->getResultArray();

        $today  = time();
        $result = [];

        foreach ($rows as $row) {
            $start    = strtotime((string) ($row['date_debut'] ?? ''));
            $end      = strtotime((string) ($row['date_fin'] ?? ''));
            $progress = 0;

            if ($start !== false && $end !== false && $end > $start) {
                $elapsed  = max(0, min($today, $end) - $start);
                $progress = (int) round(($elapsed / ($end - $start)) * 100);
            }
            if (($row['statut'] ?? '') === 'termine') $progress = 100;

            $result[] = [
                'nom'        => $row['nom'] ?? 'Regime',
                'progression' => max(0, min(100, $progress)),
                'statut'     => $row['statut'] ?? 'actif',
                'periode'    => $this->formatPeriod((string) ($row['date_debut'] ?? ''), (string) ($row['date_fin'] ?? '')),
                'variation'  => (float) ($row['variation_quotidienne'] ?? 0),
            ];
        }

        return $result;
    }

    private function getTransactionHistory(int $userId): array
    {
        if (!$this->safeTableExists('historique_transactions') || $userId <= 0) return [];

        $rows = $this->db->table('historique_transactions')
            ->select('date_transaction, type_transaction, montant, description')
            ->where('utilisateur_id', $userId)
            ->orderBy('date_transaction', 'DESC')
            ->get(8)->getResultArray();

        return array_map(function ($row) {
            $dateRaw = (string) ($row['date_transaction'] ?? '');
            return [
                'date'   => $dateRaw !== '' ? date('d/m/Y', strtotime($dateRaw)) : '-',
                'label'  => (string) ($row['description'] ?: $row['type_transaction'] ?? 'Transaction'),
                'detail' => number_format((float) ($row['montant'] ?? 0), 2) . ' Ar',
            ];
        }, $rows);
    }

    private function getDailyWeightSeries(int $userId, float $fallbackWeight): array
    {
        $series = [];
        for ($i = 6; $i >= 0; $i--) {
            $series[] = [
                'day'    => date('d/m', strtotime("-{$i} day")),
                'weight' => $fallbackWeight,
            ];
        }
        return $series;
    }

    private function mapGoalToKcal(?string $goalType): int
    {
        return match ($goalType) {
            'prise'    => 2600,
            'perte'    => 1800,
            'imc_ideal' => 2200,
            default    => 2200,
        };
    }

    private function safeTableExists(string $table): bool
    {
        try { return $this->db->tableExists($table); } catch (\Throwable $e) { return false; }
    }

    private function getCurrentRegime(int $userId): array
    {
        $default = ['nom' => 'Aucun régime actif', 'proteines' => 35, 'glucides' => 40, 'lipides' => 25];

        if (!$this->safeTableExists('abonnements_regimes') || !$this->safeTableExists('regimes') || $userId <= 0) {
            return $default;
        }

        $row = $this->db->table('abonnements_regimes ar')
            ->select('r.nom, r.pourcentage_viande, r.pourcentage_poisson, r.pourcentage_volaille')
            ->join('regimes r', 'r.id = ar.regime_id')
            ->where('ar.utilisateur_id', $userId)
            ->where('ar.statut', 'actif')
            ->orderBy('ar.date_debut', 'DESC')
            ->get(1)->getRowArray();

        if (!$row) return $default;

        $proteines = (int) ($row['pourcentage_viande']   ?? 0);
        $glucides  = (int) ($row['pourcentage_volaille'] ?? 0);
        $lipides   = (int) ($row['pourcentage_poisson']  ?? 0);
        $total     = max(1, $proteines + $glucides + $lipides);

        return [
            'nom'       => $row['nom'] ?? 'Régime actuel',
            'proteines' => (int) round(($proteines / $total) * 100),
            'glucides'  => (int) round(($glucides  / $total) * 100),
            'lipides'   => (int) round(($lipides   / $total) * 100),
        ];
    }

    private function buildCaloriesSeries(): array
    {
        return [
            ['month' => 'Oct', 'value' => 2100],
            ['month' => 'Nov', 'value' => 1950],
            ['month' => 'Déc', 'value' => 2050],
            ['month' => 'Jan', 'value' => 1820],
            ['month' => 'Fév', 'value' => 1760],
            ['month' => 'Mar', 'value' => 1710],
            ['month' => 'Avr', 'value' => 1650],
            ['month' => 'Mai', 'value' => 1600],
        ];
    }

    private function formatPeriod(string $start, string $end): string
    {
        $s = strtotime($start);
        $e = strtotime($end);
        if ($s === false || $e === false) return '-';
        $m = ['Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        return $m[(int) date('n', $s) - 1] . ' – ' . $m[(int) date('n', $e) - 1] . ' ' . date('Y', $e);
    }
}
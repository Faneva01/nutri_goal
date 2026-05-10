<?php

namespace App\Services;

use App\Models\RegimeModel;
use App\Models\UserModel;
use Config\Database;

class RegimeService {
    protected RegimeModel $regimeModel;
    protected UserModel $userModel;

    const GOLD_DISCOUNT = 0.15;

    public function __construct() {
        $this->regimeModel = new RegimeModel();
        $this->userModel = new UserModel();
    }

    /**
     * Déterminer type de régime selon variation
     */
    public function determineType(float $variation): string {
        if ($variation > 0) {
            return 'perte';
        }

        if ($variation < 0) {
            return 'prise';
        }

        return 'maintien';
    }

    /**
     * Calcul durée du régime
     */
    public function calculateDuration(float $variation, float $variationQuotidienne): int {
        if ($variationQuotidienne <= 0) {
            return 0;
        }

        return (int) ceil(abs($variation) / $variationQuotidienne);
    }

    /**
     * Calcul prix total
     */
    public function calculatePrice(float $prixJour, int $duree): float {
        return $prixJour * $duree;
    }

    /**
     * Vérifier si utilisateur gold actif
     */
    public function isGold(int $userId): bool {
        $db = Database::connect();

        $gold = $db->table('abonnements_gold')
            ->where('utilisateur_id', $userId)
            ->where('actif', 1)
            ->get()
            ->getRowArray();

        return !empty($gold);
    }

    /**
     * Appliquer remise gold
     */
    public function applyGoldDiscount(float $price): float {
        return $price * (1 - self::GOLD_DISCOUNT);
    }

    /**
     * Choisir régime adapté
     */
    public function getBestRegime(string $type): ?array {
        $regimes = $this->regimeModel
            ->where('type_regime', $type)
            ->where('actif', 1)
            ->findAll();

        if (empty($regimes)) {
            return null;
        }

        // priorité intensité modérée
        foreach ($regimes as $regime) {
            if ($regime['intensite'] === 'moderee') {
                return $regime;
            }
        }

        return $regimes[0];
    }

    /**
     *  RECOMMANDATION
     */
    public function recommend(array $user, array $objectif): array {
        $variation = $user['poids'] - $objectif['poids_cible'];

        $type = $this->determineType($variation);

        $regime = $this->getBestRegime($type);

        if (!$regime) {
            return [
                'success' => false,
                'message' => 'Aucun régime disponible'
            ];
        }

        $duree = $this->calculateDuration(
            $variation,
            $regime['variation_quotidienne']
        );

        $prix = $this->calculatePrice(
            $regime['prix_jour'],
            $duree
        );

        $gold = $this->isGold($user['id']);

        if ($gold) {
            $prix = $this->applyGoldDiscount($prix);
        }

        return [
            'success' => true,
            'type_regime' => $type,
            'variation' => $variation,
            'regime' => $regime,
            'duree' => $duree,
            'prix_total' => round($prix, 2),
            'gold' => $gold
        ];
    }
}
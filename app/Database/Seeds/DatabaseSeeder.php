<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. UTILISATEURS
        $this->db->table('utilisateurs')->emptyTable('utilisateurs');
        $users = [
            [
                'nom_complet' => 'Dupont Alice',
                'email' => 'alice.dupont@mail.com',
                'mot_de_passe' => password_hash('password', PASSWORD_BCRYPT),
                'genre' => 'F',
                'taille' => 165,
                'poids' => 72.5,
                'option_gold' => 0,
                'solde' => 50000,
            ],
            [
                'nom_complet' => 'Martin Jean',
                'email' => 'jean.martin@mail.com',
                'mot_de_passe' => password_hash('password', PASSWORD_BCRYPT),
                'genre' => 'M',
                'taille' => 180,
                'poids' => 85.0,
                'option_gold' => 1,
                'solde' => 100000,
            ],
        ];
        $this->db->table('utilisateurs')->insertBatch($users);

        // 2. RÉGIMES
        $this->db->table('regimes')->emptyTable('regimes');
        $regimes = [
            [
                'nom' => 'Régime Perte Intense',
                'description' => 'Un programme strict pour une perte rapide.',
                'type_regime' => 'perte',
                'intensite' => 'intense',
                'variation_quotidienne' => 0.5,
                'prix_jour' => 2000,
                'pourcentage_viande' => 40,
                'pourcentage_poisson' => 30,
                'pourcentage_volaille' => 30,
                'actif' => 1
            ],
            [
                'nom' => 'Régime Maintien Modéré',
                'description' => 'Pour stabiliser votre poids sainement.',
                'type_regime' => 'maintien',
                'intensite' => 'moderee',
                'variation_quotidienne' => 0.0,
                'prix_jour' => 1000,
                'pourcentage_viande' => 33,
                'pourcentage_poisson' => 33,
                'pourcentage_volaille' => 34,
                'actif' => 1
            ]
        ];
        $this->db->table('regimes')->insertBatch($regimes);

        // 3. ACTIVITÉS SPORTIVES
        $this->db->table('activites_sportives')->emptyTable('activites_sportives');
        $activities = [
            ['nom' => 'Marche rapide', 'description' => '30 min de marche intense', 'duree_minutes' => 30, 'intensite' => 'moderee', 'calories_brulees' => 150, 'actif' => 1],
            ['nom' => 'Course à pied', 'description' => 'Circuit de 5km', 'duree_minutes' => 45, 'intensite' => 'intense', 'calories_brulees' => 400, 'actif' => 1],
        ];
        $this->db->table('activites_sportives')->insertBatch($activities);
    }
}
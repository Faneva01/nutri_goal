<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed utilisateurs
        $data = [
            [
                'nom_complet' => 'Dupont Alice',
                'email' => 'alice.dupont@mail.com',
                'mot_de_passe' => password_hash('password', PASSWORD_BCRYPT),
                'genre' => 'F',
                'taille' => 165,
                'poids' => 72.5,
                'option_gold' => false,
                'solde' => 50.00,
            ],
            [
                'nom_complet' => 'Martin Jean',
                'email' => 'jean.martin@mail.com',
                'mot_de_passe' => password_hash('password', PASSWORD_BCRYPT),
                'genre' => 'M',
                'taille' => 180,
                'poids' => 85.0,
                'option_gold' => true,
                'solde' => 100.00,
            ],
            // Add other users here...
        ];

        $this->db->table('utilisateurs')->insertBatch($data);

        // Additional seed data (regimes, activites_sportives, etc.) would follow here...
    }
}
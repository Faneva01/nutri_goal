<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        // Table utilisateurs
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom_complet' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
            ],
            'mot_de_passe' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'genre' => [
                'type' => 'ENUM',
                'constraint' => ['M', 'F', 'Autre'],
            ],
            'taille' => [
                'type' => 'INT',
                'comment' => 'Taille en cm',
            ],
            'poids' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'comment' => 'Poids en kg',
            ],
            'imc' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Indice de masse corporelle',
            ],
            'option_gold' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'solde' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'date_inscription' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'date_modification' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('utilisateurs');

        // Additional tables (objectifs, regimes, etc.) would follow here...
    }

    public function down()
    {
        $this->forge->dropTable('utilisateurs');

        // Drop additional tables here...
    }
}
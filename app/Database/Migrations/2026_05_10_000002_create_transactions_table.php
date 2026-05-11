<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'utilisateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Utilisateur effectuant la transaction (null avant paiement)',
            ],
            'code_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Code de solde associé',
            ],
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'comment' => 'Montant de la transaction en Ar',
            ],
            'moyen_paiement' => [
                'type' => 'ENUM',
                'constraint' => ['mvola', 'airtel_money', 'orange_money', 'carte_bancaire', 'manuel'],
                'default' => 'manuel',
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['en_attente', 'en_cours', 'completee', 'echouee', 'annulee'],
                'default' => 'en_attente',
                'comment' => 'Statut de la transaction',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['achat_code', 'utilisation_code', 'recharge', 'depense', 'remboursement'],
                'default' => 'achat_code',
                'comment' => 'Type de transaction',
            ],
            'reference_externe' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
                'comment' => 'Référence du fournisseur de paiement (MVola, Orange, etc.)',
            ],
            'numero_telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Numéro de téléphone utilisé pour le paiement',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes_interne' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Notes internes pour les administrateurs',
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Données supplémentaires (réponse API, etc.)',
            ],
            'date_creation' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'date_completion' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Date de complétion de la transaction',
            ],
            'date_modification' => [
                'type' => 'DATETIME',
                'null' => true,
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addKey('transaction_id', false, false, 'UNIQUE');
        $this->forge->addKey('utilisateur_id');
        $this->forge->addKey('code_id');
        $this->forge->addKey('statut');
        $this->forge->addKey('type');
        $this->forge->addKey('moyen_paiement');
        $this->forge->addKey('date_creation');

        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}

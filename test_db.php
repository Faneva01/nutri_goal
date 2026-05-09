<?php

// Test simple de connexion MySQL
$host = '127.0.0.1';
$port = 3306;
$dbname = 'nutri_goal';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "✅ Connexion MySQL réussie!\n";
    echo "Base de données: $dbname\n";
    echo "Hôte: $host:$port\n";

    // Tester une requête simple
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "\nTables trouvées: " . count($tables) . "\n";
    if (count($tables) > 0) {
        echo "Liste des tables:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    } else {
        echo "⚠️  Aucune table trouvée. La base de données existe mais est vide.\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de connexion MySQL:\n";
    echo $e->getMessage() . "\n";

    // Suggestions de dépannage
    echo "\n🔧 Suggestions:\n";
    echo "1. Vérifiez que XAMPP est démarré (Apache + MySQL)\n";
    echo "2. Créez la base de données 'nutri_goal' dans phpMyAdmin\n";
    echo "3. Importez le schéma depuis database/09052026-02-schema.sql\n";
    echo "4. Si vous utilisez XAMPP sur Windows, essayez 'localhost' au lieu de '127.0.0.1'\n";
    echo "5. Vérifiez le mot de passe MySQL (par défaut vide dans XAMPP)\n";
}
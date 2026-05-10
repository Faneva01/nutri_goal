-- CODES SOLDE
CREATE TABLE codes_solde (
  id INT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(20) UNIQUE NOT NULL,
  montant DECIMAL(10,2) NOT NULL,

  utilisateur_id INT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_utilisation TIMESTAMP NULL,

  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- HISTORIQUE TRANSACTIONS
CREATE TABLE historique_transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,

  type_transaction ENUM('ajout_code', 'achat_regime', 'achat_gold', 'remboursement') NOT NULL,
  montant DECIMAL(10,2) NOT NULL,

  ancien_solde DECIMAL(10,2),
  nouveau_solde DECIMAL(10,2),

  description VARCHAR(255),
  date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

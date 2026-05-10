USE nutri_goal;
-- GOLD 
CREATE TABLE abonnements_gold (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  prix_paye DECIMAL(10,2) NOT NULL,
  remise_percent INT DEFAULT 15,
  actif BOOLEAN DEFAULT TRUE,
  date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

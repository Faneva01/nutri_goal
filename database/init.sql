CREATE DATABASE nutri_goal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nutri_goal;

-- Table utilisateurs
CREATE TABLE utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom_complet VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  genre ENUM('M', 'F', 'Autre') NOT NULL,
  taille INT NOT NULL COMMENT 'Taille en cm',
  poids DECIMAL(5, 2) NOT NULL COMMENT 'Poids en kg',
  imc DECIMAL(5, 2) COMMENT 'Indice de masse corporelle',
  option_gold BOOLEAN DEFAULT FALSE,
  solde DECIMAL(10, 2) DEFAULT 0,
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table objectifs utilisateur
CREATE TABLE objectifs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  type_objectif ENUM('augmenter_poids', 'reduire_poids', 'imc_ideal') NOT NULL,
  poids_cible DECIMAL(5, 2),
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table régimes
CREATE TABLE regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  pourcentage_viande INT NOT NULL COMMENT '% de viande',
  pourcentage_poisson INT NOT NULL COMMENT '% de poisson',
  pourcentage_volaille INT NOT NULL COMMENT '% de volaille',
  variation_poids DECIMAL(5, 2) COMMENT 'Variation de poids possible',
  actif BOOLEAN DEFAULT TRUE,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table prix régimes (prix selon la durée)
CREATE TABLE prix_regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  regime_id INT NOT NULL,
  duree_jours INT NOT NULL COMMENT 'Durée en jours',
  prix DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
  UNIQUE KEY unique_regime_duree (regime_id, duree_jours)
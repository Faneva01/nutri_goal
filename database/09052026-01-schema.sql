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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table activités sportives
CREATE TABLE activites_sportives (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  duree_minutes INT COMMENT 'Durée recommandée en minutes',
  intensite ENUM('faible', 'modere', 'intense') DEFAULT 'modere',
  calories_brulees INT COMMENT 'Calories brûlées approximativement',
  actif BOOLEAN DEFAULT TRUE,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table abonnements régimes utilisateur
CREATE TABLE abonnements_regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  regime_id INT NOT NULL,
  prix_pagine DECIMAL(10, 2),
  duree_jours INT,
  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,
  statut ENUM('actif', 'termine', 'annule') DEFAULT 'actif',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table suggestions d'activités pour l'utilisateur
CREATE TABLE suggestions_activites (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  activite_id INT NOT NULL,
  regime_id INT NOT NULL,
  frequence_par_semaine INT COMMENT 'Nombre de fois par semaine recommandé',
  date_suggestion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE CASCADE,
  FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table codes solde
CREATE TABLE codes_solde (
  id INT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(20) UNIQUE NOT NULL,
  montant DECIMAL(10, 2) NOT NULL,
  utilisateur_id INT COMMENT 'NULL si non utilisé',
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_utilisation TIMESTAMP NULL,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table historique transactions solde
CREATE TABLE historique_transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  type_transaction ENUM('ajout_code', 'achat_regime', 'remboursement') NOT NULL,
  montant DECIMAL(10, 2) NOT NULL,
  ancien_solde DECIMAL(10, 2),
  nouveau_solde DECIMAL(10, 2),
  description VARCHAR(255),
  date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table exportations PDF
CREATE TABLE exportations_pdf (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  nom_fichier VARCHAR(255),
  date_exportation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
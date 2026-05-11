CREATE DATABASE nutri_goal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nutri_goal;

-- UTILISATEURS
CREATE TABLE utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom_complet VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  genre ENUM('M', 'F', 'Autre') NOT NULL,
  taille INT NOT NULL COMMENT 'cm',
  poids DECIMAL(5,2) NOT NULL COMMENT 'kg',
  imc DECIMAL(5,2),
  solde DECIMAL(10,2) DEFAULT 0,

  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- REGIMES
CREATE TABLE regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,

  type_regime ENUM('perte', 'prise', 'maintien') NOT NULL,
  intensite ENUM('legere', 'moderee', 'intense') DEFAULT 'moderee',

  variation_quotidienne DECIMAL(4,2) NOT NULL COMMENT 'kg par jour',
  prix_jour DECIMAL(10,2) NOT NULL COMMENT 'prix par jour',

  pourcentage_viande INT NOT NULL,
  pourcentage_poisson INT NOT NULL,
  pourcentage_volaille INT NOT NULL,

  actif BOOLEAN DEFAULT TRUE,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ACTIVITES SPORTIVES
CREATE TABLE activites_sportives (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  duree_minutes INT NOT NULL,
  intensite ENUM('faible', 'modere', 'intense') DEFAULT 'modere',
  calories_brulees INT,
  actif BOOLEAN DEFAULT TRUE,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- LIEN REGIME / ACTIVITE
CREATE TABLE regime_activite (
  id INT PRIMARY KEY AUTO_INCREMENT,
  regime_id INT NOT NULL,
  activite_id INT NOT NULL,
  frequence_par_semaine INT DEFAULT 3,

  FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE,
  FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OBJECTIFS UTILISATEUR
CREATE TABLE objectifs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,

  type_objectif ENUM('perte', 'prise', 'imc_ideal') NOT NULL,
  poids_cible DECIMAL(5,2),

  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ABONNEMENT REGIME (ACHAT)
CREATE TABLE abonnements_regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  regime_id INT NOT NULL,

  poids_initial DECIMAL(5,2),
  poids_cible DECIMAL(5,2),

  duree_estimee_jours INT,

  date_debut DATE NOT NULL,
  date_fin DATE NOT NULL,

  prix_total DECIMAL(10,2),

  statut ENUM('actif', 'termine', 'annule') DEFAULT 'actif',

  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: codes_solde

CREATE TABLE codes_solde (
  id INT PRIMARY KEY AUTO_INCREMENT,

  code VARCHAR(20) UNIQUE NOT NULL,

  montant DECIMAL(10,2) NOT NULL,

  utilisateur_id INT NULL,
  est_utilise TINYINT DEFAULT 0,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_utilisation TIMESTAMP NULL DEFAULT NULL,

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: historique_transactions

CREATE TABLE historique_transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,

  utilisateur_id INT NOT NULL,

  type_transaction ENUM(
    'ajout_code',
    'achat_regime',
    'achat_gold',
    'remboursement'
  ) NOT NULL,

  montant DECIMAL(10,2) NOT NULL,

  ancien_solde DECIMAL(10,2),
  nouveau_solde DECIMAL(10,2),

  description VARCHAR(255),

  date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: administrateurs

CREATE TABLE administrateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,

    nom_complet VARCHAR(100) NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,

    mot_de_passe VARCHAR(255) NOT NULL,

    role ENUM('super_admin', 'admin')
        DEFAULT 'admin',

    actif BOOLEAN DEFAULT TRUE,

    derniere_connexion TIMESTAMP NULL DEFAULT NULL,

    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    date_modification TIMESTAMP NULL DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

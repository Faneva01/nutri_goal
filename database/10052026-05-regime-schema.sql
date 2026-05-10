USE nutri_goal;

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
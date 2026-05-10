-- ============================================================================
-- NUTRI GOAL - DATABASE TEST COMPLETE (v10052026-07)
-- ============================================================================

USE nutri_goal;

-- ============================================================================
-- SUPPRESSION DES TABLES (ORDRE IMPORTANT)
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS historique_transactions;
DROP TABLE IF EXISTS codes_solde;
DROP TABLE IF EXISTS abonnements_regimes;
DROP TABLE IF EXISTS objectifs;
DROP TABLE IF EXISTS regime_activite;
DROP TABLE IF EXISTS abonnements_gold;
DROP TABLE IF EXISTS activites_sportives;
DROP TABLE IF EXISTS regimes;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS administrateurs;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- TABLE: utilisateurs
-- ============================================================================

CREATE TABLE utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom_complet VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  genre ENUM('M', 'F', 'Autre') NOT NULL,
  taille INT NOT NULL,
  poids DECIMAL(5,2) NOT NULL,
  imc DECIMAL(5,2),
  solde DECIMAL(10,2) DEFAULT 0,
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: abonnements_gold
-- ============================================================================

CREATE TABLE abonnements_gold (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT NOT NULL,
  prix_paye DECIMAL(10,2) NOT NULL,
  remise_percent INT DEFAULT 15,
  actif BOOLEAN DEFAULT TRUE,
  date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: regimes
-- ============================================================================

CREATE TABLE regimes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,

  type_regime ENUM('perte', 'prise', 'maintien') NOT NULL,

  intensite ENUM('legere', 'moderee', 'intense')
    DEFAULT 'moderee',

  variation_quotidienne DECIMAL(4,2) NOT NULL,
  prix_jour DECIMAL(10,2) NOT NULL,

  pourcentage_viande INT NOT NULL,
  pourcentage_poisson INT NOT NULL,
  pourcentage_volaille INT NOT NULL,

  actif BOOLEAN DEFAULT TRUE,

  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP NULL DEFAULT NULL
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: activites_sportives
-- ============================================================================

CREATE TABLE activites_sportives (
  id INT PRIMARY KEY AUTO_INCREMENT,

  nom VARCHAR(100) NOT NULL,
  description TEXT,

  duree_minutes INT NOT NULL,

  intensite ENUM('faible', 'modere', 'intense')
    DEFAULT 'modere',

  calories_brulees INT,

  actif BOOLEAN DEFAULT TRUE,

  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: regime_activite
-- ============================================================================

CREATE TABLE regime_activite (
  id INT PRIMARY KEY AUTO_INCREMENT,

  regime_id INT NOT NULL,
  activite_id INT NOT NULL,

  frequence_par_semaine INT DEFAULT 3,

  FOREIGN KEY (regime_id)
    REFERENCES regimes(id)
    ON DELETE CASCADE,

  FOREIGN KEY (activite_id)
    REFERENCES activites_sportives(id)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: objectifs
-- ============================================================================

CREATE TABLE objectifs (
  id INT PRIMARY KEY AUTO_INCREMENT,

  utilisateur_id INT NOT NULL,

  type_objectif ENUM(
    'perte',
    'prise',
    'maintien',
    'imc_ideal'
  ) NOT NULL,

  poids_cible DECIMAL(5,2),

  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: abonnements_regimes
-- ============================================================================

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

  statut ENUM('actif', 'termine', 'annule')
    DEFAULT 'actif',

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE CASCADE,

  FOREIGN KEY (regime_id)
    REFERENCES regimes(id)
    ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: codes_solde
-- ============================================================================

CREATE TABLE codes_solde (
  id INT PRIMARY KEY AUTO_INCREMENT,

  code VARCHAR(20) UNIQUE NOT NULL,

  montant DECIMAL(10,2) NOT NULL,

  utilisateur_id INT NULL,

  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_utilisation TIMESTAMP NULL DEFAULT NULL,

  FOREIGN KEY (utilisateur_id)
    REFERENCES utilisateurs(id)
    ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: historique_transactions
-- ============================================================================

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


-- ============================================================================
-- TABLE: administrateurs
-- ============================================================================

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

-- ============================================================================
-- DONNÉES TEST - UTILISATEURS
-- ============================================================================

INSERT INTO utilisateurs
(nom_complet, email, mot_de_passe, genre, taille, poids, imc, solde)
VALUES
('Alice Dupont', 'alice.dupont@mail.com', 'password', 'F', 165, 72.5, 26.6, 150.00),
('Jean Martin', 'jean.martin@mail.com', 'password', 'M', 180, 85.0, 26.2, 250.00),
('Marie Bernard', 'marie.bernard@mail.com', 'password', 'F', 170, 68.0, 23.5, 100.00),
('Pierre Thomas', 'pierre.thomas@mail.com', 'password', 'M', 175, 92.5, 30.1, 75.00),
('Sophie Leclerc', 'sophie.leclerc@mail.com', 'password', 'F', 162, 65.0, 24.8, 320.00),
('Luc Rousseau', 'luc.rousseau@mail.com', 'password', 'M', 188, 95.0, 26.9, 200.00),
('Emma Gauthier', 'emma.gauthier@mail.com', 'password', 'F', 158, 62.0, 24.9, 180.00),
('Marc Fournier', 'marc.fournier@mail.com', 'password', 'M', 182, 88.0, 26.6, 120.00),
('Isabelle Moreau', 'isabelle.moreau@mail.com', 'password', 'F', 167, 70.5, 25.3, 90.00),
('Laurent Mercier', 'laurent.mercier@mail.com', 'password', 'M', 179, 82.0, 25.6, 280.00);

-- ============================================================================
-- DONNÉES TEST - OBJECTIFS
-- ============================================================================

INSERT INTO objectifs
(utilisateur_id, type_objectif, poids_cible)
VALUES
(1, 'perte', 65.0),
(2, 'maintien', 85.0),
(3, 'perte', 62.0),
(4, 'prise', 95.0),
(5, 'imc_ideal', 60.0),
(6, 'maintien', 95.0),
(7, 'perte', 58.0),
(8, 'maintien', 88.0),
(9, 'perte', 64.0),
(10, 'maintien', 82.0);

-- ============================================================================
-- DONNÉES TEST - RÉGIMES
-- ============================================================================

INSERT INTO regimes
(
  nom,
  description,
  type_regime,
  intensite,
  variation_quotidienne,
  prix_jour,
  pourcentage_viande,
  pourcentage_poisson,
  pourcentage_volaille
)
VALUES
('Régime Keto', 'Faible glucide', 'perte', 'intense', 0.5, 15.99, 40, 35, 25),
('Régime Protéiné', 'Musculation', 'prise', 'intense', 0.8, 18.99, 45, 30, 25),
('Régime Méditerranéen', 'Équilibré', 'maintien', 'moderee', 0.1, 16.99, 20, 50, 30),
('Régime Vegan', 'Sans viande', 'maintien', 'legere', 0.05, 14.99, 0, 0, 100),
('Régime Équilibré', 'Classique', 'maintien', 'moderee', 0.15, 12.99, 30, 25, 45),
('Régime Paleo', 'Naturel', 'perte', 'moderee', 0.3, 17.99, 50, 25, 25),
('Régime Jeûne Intermittent', 'Jeûne', 'perte', 'moderee', 0.4, 10.99, 30, 30, 40);

-- ============================================================================
-- DONNÉES TEST - ACTIVITÉS SPORTIVES
-- ============================================================================

INSERT INTO activites_sportives
(
  nom,
  description,
  duree_minutes,
  intensite,
  calories_brulees
)
VALUES
('Course à Pied', 'Endurance', 30, 'modere', 300),
('Musculation', 'Poids', 45, 'intense', 350),
('Natation', 'Cardio', 45, 'modere', 400),
('Yoga', 'Relaxation', 60, 'faible', 150),
('Vélo Intérieur', 'Cyclisme', 45, 'intense', 500),
('Marche Active', 'Marche rapide', 45, 'modere', 250),
('Crossfit', 'Haute intensité', 60, 'intense', 600),
('Pilates', 'Renforcement', 50, 'modere', 200),
('Danse', 'Cardio danse', 45, 'modere', 350),
('HIIT', 'Intervalles', 30, 'intense', 450);

-- ============================================================================
-- DONNÉES TEST - LIENS RÉGIME / ACTIVITÉ
-- ============================================================================

INSERT INTO regime_activite
(regime_id, activite_id, frequence_par_semaine)
VALUES
(1,1,3),
(1,5,4),
(1,10,3),
(2,2,5),
(2,9,3),
(3,3,3),
(3,6,3),
(4,8,3),
(4,4,4),
(5,6,3),
(5,8,3),
(6,1,4),
(6,2,4),
(7,10,5);

-- ============================================================================
-- DONNÉES TEST - ABONNEMENTS GOLD
-- ============================================================================

INSERT INTO abonnements_gold
(utilisateur_id, prix_paye, remise_percent, actif)
VALUES
(2,84.99,15,TRUE),
(5,84.99,15,TRUE),
(6,84.99,15,TRUE),
(8,84.99,15,TRUE),
(10,84.99,15,TRUE);

-- ============================================================================
-- DONNÉES TEST - ABONNEMENTS RÉGIMES
-- ============================================================================

INSERT INTO abonnements_regimes
(
  utilisateur_id,
  regime_id,
  poids_initial,
  poids_cible,
  duree_estimee_jours,
  date_debut,
  date_fin,
  prix_total,
  statut
)
VALUES
(1,1,72.5,65.0,30,'2026-04-10','2026-05-10',479.70,'actif'),
(2,2,85.0,95.0,60,'2026-03-20','2026-05-19',1139.40,'actif'),
(3,3,68.0,62.0,45,'2026-04-01','2026-05-16',764.55,'actif'),
(4,5,92.5,92.5,30,'2026-04-15','2026-05-15',389.70,'termine'),
(5,4,65.0,60.0,30,'2026-03-01','2026-04-01',449.70,'termine'),
(6,6,95.0,85.0,45,'2026-04-05','2026-05-20',809.55,'actif'),
(7,1,62.0,58.0,30,'2026-04-20','2026-05-20',479.70,'actif'),
(8,3,88.0,88.0,30,'2026-04-12','2026-05-12',509.70,'actif'),
(9,5,70.5,64.0,45,'2026-03-15','2026-04-29',584.55,'termine'),
(10,2,82.0,90.0,60,'2026-04-01','2026-05-31',1139.40,'actif');

-- ============================================================================
-- DONNÉES TEST - ADMINISTRATEURS
-- ============================================================================

INSERT INTO administrateurs
(
    nom_complet,
    email,
    mot_de_passe,
    role,
    actif
)
VALUES
(
    'Super Administrateur',
    'admin@nutri-goal.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'super_admin',
    TRUE
);

INSERT INTO administrateurs
(
    nom_complet,
    email,
    mot_de_passe,
    role,
    actif
)
VALUES
(
    'Admin',
    'admin@gmail.com',
    'admin',
    'super_admin',
    TRUE
);
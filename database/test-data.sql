-- ── REFERENCE DATA FOR NUTRI GOAL ─────────────────────────

USE nutri_goal;

-- ── ADMINISTRATEURS ──
INSERT INTO administrateurs (nom_complet, email, mot_de_passe, role) VALUES
('Admin NutriGoal', 'admin@nutrigoal.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'super_admin');
-- MDP: password123

-- ── UTILISATEURS (Template minimal - 5 utilisateurs requis) ──
-- Tous les utilisateurs ci-dessous ont le mot de passe : password123
INSERT INTO utilisateurs (nom_complet, email, mot_de_passe, genre, taille, poids, solde) VALUES
('Jean Dupont', 'jean.dupont@mail.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'M', 180, 85, 0), -- MDP: password123
('Marie Laurent', 'marie.laurent@mail.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'F', 165, 60, 0), -- MDP: password123
('Pierre Martin', 'pierre.martin@mail.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'M', 175, 95, 0), -- MDP: password123
('Sophie Bernard', 'sophie.bernard@mail.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'F', 160, 52, 0), -- MDP: password123
('Lucas Petit', 'lucas.petit@mail.com', '$2y$10$jLnxdIIPydArmKFiJF3ZIuQYiQSEYH69Wg3FUo01DSFkpWR58Dj32', 'M', 170, 70, 0);   -- MDP: password123

-- ── RÉGIMES (5 régimes requis) ──
INSERT INTO regimes (nom, description, type_regime, intensite, variation_quotidienne, prix_jour, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
('Régime Méditerranéen', 'Équilibré et riche en oméga-3, parfait pour le maintien.', 'maintien', 'legere', 0.05, 12000, 20, 50, 30),
('Céto Express', 'Faible en glucides pour une perte de poids rapide.', 'perte', 'intense', 0.25, 18000, 60, 20, 20),
('Prise de Masse Pro', 'Excédent calorique contrôlé pour le gain de muscle.', 'prise', 'moderee', 0.15, 15000, 40, 20, 40),
('Végétarien Sportif', 'Riche en protéines végétales et légumineuses.', 'maintien', 'moderee', 0.02, 10000, 0, 40, 60),
('Détox Printemps', 'Léger et purifiant pour relancer le métabolisme.', 'perte', 'legere', 0.10, 9000, 10, 60, 30);

-- ── ACTIVITÉS SPORTIVES (5 activités requises) ──
INSERT INTO activites_sportives (nom, description, duree_minutes, intensite, calories_brulees) VALUES
('Marche Rapide', 'Une marche à vive allure en extérieur.', 30, 'faible', 150),
('Course à pied', 'Running à allure modérée (10km/h).', 45, 'intense', 450),
('Natation', 'Longueurs en piscine, nage libre.', 60, 'modere', 500),
('Yoga', 'Séance de Hatha Yoga pour la souplesse.', 60, 'faible', 180),
('Musculation', 'Entraînement de force avec poids.', 60, 'intense', 400);

-- ── CODES PORTEFEUILLE (15 codes requis) ──
INSERT INTO codes_solde (code, montant) VALUES
('GOLD50K', 50000.00), ('TEST10K', 10000.00), ('PROMO20K', 20000.00), ('POWER100K', 100000.00),
('FREE5K', 5000.00), ('GIFT15K', 15000.00), ('BONUS25K', 25000.00), ('CASH10K', 10000.00),
('WIN10K', 10000.00), ('LUCKY50K', 50000.00), ('REF80K', 80000.00), ('VIP200K', 200000.00),
('PRO25K', 25000.00), ('TOP10K', 10000.00), ('MAX50K', 50000.00);

-- ── RÉGIME ↔ ACTIVITÉ ──
INSERT INTO regime_activite (regime_id, activite_id, frequence_par_semaine) VALUES
(1, 1, 5), (1, 4, 2),
(2, 2, 4), (2, 5, 3),
(3, 5, 5), (3, 3, 2),
(4, 3, 3), (4, 4, 4),
(5, 1, 7), (5, 4, 3);

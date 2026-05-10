-- Insertion de 5 utilisateurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, genre, taille, poids, option_gold, solde) VALUES
('Dupont', 'Alice', 'alice.dupont@mail.com', '$2y$10$KIXwichNV7LvzVTzVVWKVuFJFqFMhfXO0gZPqO9wLAK5pF8yZEjYa', 'F', 165, 72.5, FALSE, 50.00),
('Martin', 'Jean', 'jean.martin@mail.com', '$2y$10$KIXwichNV7LvzVTzVVWKVuFJFqFMhfXO0gZPqO9wLAK5pF8yZEjYa', 'M', 180, 85.0, TRUE, 100.00),
('Bernard', 'Marie', 'marie.bernard@mail.com', '$2y$10$KIXwichNV7LvzVTzVVWKVuFJFqFMhfXO0gZPqO9wLAK5pF8yZEjYa', 'F', 170, 68.0, FALSE, 25.50),
('Thomas', 'Pierre', 'pierre.thomas@mail.com', '$2y$10$KIXwichNV7LvzVTzVVWKVuFJFqFMhfXO0gZPqO9wLAK5pF8yZEjYa', 'M', 175, 92.5, FALSE, 75.00),
('Leclerc', 'Sophie', 'sophie.leclerc@mail.com', '$2y$10$KIXwichNV7LvzVTzVVWKVuFJFqFMhfXO0gZPqO9wLAK5pF8yZEjYa', 'F', 162, 65.0, TRUE, 150.00);

-- Insertion de 5 régimes
INSERT INTO regimes (nom, description, pourcentage_viande, pourcentage_poisson, pourcentage_volaille, variation_poids) VALUES
('Régime Protéiné', 'Riche en protéines pour la musculation', 40, 30, 30, 2.5),
('Régime Méditerranéen', 'Équilibré avec beaucoup de poisson', 20, 50, 30, 1.5),
('Régime Cétogène', 'Faible en glucides, riche en matières grasses', 35, 35, 30, 3.0),
('Régime Végétal', 'Sans viande mais avec poisson et volaille', 0, 40, 60, 1.0),
('Régime Équilibré', 'Nutrition complète et variée', 30, 25, 45, 1.2);

-- Insertion de prix pour les régimes (différentes durées)
INSERT INTO prix_regimes (regime_id, duree_jours, prix) VALUES
(1, 7, 49.99),
(1, 30, 149.99),
(1, 90, 399.99),
(2, 7, 39.99),
(2, 30, 119.99),
(2, 90, 299.99),
(3, 7, 59.99),
(3, 30, 179.99),
(3, 90, 449.99),
(4, 7, 34.99),
(4, 30, 99.99),
(4, 90, 249.99),
(5, 7, 44.99),
(5, 30, 134.99),
(5, 90, 359.99);
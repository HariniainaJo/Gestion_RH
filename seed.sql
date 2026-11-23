--mdp admin=admin123
--mdp employe=employe123

INSERT INTO utilisateur (username, mot_de_passe, actif, id_role, date_creation) VALUES
('admin', '$2y$13$H9XDfDQDrFMSjy1JY0gwJuycyJtAsfHCBWd6mo76.1UADY9WL7J8i', TRUE, (SELECT id_role FROM role WHERE nom_role='ADMIN'), NOW()),
('employe1', '$2y$13$lXnExJLKRVEAGCXGDQm0POLEne3/kwB4hvmRvENnyQ0GZGQozqbsW', TRUE, (SELECT id_role FROM role WHERE nom_role='EMPLOYE'), NOW());

-- =====================
-- Direction
-- =====================
INSERT IGNORE INTO direction (nom_direction, description)
VALUES ('Direction Generale', 'Direction principale de l\'entreprise');

-- =====================
-- Service
-- =====================
INSERT IGNORE INTO service (nom_service, id_direction)
VALUES ('Service Informatique', (SELECT id_direction FROM direction WHERE nom_direction='Direction Generale'));

-- =====================
-- Postes
-- =====================
INSERT INTO poste (libelle, description, salaire_base, id_direction)
VALUES 
('Directeur General', 'DG de l\'entreprise', 5000, (SELECT id_Direction FROM Direction WHERE nom_Direction='Direction Generale'));


INSERT INTO poste (libelle, description, salaire_base, id_service)
VALUES 
('Developpeur', 'Developpeur Symfony/Fullstack', 1500, (SELECT id_service FROM service WHERE nom_service='Service Informatique'));
INSERT INTO employe (nom, prenom, email, contact, date_embauche, statut, id_poste, id_user)
VALUES
('Rakoto', 'Jean', 'admin@zanova.com', '0341234567', '2024-01-01', 'Actif', (SELECT id_poste FROM poste WHERE libelle='Directeur General'), (SELECT id_user FROM utilisateur WHERE username='admin')),
('Rabe', 'Mina', 'mina@zanova.com', '0349876543', '2025-01-01', 'Actif', (SELECT id_poste FROM poste WHERE libelle='Developpeur'), (SELECT id_user FROM utilisateur WHERE username='employe1'));

-- Données de démonstration pour les captures d'écran du mémoire (remplace les QR codes placeholders)
-- Seules les tables vides sont peuplées ; aucune donnée existante n'est modifiée.

INSERT INTO direction (nom_direction, description) VALUES
('Direction Générale', 'Pilotage stratégique de ZANOVA'),
('Direction Technique', 'Développement et exploitation des solutions logicielles'),
('Direction RH & Finance', 'Gestion du personnel, paie et comptabilité');

INSERT INTO service (nom_service, id_direction) VALUES
('Pôle Développement', 2),
('Ressources Humaines', 3),
('Comptabilité & Paie', 3);

INSERT INTO poste (libelle, description, salaire_base, id_service, id_direction) VALUES
('Chargé RH', 'Gestion administrative du personnel', 1200.00, 2, NULL),
('Comptable', 'Suivi comptable et paie', 1300.00, 3, NULL),
('Chef de Projet', 'Pilotage des projets techniques', 1800.00, 1, NULL);

INSERT INTO employe (nom, prenom, email, contact, date_embauche, statut, id_poste, id_user) VALUES
('Razafy', 'Soa', 'razafy.soa@zanova.com', '0340011223', '2025-03-01', 'Actif', 3, NULL),
('Andry', 'Tojo', 'andry.tojo@zanova.com', '0330022334', '2025-06-15', 'Actif', 4, NULL),
('Fanja', 'Hery', 'fanja.hery@zanova.com', '0320033445', '2024-09-01', 'Actif', 5, NULL);

INSERT INTO groupe (nom_groupe, type_groupe, date_debut, date_fin) VALUES
('Équipe Produit GRH', 'PROJET', '2026-01-01', '2026-12-31'),
('Comité RH', 'EQUIPE', '2025-01-01', NULL);

INSERT INTO groupe_employe (id_groupe, id_employe) VALUES
(1, 2), (1, 5), (2, 3), (2, 4);

INSERT INTO stagiaire (nom, prenom, email, contact, etablissement, niveau) VALUES
('Randria', 'Fy', 'randria.fy@gmail.com', '0338889900', 'IT University', 'Licence 3'),
('Soa', 'Niaina', 'soa.niaina@gmail.com', '0337776655', 'Université d''Antananarivo', 'Master 1');

INSERT INTO stage (date_debut, date_fin, type_stage, sujet, id_stagiaire, id_encadreur) VALUES
('2026-01-05', '2026-04-05', 'Stage académique', 'Développement d''une application de gestion des ressources humaines', 1, 2),
('2026-02-01', '2026-05-01', 'Stage professionnel', 'Mise en place du suivi de performance des équipes', 2, 5);

INSERT INTO conge (date_debut, date_fin, type_conge, motif, statut, date_demande, date_validation, id_employe, valide_par) VALUES
('2026-11-20', '2026-11-25', 'Congé annuel', 'Congés de fin d''année', 'EN_ATTENTE', NOW(), NULL, 2, NULL),
('2026-10-01', '2026-10-03', 'Congé maladie', 'Grippe', 'APPROUVE', '2026-09-28 09:00:00', '2026-09-29 10:00:00', 3, 1),
('2026-09-10', '2026-09-12', 'Congé maternité/paternité', 'Naissance', 'REFUSE', '2026-09-05 08:30:00', '2026-09-06 11:00:00', 4, 1);

INSERT INTO recrutement (date_debut, date_fin, suggestions, statut, id_poste) VALUES
('2026-10-01', '2026-12-31', 'Privilégier les profils Symfony/PHP avec au moins 2 ans d''expérience.', 'Ouvert', 2),
('2026-08-01', '2026-09-30', 'Poste pourvu après 3 entretiens.', 'Clôturé', 3);

INSERT INTO candidat (nom, prenom, email, contact, cv_path, statut, id_recrutement) VALUES
('Rasoa', 'Lala', 'rasoa.lala@gmail.com', '0341112233', 'softeam-icons-6974cd9082bdc.jpg', 'En attente', 1),
('Andria', 'Mamy', 'andria.mamy@gmail.com', '0342223344', 'softeam-icons-6974cd9082bdc.jpg', 'Présélectionné', 1),
('Rakotondrabe', 'Hary', 'rakotondrabe.hary@gmail.com', '0343334455', 'softeam-icons-6974cd9082bdc.jpg', 'Accepté', 1),
('Harimino', 'Dera', 'harimino.dera@gmail.com', '0344445566', 'softeam-icons-6974cd9082bdc.jpg', 'Refusé', 1),
('Voahangy', 'Nirina', 'voahangy.nirina@gmail.com', '0345556677', 'softeam-icons-6974cd9082bdc.jpg', 'Embauché', 2);

INSERT INTO presence_rh (type_evenement, date_evenement, justification, justifie, id_employe) VALUES
('ABSENCE', '2026-11-27', NULL, 0, 2),
('ABSENCE', '2026-11-20', 'Certificat médical fourni', 1, 3),
('RETARD', '2026-11-28', NULL, 0, 4),
('RETARD', '2026-11-25', 'Embouteillage exceptionnel', 1, 5);

INSERT INTO kpi (nom_kpi, description, valeur_min, valeur_max) VALUES
('Ponctualité', 'Respect des horaires de travail', 0.00, 100.00),
('Atteinte des objectifs mensuels', 'Pourcentage d''objectifs individuels atteints', 0.00, 100.00);

INSERT INTO employe_kpi (mois, annee, valeur, id_employe, id_kpi) VALUES
(11, 2026, 92.50, 2, 1),
(11, 2026, 85.00, 2, 2),
(11, 2026, 97.00, 5, 1),
(11, 2026, 90.00, 5, 2);

INSERT INTO prime (nom_prime, montant_max, formule, collectif) VALUES
('Prime de performance', 150000.00, 'valeur_kpi * 1500', 0),
('Prime d''équipe trimestrielle', 300000.00, 'montant_max * taux_atteinte_equipe', 1);

INSERT INTO prime_kpi (id_prime, id_kpi) VALUES
(1, 1), (1, 2);

INSERT INTO employe_prime (mois, annee, montant_calcule, valide, id_employe, id_prime) VALUES
(11, 2026, 138750.00, 1, 2, 1),
(11, 2026, 145500.00, 0, 5, 1);

INSERT INTO prime_groupe (mois, annee, montant_total, id_groupe, id_prime) VALUES
(11, 2026, 300000.00, 1, 2);

INSERT INTO cotisation (code, nom, taux, plafond, seuil) VALUES
('CNAPS', 'Caisse Nationale de Prévoyance Sociale', 0.0100, 2000000.00, NULL),
('OSTIE', 'Organisation Sanitaire Tananarivienne Inter-Entreprises', 0.0100, NULL, NULL);

INSERT INTO irsa_bareme (salaire_min, salaire_max, taux) VALUES
(0.00, 350000.00, 0.0000),
(350000.01, 400000.00, 0.0500),
(400000.01, 500000.00, 0.1000),
(500000.01, NULL, 0.2000);

INSERT INTO paye (mois, annee, salaire_brut, retenue, total_prime, salaire_net, date_paiement, id_employe) VALUES
(11, 2026, 1500000.00, 45000.00, 138750.00, 1593750.00, '2026-11-30', 2),
(11, 2026, 1800000.00, 54000.00, 145500.00, 1891500.00, '2026-11-30', 5);

INSERT INTO avance_salaire (montant, date_demande, motif, statut, mois_deduction, annee_deduction, id_employe) VALUES
(400000.00, '2026-11-20', 'Dépense médicale imprévue', 'EN_ATTENTE', 12, 2026, 3),
(250000.00, '2026-11-10', 'Frais de rentrée scolaire', 'APPROUVE', 12, 2026, 4);

INSERT INTO axe_navette (nom_axe, point_depart, point_arrivee) VALUES
('Ivato - Analakely', 'Ivato', 'Analakely'),
('Tanjombato - Centre Ville', 'Tanjombato', 'Antananarivo Centre');

INSERT INTO navette (code_navette, capacite, id_axe) VALUES
('NAV-01', 25, 1),
('NAV-02', 18, 2);

INSERT INTO employe_navette (id_navette, id_employe) VALUES
(1, 2), (1, 3), (2, 4);

INSERT INTO employe_document (type_document, fichier, date_ajout, id_employe) VALUES
('CV', 'softeam-icons-6974cd9082bdc.jpg', NOW(), 2),
('CIN', 'softeam-icons-6974cd9082bdc.jpg', NOW(), 2),
('Contrat', 'softeam-icons-6974cd9082bdc.jpg', NOW(), 2);

/* =========================================================
   BASE GRH COMPLETE – MariaDB 10.4.32
   ========================================================= */

CREATE DATABASE IF NOT EXISTS grh_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE grh_db;

/* =======================
   ROLES & UTILISATEURS
   ======================= */
CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO role (nom_role) VALUES ('ADMIN'), ('EMPLOYE');

CREATE TABLE utilisateur (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id_role)
);

/* =======================
   STRUCTURE ORGANISATIONNELLE
   ======================= */
CREATE TABLE direction (
    id_direction INT AUTO_INCREMENT PRIMARY KEY,
    nom_direction VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE service (
    id_service INT AUTO_INCREMENT PRIMARY KEY,
    nom_service VARCHAR(100) NOT NULL,
    id_direction INT NOT NULL,
    FOREIGN KEY (id_direction) REFERENCES direction(id_direction)
);

CREATE TABLE poste (
    id_poste INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    description TEXT,
    salaire_base DECIMAL(10,2),
    id_service INT NULL,
    id_direction INT NULL,
    CHECK (id_service IS NOT NULL OR id_direction IS NOT NULL),
    FOREIGN KEY (id_service) REFERENCES service(id_service),
    FOREIGN KEY (id_direction) REFERENCES direction(id_direction)
);

/* =======================
   EMPLOYES & DOCUMENTS
   ======================= */
CREATE TABLE employe (
    id_employe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    date_naissance DATE,
    adresse TEXT,
    email VARCHAR(150) UNIQUE,
    contact VARCHAR(30),
    situation_matrimoniale VARCHAR(50),
    date_embauche DATE NOT NULL,
    statut VARCHAR(50) DEFAULT 'Actif',
    photo_profil VARCHAR(255),
    photo_cin VARCHAR(255),
    cv_fichier VARCHAR(255),
    id_poste INT NOT NULL,
    id_user INT UNIQUE,
    FOREIGN KEY (id_poste) REFERENCES poste(id_poste),
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user)
);

CREATE TABLE employe_document (
    id_document INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    type_document VARCHAR(50),
    fichier VARCHAR(255),
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

/* =======================
   STAGIAIRES & STAGES
   ======================= */
CREATE TABLE stagiaire (
    id_stagiaire INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150),
    contact VARCHAR(30),
    etablissement VARCHAR(150),
    niveau VARCHAR(50)
);

CREATE TABLE stage (
    id_stage INT AUTO_INCREMENT PRIMARY KEY,
    id_stagiaire INT NOT NULL,
    id_encadreur INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE,
    type_stage VARCHAR(50),
    sujet TEXT,
    FOREIGN KEY (id_stagiaire) REFERENCES stagiaire(id_stagiaire),
    FOREIGN KEY (id_encadreur) REFERENCES employe(id_employe)
);

/* =======================
   NAVETTES
   ======================= */
CREATE TABLE axe_navette (
    id_axe INT AUTO_INCREMENT PRIMARY KEY,
    nom_axe VARCHAR(100),
    point_depart VARCHAR(100),
    point_arrivee VARCHAR(100)
);

CREATE TABLE navette (
    id_navette INT AUTO_INCREMENT PRIMARY KEY,
    code_navette VARCHAR(50),
    capacite INT,
    id_axe INT NOT NULL,
    FOREIGN KEY (id_axe) REFERENCES axe_navette(id_axe)
);

CREATE TABLE employe_navette (
    id_employe INT,
    id_navette INT,
    date_affectation DATE DEFAULT CURRENT_DATE,
    PRIMARY KEY (id_employe, id_navette),
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe),
    FOREIGN KEY (id_navette) REFERENCES navette(id_navette)
);

/* =======================
   CONGES / ABSENCES / RETARDS
   ======================= */
CREATE TABLE conge (
    id_conge INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    type_conge VARCHAR(50),
    motif TEXT,
    statut ENUM('EN_ATTENTE','APPROUVE','REFUSE') DEFAULT 'EN_ATTENTE',
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_validation DATETIME,
    valide_par INT,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe),
    FOREIGN KEY (valide_par) REFERENCES utilisateur(id_user)
);

CREATE TABLE presence_rh (
    id_presence INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    type_evenement ENUM('RETARD','ABSENCE') NOT NULL,
    date_evenement DATE NOT NULL,
    justification TEXT,
    justifie BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

/* =======================
   PAYE
   ======================= */
CREATE TABLE paye (
    id_paye INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL,
    mois INT,
    annee INT,
    salaire_brut DECIMAL(10,2),
    retenue DECIMAL(10,2),
    total_prime DECIMAL(10,2) DEFAULT 0,
    salaire_net DECIMAL(10,2),
    date_paiement DATE,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

/* =======================
   COTISATIONS (CNAPS, OSTIE, IRSA, etc.)
   ======================= */
CREATE TABLE cotisation (
    id_cotisation INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,          -- ex: 'CNAPS', 'OSTIE', 'IRSA'
    nom VARCHAR(100) NOT NULL,                 -- libellé lisible
    taux DECIMAL(7,4) NOT NULL,                -- ex: 0.0100 pour 1%
    plafond DECIMAL(10,2) NULL,                -- ex: 20000 pour CNAPS (NULL si pas de plafond)
    seuil DECIMAL(10,2) NULL                   -- ex: 350000 pour IRSA (NULL si non utilisé)
);

-- Données initiales
INSERT INTO cotisation (code, nom, taux, plafond, seuil) VALUES
('CNAPS', 'Cotisation CNAPS (part salarié)', 0.0100, 20000, NULL),
('OSTIE', 'Assurance santé OSTIE', 0.0100, NULL, NULL),
('IRSA', 'Impôt sur le revenu salarial (IRSA)', 0.2000, NULL, 350000);

/* =======================
   BAREME IRSA (par tranches)
   ======================= */
CREATE TABLE irsa_bareme (
    id_irsa INT AUTO_INCREMENT PRIMARY KEY,
    salaire_min DECIMAL(10,2) NOT NULL,
    salaire_max DECIMAL(10,2) NULL,
    taux DECIMAL(7,4) NOT NULL
);

-- Exemple de barème (à adapter selon la loi en vigueur)
INSERT INTO irsa_bareme (salaire_min, salaire_max, taux) VALUES
(0, 350000, 0.0000),        -- 0% jusqu'à 350 000 Ar
(350001, NULL, 0.2000);     -- 20% au-delà (salaire entre 350 001 et +)

/* =======================
   KPI & PRIMES INDIVIDUELLES
   ======================= */
CREATE TABLE kpi (
    id_kpi INT AUTO_INCREMENT PRIMARY KEY,
    nom_kpi VARCHAR(100),
    description TEXT,
    valeur_min DECIMAL(5,2) DEFAULT 0,
    valeur_max DECIMAL(5,2) DEFAULT 100
);

CREATE TABLE prime (
    id_prime INT AUTO_INCREMENT PRIMARY KEY,
    nom_prime VARCHAR(100),
    montant_max DECIMAL(10,2),
    formule TEXT,
    collectif BOOLEAN DEFAULT FALSE
);

CREATE TABLE prime_kpi (
    id_prime INT,
    id_kpi INT,
    poids DECIMAL(5,2),
    PRIMARY KEY (id_prime, id_kpi),
    FOREIGN KEY (id_prime) REFERENCES prime(id_prime),
    FOREIGN KEY (id_kpi) REFERENCES kpi(id_kpi)
);

CREATE TABLE employe_kpi (
    id_employe INT,
    id_kpi INT,
    mois INT,
    annee INT,
    valeur DECIMAL(5,2),
    PRIMARY KEY (id_employe, id_kpi, mois, annee),
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe),
    FOREIGN KEY (id_kpi) REFERENCES kpi(id_kpi)
);

CREATE TABLE employe_prime (
    id_employe_prime INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT,
    id_prime INT,
    mois INT,
    annee INT,
    montant_calcule DECIMAL(10,2),
    valide BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe),
    FOREIGN KEY (id_prime) REFERENCES prime(id_prime)
);



/* =======================
   GROUPES & PRIMES COLLECTIVES
   ======================= */
CREATE TABLE groupe (
    id_groupe INT AUTO_INCREMENT PRIMARY KEY,
    nom_groupe VARCHAR(100),
    type_groupe ENUM('SERVICE','EQUIPE','PROJET'),
    date_debut DATE,
    date_fin DATE
);

CREATE TABLE groupe_employe (
    id_groupe INT,
    id_employe INT,
    coefficient DECIMAL(5,2) DEFAULT 1,
    PRIMARY KEY (id_groupe, id_employe),
    FOREIGN KEY (id_groupe) REFERENCES groupe(id_groupe),
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

CREATE TABLE prime_groupe (
    id_prime_groupe INT AUTO_INCREMENT PRIMARY KEY,
    id_groupe INT,
    id_prime INT,
    mois INT,
    annee INT,
    montant_total DECIMAL(10,2),
    FOREIGN KEY (id_groupe) REFERENCES groupe(id_groupe),
    FOREIGN KEY (id_prime) REFERENCES prime(id_prime)
);

CREATE TABLE repartition_prime_groupe (
    id_prime_groupe INT,
    id_employe INT,
    montant DECIMAL(10,2),
    PRIMARY KEY (id_prime_groupe, id_employe),
    FOREIGN KEY (id_prime_groupe) REFERENCES prime_groupe(id_prime_groupe),
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

/* =======================
   AUDIT / SUIVI DES ACTIONS
   ======================= */
CREATE TABLE audit_log (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    action VARCHAR(100),
    table_concernee VARCHAR(100),
    id_enregistrement INT,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    adresse_ip VARCHAR(50),
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user)
);


CREATE TABLE avance_salaire (
    id_avance INT AUTO_INCREMENT PRIMARY KEY,
    id_employe INT NOT NULL, montant DECIMAL(10,2) NOT NULL, 
    date_demande DATE NOT NULL, motif TEXT, 
    statut ENUM('EN_ATTENTE','APPROUVE','REFUSE') DEFAULT 'EN_ATTENTE', 
    mois_deduction INT, 
    annee_deduction INT,
    FOREIGN KEY (id_employe) REFERENCES employe(id_employe)
);

-- =========================
-- TABLE : recrutement
-- =========================
CREATE TABLE recrutement (
    id_recrutement INT AUTO_INCREMENT PRIMARY KEY,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    id_poste INT NOT NULL,
    suggestions TEXT,
    statut ENUM('Ouvert', 'Clôturé', 'Suspendu') DEFAULT 'Ouvert',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_recrutement_poste
        FOREIGN KEY (id_poste)
        REFERENCES poste(id_poste)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================
-- TABLE : candidat
-- =========================
CREATE TABLE candidat (
    id_candidat INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    date_naissance DATE,
    adresse VARCHAR(255),
    email VARCHAR(150),
    contact VARCHAR(50),
    situation_matrimoniale VARCHAR(50) NULL,
    
    cv_path VARCHAR(255) NOT NULL, -- chemin du CV (pdf/image)
    
    id_recrutement INT NOT NULL,
    statut VARCHAR(50) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_candidat_recrutement
        FOREIGN KEY (id_recrutement)
        REFERENCES recrutement(id_recrutement)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
--CREATE TABLE messenger_messages (
--  id BINARY(16) NOT NULL,
--  body LONGTEXT NOT NULL,
--  headers LONGTEXT NOT NULL,
--  queue_name VARCHAR(190) NOT NULL,
--  created_at DATETIME NOT NULL,
--  available_at DATETIME NOT NULL,
--  delivered_at DATETIME DEFAULT NULL,
--  PRIMARY KEY(id)
--) ENGINE=InnoDB;

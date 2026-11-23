<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261119052522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_log (id_log INT AUTO_INCREMENT NOT NULL, action VARCHAR(100) DEFAULT NULL, table_concernee VARCHAR(100) DEFAULT NULL, id_enregistrement INT DEFAULT NULL, date_action DATETIME DEFAULT NULL, adresse_ip VARCHAR(50) DEFAULT NULL, id_user INT DEFAULT NULL, INDEX IDX_F6E1C0F56B3CA4B (id_user), PRIMARY KEY (id_log)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE avance_salaire (id_avance INT AUTO_INCREMENT NOT NULL, montant NUMERIC(10, 2) NOT NULL, date_demande DATE NOT NULL, motif LONGTEXT DEFAULT NULL, statut VARCHAR(20) NOT NULL, mois_deduction INT DEFAULT NULL, annee_deduction INT DEFAULT NULL, id_employe INT NOT NULL, INDEX IDX_6DA8D10526997AC9 (id_employe), PRIMARY KEY (id_avance)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE axe_navette (id_axe INT AUTO_INCREMENT NOT NULL, nom_axe VARCHAR(100) DEFAULT NULL, point_depart VARCHAR(100) DEFAULT NULL, point_arrivee VARCHAR(100) DEFAULT NULL, PRIMARY KEY (id_axe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE candidat (id_candidat INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) DEFAULT NULL, date_naissance DATE DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, contact VARCHAR(50) DEFAULT NULL, situation_matrimoniale VARCHAR(50) DEFAULT NULL, cv_path VARCHAR(255) NOT NULL, statut VARCHAR(50) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, id_recrutement INT NOT NULL, INDEX IDX_6AB5B4713DF9EEEE (id_recrutement), PRIMARY KEY (id_candidat)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE conge (id_conge INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, type_conge VARCHAR(50) DEFAULT NULL, motif LONGTEXT DEFAULT NULL, statut VARCHAR(20) DEFAULT \'EN_ATTENTE\' NOT NULL, date_demande DATETIME DEFAULT NULL, date_validation DATETIME DEFAULT NULL, id_employe INT NOT NULL, valide_par INT DEFAULT NULL, INDEX IDX_2ED8934826997AC9 (id_employe), INDEX IDX_2ED89348D6A3CAF (valide_par), PRIMARY KEY (id_conge)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cotisation (id_cotisation INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, taux NUMERIC(7, 4) NOT NULL, plafond NUMERIC(10, 2) DEFAULT NULL, seuil NUMERIC(10, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_AE64D2ED77153098 (code), PRIMARY KEY (id_cotisation)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE direction (id_direction INT AUTO_INCREMENT NOT NULL, nom_direction VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id_direction)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe (id_employe INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) DEFAULT NULL, date_naissance DATE DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, contact VARCHAR(30) DEFAULT NULL, situation_matrimoniale VARCHAR(50) DEFAULT NULL, date_embauche DATE NOT NULL, statut VARCHAR(50) DEFAULT \'Actif\' NOT NULL, photo_profil VARCHAR(255) DEFAULT NULL, photo_cin VARCHAR(255) DEFAULT NULL, cv_fichier VARCHAR(255) DEFAULT NULL, id_poste INT NOT NULL, id_user INT DEFAULT NULL, UNIQUE INDEX UNIQ_F804D3B9E7927C74 (email), INDEX IDX_F804D3B9920C4E9B (id_poste), UNIQUE INDEX UNIQ_F804D3B96B3CA4B (id_user), PRIMARY KEY (id_employe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe_document (id_document INT AUTO_INCREMENT NOT NULL, type_document VARCHAR(50) DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, date_ajout DATETIME DEFAULT NULL, id_employe INT NOT NULL, INDEX IDX_9772774826997AC9 (id_employe), PRIMARY KEY (id_document)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe_kpi (mois INT NOT NULL, annee INT NOT NULL, valeur NUMERIC(5, 2) DEFAULT NULL, id_employe INT NOT NULL, id_kpi INT NOT NULL, INDEX IDX_D546717026997AC9 (id_employe), INDEX IDX_D54671703E36B583 (id_kpi), PRIMARY KEY (id_employe, id_kpi, mois, annee)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe_prime (id_employe_prime INT AUTO_INCREMENT NOT NULL, mois INT DEFAULT NULL, annee INT DEFAULT NULL, montant_calcule NUMERIC(10, 2) DEFAULT NULL, valide TINYINT DEFAULT 0 NOT NULL, id_employe INT DEFAULT NULL, id_prime INT DEFAULT NULL, INDEX IDX_195611C026997AC9 (id_employe), INDEX IDX_195611C0BACE4E67 (id_prime), PRIMARY KEY (id_employe_prime)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE groupe (id_groupe INT AUTO_INCREMENT NOT NULL, nom_groupe VARCHAR(100) DEFAULT NULL, type_groupe VARCHAR(20) DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, PRIMARY KEY (id_groupe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE groupe_employe (id_groupe INT NOT NULL, id_employe INT NOT NULL, INDEX IDX_3E6334F7228E39CC (id_groupe), INDEX IDX_3E6334F726997AC9 (id_employe), PRIMARY KEY (id_groupe, id_employe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE irsa_bareme (id_irsa INT AUTO_INCREMENT NOT NULL, salaire_min NUMERIC(10, 2) NOT NULL, salaire_max NUMERIC(10, 2) DEFAULT NULL, taux NUMERIC(7, 4) NOT NULL, PRIMARY KEY (id_irsa)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE kpi (id_kpi INT AUTO_INCREMENT NOT NULL, nom_kpi VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, valeur_min NUMERIC(5, 2) DEFAULT 0 NOT NULL, valeur_max NUMERIC(5, 2) DEFAULT 100 NOT NULL, PRIMARY KEY (id_kpi)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE navette (id_navette INT AUTO_INCREMENT NOT NULL, code_navette VARCHAR(50) DEFAULT NULL, capacite INT DEFAULT NULL, id_axe INT NOT NULL, INDEX IDX_DA54CFCEF2CEF676 (id_axe), PRIMARY KEY (id_navette)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe_navette (id_navette INT NOT NULL, id_employe INT NOT NULL, INDEX IDX_1E5BA36D4C966BE (id_navette), INDEX IDX_1E5BA36D26997AC9 (id_employe), PRIMARY KEY (id_navette, id_employe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE paye (id_paye INT AUTO_INCREMENT NOT NULL, mois INT DEFAULT NULL, annee INT DEFAULT NULL, salaire_brut NUMERIC(10, 2) DEFAULT NULL, retenue NUMERIC(10, 2) DEFAULT NULL, total_prime NUMERIC(10, 2) DEFAULT 0 NOT NULL, salaire_net NUMERIC(10, 2) DEFAULT NULL, date_paiement DATE DEFAULT NULL, id_employe INT NOT NULL, INDEX IDX_C04B89FF26997AC9 (id_employe), PRIMARY KEY (id_paye)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE poste (id_poste INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, salaire_base NUMERIC(10, 2) DEFAULT NULL, id_service INT DEFAULT NULL, id_direction INT DEFAULT NULL, INDEX IDX_7C890FAB3F0033A2 (id_service), INDEX IDX_7C890FABBE7F6D94 (id_direction), PRIMARY KEY (id_poste)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE presence_rh (id_presence INT AUTO_INCREMENT NOT NULL, type_evenement VARCHAR(20) NOT NULL, date_evenement DATE NOT NULL, justification LONGTEXT DEFAULT NULL, justifie TINYINT DEFAULT 0 NOT NULL, id_employe INT NOT NULL, INDEX IDX_53A8787526997AC9 (id_employe), PRIMARY KEY (id_presence)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prime (id_prime INT AUTO_INCREMENT NOT NULL, nom_prime VARCHAR(100) DEFAULT NULL, montant_max NUMERIC(10, 2) DEFAULT NULL, formule LONGTEXT DEFAULT NULL, collectif TINYINT DEFAULT 0 NOT NULL, PRIMARY KEY (id_prime)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prime_kpi (id_prime INT NOT NULL, id_kpi INT NOT NULL, INDEX IDX_CFF43726BACE4E67 (id_prime), INDEX IDX_CFF437263E36B583 (id_kpi), PRIMARY KEY (id_prime, id_kpi)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prime_groupe (id_prime_groupe INT AUTO_INCREMENT NOT NULL, mois INT DEFAULT NULL, annee INT DEFAULT NULL, montant_total NUMERIC(10, 2) DEFAULT NULL, id_groupe INT DEFAULT NULL, id_prime INT DEFAULT NULL, INDEX IDX_81E2FFE3228E39CC (id_groupe), INDEX IDX_81E2FFE3BACE4E67 (id_prime), PRIMARY KEY (id_prime_groupe)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recrutement (id_recrutement INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, suggestions LONGTEXT DEFAULT NULL, statut VARCHAR(20) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, id_poste INT NOT NULL, INDEX IDX_25EB2319920C4E9B (id_poste), PRIMARY KEY (id_recrutement)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE role (id_role INT AUTO_INCREMENT NOT NULL, nom_role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_57698A6AA5B94004 (nom_role), PRIMARY KEY (id_role)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE service (id_service INT AUTO_INCREMENT NOT NULL, nom_service VARCHAR(100) NOT NULL, id_direction INT NOT NULL, INDEX IDX_E19D9AD2BE7F6D94 (id_direction), PRIMARY KEY (id_service)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stage (id_stage INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE DEFAULT NULL, type_stage VARCHAR(50) DEFAULT NULL, sujet LONGTEXT DEFAULT NULL, id_stagiaire INT NOT NULL, id_encadreur INT NOT NULL, INDEX IDX_C27C9369CF574B16 (id_stagiaire), INDEX IDX_C27C9369C384799E (id_encadreur), PRIMARY KEY (id_stage)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stagiaire (id_stagiaire INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, contact VARCHAR(30) DEFAULT NULL, etablissement VARCHAR(150) DEFAULT NULL, niveau VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id_stagiaire)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id_user INT AUTO_INCREMENT NOT NULL, username VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, actif TINYINT NOT NULL, date_creation DATETIME NOT NULL, id_role INT NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3F85E0677 (username), INDEX IDX_1D1C63B3DC499668 (id_role), PRIMARY KEY (id_user)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE audit_log ADD CONSTRAINT FK_F6E1C0F56B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE avance_salaire ADD CONSTRAINT FK_6DA8D10526997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B4713DF9EEEE FOREIGN KEY (id_recrutement) REFERENCES recrutement (id_recrutement)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED8934826997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348D6A3CAF FOREIGN KEY (valide_par) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9920C4E9B FOREIGN KEY (id_poste) REFERENCES poste (id_poste)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B96B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE employe_document ADD CONSTRAINT FK_9772774826997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE employe_kpi ADD CONSTRAINT FK_D546717026997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE employe_kpi ADD CONSTRAINT FK_D54671703E36B583 FOREIGN KEY (id_kpi) REFERENCES kpi (id_kpi)');
        $this->addSql('ALTER TABLE employe_prime ADD CONSTRAINT FK_195611C026997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE employe_prime ADD CONSTRAINT FK_195611C0BACE4E67 FOREIGN KEY (id_prime) REFERENCES prime (id_prime)');
        $this->addSql('ALTER TABLE groupe_employe ADD CONSTRAINT FK_3E6334F7228E39CC FOREIGN KEY (id_groupe) REFERENCES groupe (id_groupe)');
        $this->addSql('ALTER TABLE groupe_employe ADD CONSTRAINT FK_3E6334F726997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE navette ADD CONSTRAINT FK_DA54CFCEF2CEF676 FOREIGN KEY (id_axe) REFERENCES axe_navette (id_axe)');
        $this->addSql('ALTER TABLE employe_navette ADD CONSTRAINT FK_1E5BA36D4C966BE FOREIGN KEY (id_navette) REFERENCES navette (id_navette)');
        $this->addSql('ALTER TABLE employe_navette ADD CONSTRAINT FK_1E5BA36D26997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE paye ADD CONSTRAINT FK_C04B89FF26997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FAB3F0033A2 FOREIGN KEY (id_service) REFERENCES service (id_service)');
        $this->addSql('ALTER TABLE poste ADD CONSTRAINT FK_7C890FABBE7F6D94 FOREIGN KEY (id_direction) REFERENCES direction (id_direction)');
        $this->addSql('ALTER TABLE presence_rh ADD CONSTRAINT FK_53A8787526997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE prime_kpi ADD CONSTRAINT FK_CFF43726BACE4E67 FOREIGN KEY (id_prime) REFERENCES prime (id_prime)');
        $this->addSql('ALTER TABLE prime_kpi ADD CONSTRAINT FK_CFF437263E36B583 FOREIGN KEY (id_kpi) REFERENCES kpi (id_kpi)');
        $this->addSql('ALTER TABLE prime_groupe ADD CONSTRAINT FK_81E2FFE3228E39CC FOREIGN KEY (id_groupe) REFERENCES groupe (id_groupe)');
        $this->addSql('ALTER TABLE prime_groupe ADD CONSTRAINT FK_81E2FFE3BACE4E67 FOREIGN KEY (id_prime) REFERENCES prime (id_prime)');
        $this->addSql('ALTER TABLE recrutement ADD CONSTRAINT FK_25EB2319920C4E9B FOREIGN KEY (id_poste) REFERENCES poste (id_poste)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2BE7F6D94 FOREIGN KEY (id_direction) REFERENCES direction (id_direction)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369CF574B16 FOREIGN KEY (id_stagiaire) REFERENCES stagiaire (id_stagiaire)');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369C384799E FOREIGN KEY (id_encadreur) REFERENCES employe (id_employe)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3DC499668 FOREIGN KEY (id_role) REFERENCES role (id_role)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit_log DROP FOREIGN KEY FK_F6E1C0F56B3CA4B');
        $this->addSql('ALTER TABLE avance_salaire DROP FOREIGN KEY FK_6DA8D10526997AC9');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B4713DF9EEEE');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED8934826997AC9');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348D6A3CAF');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9920C4E9B');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B96B3CA4B');
        $this->addSql('ALTER TABLE employe_document DROP FOREIGN KEY FK_9772774826997AC9');
        $this->addSql('ALTER TABLE employe_kpi DROP FOREIGN KEY FK_D546717026997AC9');
        $this->addSql('ALTER TABLE employe_kpi DROP FOREIGN KEY FK_D54671703E36B583');
        $this->addSql('ALTER TABLE employe_prime DROP FOREIGN KEY FK_195611C026997AC9');
        $this->addSql('ALTER TABLE employe_prime DROP FOREIGN KEY FK_195611C0BACE4E67');
        $this->addSql('ALTER TABLE groupe_employe DROP FOREIGN KEY FK_3E6334F7228E39CC');
        $this->addSql('ALTER TABLE groupe_employe DROP FOREIGN KEY FK_3E6334F726997AC9');
        $this->addSql('ALTER TABLE navette DROP FOREIGN KEY FK_DA54CFCEF2CEF676');
        $this->addSql('ALTER TABLE employe_navette DROP FOREIGN KEY FK_1E5BA36D4C966BE');
        $this->addSql('ALTER TABLE employe_navette DROP FOREIGN KEY FK_1E5BA36D26997AC9');
        $this->addSql('ALTER TABLE paye DROP FOREIGN KEY FK_C04B89FF26997AC9');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FAB3F0033A2');
        $this->addSql('ALTER TABLE poste DROP FOREIGN KEY FK_7C890FABBE7F6D94');
        $this->addSql('ALTER TABLE presence_rh DROP FOREIGN KEY FK_53A8787526997AC9');
        $this->addSql('ALTER TABLE prime_kpi DROP FOREIGN KEY FK_CFF43726BACE4E67');
        $this->addSql('ALTER TABLE prime_kpi DROP FOREIGN KEY FK_CFF437263E36B583');
        $this->addSql('ALTER TABLE prime_groupe DROP FOREIGN KEY FK_81E2FFE3228E39CC');
        $this->addSql('ALTER TABLE prime_groupe DROP FOREIGN KEY FK_81E2FFE3BACE4E67');
        $this->addSql('ALTER TABLE recrutement DROP FOREIGN KEY FK_25EB2319920C4E9B');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2BE7F6D94');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369CF574B16');
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369C384799E');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3DC499668');
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('DROP TABLE axe_navette');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE conge');
        $this->addSql('DROP TABLE cotisation');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE employe_document');
        $this->addSql('DROP TABLE employe_kpi');
        $this->addSql('DROP TABLE employe_prime');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_employe');
        $this->addSql('DROP TABLE irsa_bareme');
        $this->addSql('DROP TABLE kpi');
        $this->addSql('DROP TABLE navette');
        $this->addSql('DROP TABLE employe_navette');
        $this->addSql('DROP TABLE paye');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE presence_rh');
        $this->addSql('DROP TABLE prime');
        $this->addSql('DROP TABLE prime_kpi');
        $this->addSql('DROP TABLE prime_groupe');
        $this->addSql('DROP TABLE recrutement');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stagiaire');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

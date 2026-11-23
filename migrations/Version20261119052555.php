<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261119052555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kpi CHANGE valeur_min valeur_min NUMERIC(5, 2) DEFAULT 0 NOT NULL, CHANGE valeur_max valeur_max NUMERIC(5, 2) DEFAULT 100 NOT NULL');
        $this->addSql('ALTER TABLE paye CHANGE total_prime total_prime NUMERIC(10, 2) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kpi CHANGE valeur_min valeur_min NUMERIC(5, 2) DEFAULT \'0.00\' NOT NULL, CHANGE valeur_max valeur_max NUMERIC(5, 2) DEFAULT \'100.00\' NOT NULL');
        $this->addSql('ALTER TABLE paye CHANGE total_prime total_prime NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
    }
}

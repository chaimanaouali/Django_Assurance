<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225192658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, type_couverture_id INT DEFAULT NULL, date_debut_contrat DATE NOT NULL, datefin_contrat DATE NOT NULL, adresse_assur VARCHAR(255) NOT NULL, numero_assur VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_603499934FE72DC0 (type_couverture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499934FE72DC0 FOREIGN KEY (type_couverture_id) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499934FE72DC0');
        $this->addSql('DROP TABLE contrat');
    }
}

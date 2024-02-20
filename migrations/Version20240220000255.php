<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220000255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE constat ADD identifiant VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE traitement ADD identifiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D271016936D FOREIGN KEY (identifiant_id) REFERENCES constat (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A356D271016936D ON traitement (identifiant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE constat DROP identifiant');
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D271016936D');
        $this->addSql('DROP INDEX UNIQ_2A356D271016936D ON traitement');
        $this->addSql('ALTER TABLE traitement DROP identifiant_id');
    }
}

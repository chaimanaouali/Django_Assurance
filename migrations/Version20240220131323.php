<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220131323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE constat (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, lieu VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, conditionroute VARCHAR(255) NOT NULL, rapportepolice TINYINT(1) NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE traitement (id INT AUTO_INCREMENT NOT NULL, date_taitement DATETIME NOT NULL, responsable VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, remarque VARCHAR(255) NOT NULL, identifiant_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_2A356D271016936D (identifiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D271016936D FOREIGN KEY (identifiant_id) REFERENCES constat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D271016936D');
        $this->addSql('DROP TABLE constat');
        $this->addSql('DROP TABLE traitement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

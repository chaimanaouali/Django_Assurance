<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218173315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture ADD email_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FA832C1C9 FOREIGN KEY (email_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E9E2810FA832C1C9 ON voiture (email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FA832C1C9');
        $this->addSql('DROP INDEX IDX_E9E2810FA832C1C9 ON voiture');
        $this->addSql('ALTER TABLE voiture DROP email_id');
    }
}

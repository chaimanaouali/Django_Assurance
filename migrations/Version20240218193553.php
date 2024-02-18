<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218193553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse_devis ADD email_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_devis ADD CONSTRAINT FK_64CEBE48A832C1C9 FOREIGN KEY (email_id) REFERENCES devis (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64CEBE48A832C1C9 ON reponse_devis (email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse_devis DROP FOREIGN KEY FK_64CEBE48A832C1C9');
        $this->addSql('DROP INDEX UNIQ_64CEBE48A832C1C9 ON reponse_devis');
        $this->addSql('ALTER TABLE reponse_devis DROP email_id');
    }
}

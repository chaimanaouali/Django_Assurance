<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301184829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP INDEX FK_603499934FE72DC0, ADD UNIQUE INDEX UNIQ_603499934FE72DC0 (type_couverture_id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499934FE72DC0 FOREIGN KEY (type_couverture_id) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat DROP INDEX UNIQ_603499934FE72DC0, ADD INDEX FK_603499934FE72DC0 (type_couverture_id)');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499934FE72DC0');
    }
}

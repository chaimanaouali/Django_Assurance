<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217003821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify image and status column lengths';
    }

    public function up(Schema $schema): void
    {
        // Increase the length of the `image` and `status` columns
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE status status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Restore the original length of the `image` and `status` columns
        $this->addSql('ALTER TABLE post CHANGE image image VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE status status VARCHAR(1) NOT NULL');
    }
}

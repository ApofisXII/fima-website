<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218092051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_edition ADD date_start DATETIME NULL, ADD date_end DATETIME NULL, DROP year, DROP period_description');
        $this->addSql('UPDATE urbino_edition SET date_start = NOW(), date_end = NOW() WHERE date_start IS NULL');
        $this->addSql('ALTER TABLE urbino_edition MODIFY date_start DATETIME NOT NULL, MODIFY date_end DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_edition ADD year INT NOT NULL, ADD period_description VARCHAR(255) DEFAULT NULL, DROP date_start, DROP date_end');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217192615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_start and date_end to urbino_course, remove accommodation fields from urbino_edition';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course ADD date_start DATETIME DEFAULT NULL, ADD date_end DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE urbino_edition DROP accommodation_info_it, DROP accommodation_info_en');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course DROP date_start, DROP date_end');
        $this->addSql('ALTER TABLE urbino_edition ADD accommodation_info_it LONGTEXT NOT NULL, ADD accommodation_info_en LONGTEXT NOT NULL');
    }
}

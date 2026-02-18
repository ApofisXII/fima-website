<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216142553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_edition ADD enrollment_info_it LONGTEXT NOT NULL, ADD enrollment_info_en LONGTEXT NOT NULL, ADD accommodation_info_it LONGTEXT NOT NULL, ADD accommodation_info_en LONGTEXT NOT NULL, DROP enrollement_info_it, DROP enrollement_info_en, DROP accomodation_info_it, DROP accomodation_info_en');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_edition ADD enrollement_info_it LONGTEXT NOT NULL, ADD enrollement_info_en LONGTEXT NOT NULL, ADD accomodation_info_it LONGTEXT NOT NULL, ADD accomodation_info_en LONGTEXT NOT NULL, DROP enrollment_info_it, DROP enrollment_info_en, DROP accommodation_info_it, DROP accommodation_info_en');
    }
}

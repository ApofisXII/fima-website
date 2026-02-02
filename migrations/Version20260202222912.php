<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202222912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_full_name VARCHAR(255) NOT NULL, is_preselection_required TINYINT NOT NULL, is_sold_out TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, subject_it VARCHAR(255) DEFAULT NULL, subject_en VARCHAR(255) DEFAULT NULL, program_description_it LONGTEXT DEFAULT NULL, program_description_en LONGTEXT DEFAULT NULL, bio_description_it LONGTEXT DEFAULT NULL, bio_description_en LONGTEXT DEFAULT NULL, urbino_edition_id INT NOT NULL, INDEX IDX_169E6FB91B37BF7E (urbino_edition_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB91B37BF7E FOREIGN KEY (urbino_edition_id) REFERENCES urbino_edition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB91B37BF7E');
        $this->addSql('DROP TABLE course');
    }
}

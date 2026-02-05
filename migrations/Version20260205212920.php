<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205212920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE urbino_course (id INT AUTO_INCREMENT NOT NULL, teacher_full_name VARCHAR(255) NOT NULL, is_preselection_required TINYINT NOT NULL, is_sold_out TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, subject_it VARCHAR(255) DEFAULT NULL, subject_en VARCHAR(255) DEFAULT NULL, program_description_it LONGTEXT DEFAULT NULL, program_description_en LONGTEXT DEFAULT NULL, bio_description_it LONGTEXT DEFAULT NULL, bio_description_en LONGTEXT DEFAULT NULL, ordering INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, urbino_edition_id INT NOT NULL, INDEX IDX_AA3F29581B37BF7E (urbino_edition_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE urbino_event (id INT AUTO_INCREMENT NOT NULL, event_datetime DATETIME NOT NULL, title VARCHAR(255) NOT NULL, subtitle_it VARCHAR(255) NOT NULL, subtitle_en VARCHAR(255) DEFAULT NULL, location_short VARCHAR(255) DEFAULT NULL, location_long VARCHAR(255) DEFAULT NULL, ticket_link VARCHAR(255) DEFAULT NULL, description_it LONGTEXT DEFAULT NULL, description_en LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_deleted TINYINT NOT NULL, is_public TINYINT NOT NULL, urbino_edition_id INT NOT NULL, INDEX IDX_FC6BF1161B37BF7E (urbino_edition_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE urbino_course ADD CONSTRAINT FK_AA3F29581B37BF7E FOREIGN KEY (urbino_edition_id) REFERENCES urbino_edition (id)');
        $this->addSql('ALTER TABLE urbino_event ADD CONSTRAINT FK_FC6BF1161B37BF7E FOREIGN KEY (urbino_edition_id) REFERENCES urbino_edition (id)');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY `FK_169E6FB91B37BF7E`');
        $this->addSql('DROP TABLE course');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_full_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_preselection_required TINYINT NOT NULL, is_sold_out TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, subject_it VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, subject_en VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, program_description_it LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, program_description_en LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, bio_description_it LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, bio_description_en LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, urbino_edition_id INT NOT NULL, ordering INT DEFAULT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_169E6FB91B37BF7E (urbino_edition_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT `FK_169E6FB91B37BF7E` FOREIGN KEY (urbino_edition_id) REFERENCES urbino_edition (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE urbino_course DROP FOREIGN KEY FK_AA3F29581B37BF7E');
        $this->addSql('ALTER TABLE urbino_event DROP FOREIGN KEY FK_FC6BF1161B37BF7E');
        $this->addSql('DROP TABLE urbino_course');
        $this->addSql('DROP TABLE urbino_event');
    }
}

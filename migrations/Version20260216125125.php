<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216125125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add urbino_course_category table and nullable FK to urbino_course';
    }

    public function up(Schema $schema): void
    {
        // Creare tabella categorie
        $this->addSql('CREATE TABLE urbino_course_category (id INT AUTO_INCREMENT NOT NULL, name_it VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_deleted TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');

        // Aggiungere colonna FK temporanea nullable
        $this->addSql('ALTER TABLE urbino_course ADD urbino_course_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE urbino_course ADD CONSTRAINT FK_AA3F2958632F637B FOREIGN KEY (urbino_course_category_id) REFERENCES urbino_course_category (id)');
        $this->addSql('CREATE INDEX IDX_AA3F2958632F637B ON urbino_course (urbino_course_category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_course DROP FOREIGN KEY FK_AA3F2958632F637B');
        $this->addSql('DROP INDEX IDX_AA3F2958632F637B ON urbino_course');
        $this->addSql('ALTER TABLE urbino_course DROP urbino_course_category_id');
        $this->addSql('DROP TABLE urbino_course_category');
    }
}

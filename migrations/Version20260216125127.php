<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration 3: Remove subject_it/subject_en columns and make category FK required
 */
final class Version20260216125127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove subject_it/subject_en columns and make category required';
    }

    public function up(Schema $schema): void
    {
        // 1. Rimuovere le colonne vecchie
        $this->addSql('ALTER TABLE urbino_course DROP subject_it');
        $this->addSql('ALTER TABLE urbino_course DROP subject_en');

        // 2. Rendere la FK non nullable
        $this->addSql('ALTER TABLE urbino_course MODIFY urbino_course_category_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Rendere la FK nullable
        $this->addSql('ALTER TABLE urbino_course MODIFY urbino_course_category_id INT DEFAULT NULL');

        // Riaggiungere le colonne
        $this->addSql('ALTER TABLE urbino_course ADD subject_it VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE urbino_course ADD subject_en VARCHAR(255) DEFAULT NULL');

        // Ripopolare i subject dai dati delle categorie
        $this->addSql("
            UPDATE urbino_course uc
            INNER JOIN urbino_course_category ucc ON uc.urbino_course_category_id = ucc.id
            SET uc.subject_it = ucc.name_it,
                uc.subject_en = ucc.name_en
        ");
    }
}

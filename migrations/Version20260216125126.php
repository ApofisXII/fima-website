<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration 2: Migrate existing subject_it/subject_en data to categories
 */
final class Version20260216125126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate existing subject_it/subject_en to categories';
    }

    public function up(Schema $schema): void
    {
        // 1. Estrarre subject distinti e creare categorie
        $this->addSql("
            INSERT INTO urbino_course_category (name_it, name_en, created_at, updated_at, is_deleted)
            SELECT DISTINCT
                subject_it,
                subject_en,
                NOW(),
                NOW(),
                0
            FROM urbino_course
            WHERE subject_it IS NOT NULL
            AND subject_it != ''
            AND is_deleted = 0
        ");

        // 2. Aggiornare i corsi con le categorie corrispondenti
        $this->addSql("
            UPDATE urbino_course uc
            INNER JOIN urbino_course_category ucc
                ON uc.subject_it COLLATE utf8mb4_unicode_ci = ucc.name_it COLLATE utf8mb4_unicode_ci
                AND (uc.subject_en COLLATE utf8mb4_unicode_ci = ucc.name_en COLLATE utf8mb4_unicode_ci OR (uc.subject_en IS NULL AND ucc.name_en IS NULL))
            SET uc.urbino_course_category_id = ucc.id
            WHERE uc.is_deleted = 0
        ");
    }

    public function down(Schema $schema): void
    {
        // Reset associations
        $this->addSql('UPDATE urbino_course SET urbino_course_category_id = NULL');

        // Note: We don't delete categories created as they may have been modified
    }
}

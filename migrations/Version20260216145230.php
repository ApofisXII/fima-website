<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216145230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ordering field to urbino_course_category';
    }

    public function up(Schema $schema): void
    {
        // 1. Add ordering column
        $this->addSql('ALTER TABLE urbino_course_category ADD ordering INT DEFAULT NULL');

        // 2. Initialize ordering for existing categories (ordered by name_it)
        $this->addSql('
            SET @row_number = 0;
            UPDATE urbino_course_category
            SET ordering = (@row_number:=@row_number + 1)
            ORDER BY name_it ASC
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course_category DROP ordering');
    }
}

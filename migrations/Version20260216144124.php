<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216144124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace is_afternoon_course boolean with schedule_type string';
    }

    public function up(Schema $schema): void
    {
        // 1. Add schedule_type as nullable
        $this->addSql('ALTER TABLE urbino_course ADD schedule_type VARCHAR(255) DEFAULT NULL');

        // 2. Migrate data: if is_afternoon_course = 1 then "afternoon", else "main"
        $this->addSql('UPDATE urbino_course SET schedule_type = "afternoon" WHERE is_afternoon_course = 1');
        $this->addSql('UPDATE urbino_course SET schedule_type = "main" WHERE is_afternoon_course = 0');

        // 3. Make schedule_type NOT NULL
        $this->addSql('ALTER TABLE urbino_course MODIFY schedule_type VARCHAR(255) NOT NULL');

        // 4. Drop old column
        $this->addSql('ALTER TABLE urbino_course DROP is_afternoon_course');
    }

    public function down(Schema $schema): void
    {
        // 1. Add is_afternoon_course as nullable
        $this->addSql('ALTER TABLE urbino_course ADD is_afternoon_course TINYINT DEFAULT NULL');

        // 2. Migrate data back: if schedule_type = "afternoon" then 1, else 0
        $this->addSql('UPDATE urbino_course SET is_afternoon_course = 1 WHERE schedule_type = "afternoon"');
        $this->addSql('UPDATE urbino_course SET is_afternoon_course = 0 WHERE schedule_type = "main"');

        // 3. Make is_afternoon_course NOT NULL
        $this->addSql('ALTER TABLE urbino_course MODIFY is_afternoon_course TINYINT NOT NULL');

        // 4. Drop new column
        $this->addSql('ALTER TABLE urbino_course DROP schedule_type');
    }
}

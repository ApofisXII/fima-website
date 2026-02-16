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
        $this->addSql('ALTER TABLE urbino_course ADD schedule_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE urbino_course SET schedule_type = "afternoon" WHERE is_afternoon_course = 1');
        $this->addSql('UPDATE urbino_course SET schedule_type = "main" WHERE is_afternoon_course = 0');
        $this->addSql('ALTER TABLE urbino_course MODIFY schedule_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE urbino_course DROP is_afternoon_course');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_course ADD is_afternoon_course TINYINT DEFAULT NULL');
        $this->addSql('UPDATE urbino_course SET is_afternoon_course = 1 WHERE schedule_type = "afternoon"');
        $this->addSql('UPDATE urbino_course SET is_afternoon_course = 0 WHERE schedule_type = "main"');
        $this->addSql('ALTER TABLE urbino_course MODIFY is_afternoon_course TINYINT NOT NULL');
        $this->addSql('ALTER TABLE urbino_course DROP schedule_type');
    }
}

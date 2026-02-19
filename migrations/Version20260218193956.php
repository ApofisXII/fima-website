<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218193956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course ADD schedule_type VARCHAR(255) NOT NULL, DROP is_afternoon_course');
        $this->addSql('ALTER TABLE urbino_course_category ADD ordering INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course ADD is_afternoon_course TINYINT NOT NULL, DROP schedule_type');
        $this->addSql('ALTER TABLE urbino_course_category DROP ordering');
    }
}

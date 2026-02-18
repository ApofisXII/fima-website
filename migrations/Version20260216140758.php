<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260216140758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add has_cover_image field to urbino_event';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_event ADD has_cover_image TINYINT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE urbino_event ALTER has_cover_image DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_event DROP has_cover_image');
    }
}

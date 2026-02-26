<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226143345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE news ADD has_poster TINYINT NOT NULL');

        $uploadsDir = __DIR__ . '/../public/uploads-news/';
        $imagesDir = $uploadsDir . 'images/';

        if (!is_dir($imagesDir)) {
            mkdir($imagesDir, 0755, true);
        }

        foreach (glob($uploadsDir . '*.webp') as $file) {
            rename($file, $imagesDir . basename($file));
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE news DROP has_poster');
    }
}

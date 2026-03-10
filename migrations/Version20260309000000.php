<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260309000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add price_info_it/en to urbino_course (migrating from price_cents), drop enrollment_link from urbino_edition';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_course ADD price_info_it VARCHAR(255) DEFAULT NULL, ADD price_info_en VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE urbino_course SET price_info_it = CONCAT(FORMAT(price_cents / 100, 2), ' €'), price_info_en = CONCAT(FORMAT(price_cents / 100, 2), ' €') WHERE price_cents IS NOT NULL");
        $this->addSql('ALTER TABLE urbino_course DROP price_cents');
        $this->addSql('ALTER TABLE urbino_edition DROP enrollment_link');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE urbino_course ADD price_cents INT DEFAULT NULL, DROP price_info_it, DROP price_info_en');
        $this->addSql('ALTER TABLE urbino_edition ADD enrollment_link VARCHAR(2048) DEFAULT NULL');
    }
}

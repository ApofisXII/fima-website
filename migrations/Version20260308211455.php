<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260308211455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE urbino_course SET price_info_it = CONCAT(FORMAT(price_cents / 100, 2), ' €'), price_info_en = CONCAT(FORMAT(price_cents / 100, 2), ' €') WHERE price_cents IS NOT NULL AND price_info_it IS NULL");
        $this->addSql('ALTER TABLE urbino_course DROP price_cents, CHANGE price_info_it price_info_it LONGTEXT DEFAULT NULL, CHANGE price_info_en price_info_en LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE urbino_course ADD price_cents INT DEFAULT NULL, CHANGE price_info_it price_info_it VARCHAR(255) DEFAULT NULL, CHANGE price_info_en price_info_en VARCHAR(255) DEFAULT NULL');
    }
}

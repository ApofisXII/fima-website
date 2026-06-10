<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260610120000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE recercare_issue (id INT AUTO_INCREMENT NOT NULL, volume INT NOT NULL, year INT NOT NULL, isbn VARCHAR(100) DEFAULT NULL, has_cover TINYINT(1) NOT NULL, has_index_it TINYINT(1) NOT NULL, has_index_en TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE recercare_issue');
    }
}

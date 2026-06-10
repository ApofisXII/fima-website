<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260610145514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add note column and remove has_index_it/has_index_en from recercare_issue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recercare_issue ADD note VARCHAR(255) DEFAULT NULL, DROP has_index_it, DROP has_index_en');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recercare_issue DROP note, ADD has_index_it TINYINT(1) NOT NULL, ADD has_index_en TINYINT(1) NOT NULL');
    }
}

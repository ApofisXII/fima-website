<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226144006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename schedule_type values: main→individual, secondary_afternoon→ensemble';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE urbino_course SET schedule_type = 'schedule_type_individual' WHERE schedule_type = 'schedule_type_main'");
        $this->addSql("UPDATE urbino_course SET schedule_type = 'schedule_type_ensemble' WHERE schedule_type = 'schedule_type_secondary_afternoon'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE urbino_course SET schedule_type = 'schedule_type_main' WHERE schedule_type = 'schedule_type_individual'");
        $this->addSql("UPDATE urbino_course SET schedule_type = 'schedule_type_secondary_afternoon' WHERE schedule_type = 'schedule_type_ensemble'");
    }
}

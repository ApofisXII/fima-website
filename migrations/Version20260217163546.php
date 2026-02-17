<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Update schedule_type from 'afternoon' to 'secondary_afternoon'
 */
final class Version20260217163546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update schedule_type values from afternoon to secondary_afternoon';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE urbino_course SET schedule_type = "secondary_afternoon" WHERE schedule_type = "afternoon"');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE urbino_course SET schedule_type = "afternoon" WHERE schedule_type = "secondary_afternoon"');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260219094949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE news_category (id INT AUTO_INCREMENT NOT NULL, name_it VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_deleted TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE news ADD news_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399503B732BAD FOREIGN KEY (news_category_id) REFERENCES news_category (id)');
        $this->addSql('CREATE INDEX IDX_1DD399503B732BAD ON news (news_category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE news_category');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399503B732BAD');
        $this->addSql('DROP INDEX IDX_1DD399503B732BAD ON news');
        $this->addSql('ALTER TABLE news DROP news_category_id');
    }
}

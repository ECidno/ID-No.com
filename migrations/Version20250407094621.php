<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407094621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD health_care_proxy TINYINT(1) DEFAULT 0 NOT NULL, ADD health_care_proxy_comment TEXT DEFAULT NULL, ADD health_care_proxy_show TINYINT(1) DEFAULT 1 NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP health_care_proxy, DROP health_care_proxy_comment. DROP health_care_proxy_show');
    }
}

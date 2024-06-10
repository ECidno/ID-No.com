<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424075846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD conditions_active TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD medications_active TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD allergies_active TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD reanimation VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD reanimation_comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD reanimation_show TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD organspender_comment TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP conditions_active');
        $this->addSql('ALTER TABLE person DROP medications_active');
        $this->addSql('ALTER TABLE person DROP allergies_active');
        $this->addSql('ALTER TABLE person DROP reanimation');
        $this->addSql('ALTER TABLE person DROP reanimation_comment');
        $this->addSql('ALTER TABLE person DROP reanimation_show');
        $this->addSql('ALTER TABLE person DROP organspender_comment');
    }
}

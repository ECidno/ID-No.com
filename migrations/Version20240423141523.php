<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423141523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD operations_active TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD patientenverf_comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD important_note TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD important_note_show TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD pacemaker TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE person ADD pacemaker_comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD pacemaker_show TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE person ADD pregnancy TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE person ADD pregnancy_comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD pregnancy_show TINYINT(1) DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP operations_active');
        $this->addSql('ALTER TABLE person DROP patientenverf_comment');
        $this->addSql('ALTER TABLE person DROP important_note');
        $this->addSql('ALTER TABLE person DROP important_note_show');
        $this->addSql('ALTER TABLE person DROP pacemaker');
        $this->addSql('ALTER TABLE person DROP pacemaker_comment');
        $this->addSql('ALTER TABLE person DROP pacemaker_show');
        $this->addSql('ALTER TABLE person DROP pregnancy');
        $this->addSql('ALTER TABLE person DROP pregnancy_comment');
        $this->addSql('ALTER TABLE person DROP pregnancy_show');
    }
}

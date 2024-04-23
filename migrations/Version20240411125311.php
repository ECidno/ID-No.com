<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240411125311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pass_entry_condition (
            id INT AUTO_INCREMENT NOT NULL,
            sorting INT NOT NULL,
            person_id INT NOT NULL,
            category VARCHAR(255) DEFAULT NULL,
            title TEXT DEFAULT NULL,
            comment TEXT DEFAULT NULL,
            created DATETIME,
            last_change DATETIME,
            PRIMARY KEY(id)
            )'
        );

        $this->addSql('CREATE TABLE pass_entry_medication (
            id INT AUTO_INCREMENT NOT NULL,
            sorting INT NOT NULL,
            person_id INT NOT NULL,
            ingredient VARCHAR(255) DEFAULT NULL,
            trade_name VARCHAR(255) DEFAULT NULL,
            dosage VARCHAR(255) DEFAULT NULL,
            consumption VARCHAR(255) DEFAULT NULL,
            emergency_notes TEXT DEFAULT NULL,
            comment TEXT DEFAULT NULL,
            created DATETIME,
            last_change DATETIME,
            PRIMARY KEY(id)
            )'
        );

        $this->addSql('CREATE TABLE pass_entry_allergy (
            id INT AUTO_INCREMENT NOT NULL,
            sorting INT NOT NULL,
            person_id INT NOT NULL,
            category VARCHAR(255) DEFAULT NULL,
            comment TEXT DEFAULT NULL,
            created DATETIME,
            last_change DATETIME,
            PRIMARY KEY(id)
            )'
        );

        // $this->addSql('CREATE TABLE pass_entry_surgery (
        //     id INT AUTO_INCREMENT NOT NULL,
        //     sorting INT NOT NULL,
        //     person_id INT NOT NULL,
        //     category VARCHAR(255) DEFAULT NULL,
        //     comment TEXT DEFAULT NULL,
        //     created DATETIME,
        //     last_change DATETIME,
        //     PRIMARY KEY(id)
        //     )'
        // );

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227161745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
        CREATE TABLE `articles_list` (
	    `id` INT NOT NULL AUTO_INCREMENT,
	    `name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	    `unit_id` INT NOT NULL,
	    PRIMARY KEY (`id`),
	    UNIQUE INDEX `id` (`id`),
	    INDEX `unit_id` (`unit_id`)
        )
        COLLATE='latin1_swedish_ci'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `articles_list`
        DROP INDEX `unit_id`; 
        DROP TABLE `articles_list`");
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227165949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
        CREATE TABLE `articles` (
	    `id` INT NOT NULL AUTO_INCREMENT,
	    `name_id` INT NOT NULL,
	    `unit_id` INT NOT NULL,
	    `storages_list_id` INT NOT NULL,
	    `amount` FLOAT NULL DEFAULT NULL,
	    `vat` FLOAT NULL DEFAULT NULL,
	    `price` FLOAT NULL DEFAULT NULL,
	    `code` INT NULL DEFAULT NULL,
	    `file_path` VARCHAR(200) NULL DEFAULT NULL,
	    PRIMARY KEY (`id`),
	    UNIQUE INDEX `id` (`id`),
	    INDEX `name_id` (`name_id`),
	    INDEX `unit_id` (`unit_id`),
	    INDEX `storages_list_id` (`storages_list_id`)
)
COLLATE='latin1_swedish_ci'
;
");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `articles`
        DROP INDEX `name_id`,
        DROP INDEX `unit_id`, 
        DROP INDEX `storages_list_id`;

        DROP TABLE `articles`;");
    }
}

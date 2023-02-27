<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227155927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE `units` (
		`id` INT NOT NULL AUTO_INCREMENT,
	    `unit` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	    PRIMARY KEY (`id`),
	    UNIQUE INDEX `id` (`id`)
)
    COLLATE='latin1_swedish_ci'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE `units`");

    }
}

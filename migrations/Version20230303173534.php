<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303173534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `user`
	    ADD INDEX `storage_list_id` (`storage_list_id`);");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `user`
	    DROP INDEX `storage_list_id`;");

    }
}

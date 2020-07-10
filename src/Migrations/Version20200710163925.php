<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to create the proper session table.
 */
final class Version20200710163925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create the session table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
            'CREATE TABLE `session` (
                `session_id` VARCHAR(128) NOT NULL PRIMARY KEY,
                `session_data` BLOB NOT NULL,
                `session_time` INTEGER UNSIGNED NOT NULL,
                `session_lifetime` INTEGER UNSIGNED NOT NULL
            ) COLLATE utf8mb4_bin, ENGINE = InnoDB;'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `session`;');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191021153945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `serials` ADD COLUMN `screen` VARCHAR(2048) NOT NULL');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE `serials` DROP COLUMN `screen`');

    }
}

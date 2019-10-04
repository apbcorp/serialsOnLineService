<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191003122216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE `ratings` (
                `id` INT(11) AUTO_INCREMENT NOT NULL, 
                `episodeId` int(11) NOT NULL,
                `type` int(11) NOT NULL,
                `value` int(11) NOT NULL,
                PRIMARY KEY(id),
                UNIQUE `episode_type`(`episodeId`, `type`)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `serialSynonims` (
                `serialId` INT(11) NOT NULL, 
                `name` VARCHAR(512) NOT NULL,
                PRIMARY KEY(`serialId`, `name`)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `ratings`');
        $this->addSql('DROP TABLE `serialSynonims`');
    }
}

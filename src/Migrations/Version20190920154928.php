<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190920154928 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE `serials` (
                `id` INT(11) AUTO_INCREMENT NOT NULL, 
                `name` VARCHAR(256) NOT NULL,
                `visible` TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `seasons` (
                `id` INT(11) AUTO_INCREMENT NOT NULL,
                `serialId` INT(11) NOT NULL, 
                `number` VARCHAR(256) NOT NULL,
                `visible` TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `episodes` (
                `id` INT(11) AUTO_INCREMENT NOT NULL,
                `seasonId` INT(11) NOT NULL, 
                `number` INT(11) NOT NULL,
                `releaseDate` DATETIME NOT NULL,
                `visible` TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `streams` (
                `id` INT(11) AUTO_INCREMENT NOT NULL,
                `episodeId` INT(11) NOT NULL, 
                `translatedBy` VARCHAR(512) NOT NULL, 
                `streamProvider` VARCHAR(512) NOT NULL, 
                `url` VARCHAR(512) NOT NULL,
                `resolution` INT(11) NOT NULL,
                `visible` TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `serials`');
        $this->addSql('DROP TABLE `seasons`');
        $this->addSql('DROP TABLE `episodes`');
        $this->addSql('DROP TABLE `streams`');
    }
}

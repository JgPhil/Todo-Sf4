<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200917085557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // crreation of the application tables
        $this->addSql('CREATE TABLE task (
            id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            title VARCHAR(100),
            content VARCHAR(255),
            is_done SMALLINT,
            created_at DATETIME
        )');
        $this->addSql('CREATE TABLE user (
            id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            username VARCHAR(100),
            password VARCHAR(255),
            email VARCHAR(100)
        )');

        //add the relation between tasks and users
        $this->addSql('ALTER TABLE task ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A76ED395 ON task (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('DROP INDEX IDX_527EDB25A76ED395 ON task');
        $this->addSql('ALTER TABLE task DROP user_id');
    }
}

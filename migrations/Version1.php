<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use DateTime;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1 extends AbstractMigration
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
            content VARCHAR(10000),
            is_done SMALLINT,
            created_at DATETIME
        )');
        $this->addSql('CREATE TABLE user (
            id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            username VARCHAR(100),
            password VARCHAR(255),
            email VARCHAR(100)
        )');

            // generate tasks  with no user binded

        for ($i = 1; $i < 21; $i ++)
        {
            $now = new DateTime();
            $task = array(
                'id' => null,
                'title' => 'Titre de l\'article anonyme n° '.$i,
                'content' => 'Contenu de l\'article anonyme n° '.$i,
                'is_done' => 0,
                'created_at' => $now->format('Y-m-d H:i:s')
            );

            $this->addSql('INSERT INTO task VALUES(:id, :title, :content, :is_done, :created_at)', $task);
        }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
